{{--
    TEDI Header Language.
    Port of angular/tedi/components/layout/header/header-language/header-language.component.{ts,html}

    Angular wraps the trigger/options in `<tedi-popover>` (floating-ui
    positioning), which is out of scope for this template-only phase
    (CONVENTIONS.md §7.3 — tooltip/dropdown/modal positioning). This port emits
    only the classes header-language itself owns (`tedi-header-language`,
    `__label`, `__chevron`, `--label-left`, plus the shared `tedi-link
    tedi-header__link-button` utility from header.component.scss) and renders
    the trigger + full language list as static, always-visible markup — no
    popover open/close state.

    `displayedLanguage()` reads the active language from `TediTranslationService`
    (a runtime service), which has no Blade equivalent — per CONVENTIONS.md §5
    it becomes the explicit prop `currentLanguage`.

    `languageChange` (output) is not re-emitted (CONVENTIONS.md §2); bind
    `wire:click` / `x-on:click` on each option yourself if needed.
--}}
@props([
    /** [lang => label] map. Required. */
    'languages',
    'selectLabel' => null,
    /** top|left */
    'labelPosition' => 'top',
    /** Optional [lang => url] map; options with a URL render as <a href>. */
    'languageHrefs' => null,
    /** Key into $languages for the currently active language (drives the trigger label). */
    'currentLanguage' => null,
])

@php
    $resolvedSelectLabel = $selectLabel ?: __('tedi::tedi.header.select-lang');
    $displayedLanguage = $languages[$currentLanguage] ?? reset($languages);
    $triggerAriaLabel = trim($resolvedSelectLabel.' '.$displayedLanguage);
@endphp

<div {{ $attributes->class([
    'tedi-header-language',
    'tedi-header-language--label-left' => $labelPosition === 'left',
]) }}>
    <tedi:text as="span" color="secondary" modifiers="small" class="tedi-header-language__label">
        {{ $resolvedSelectLabel }}
    </tedi:text>

    <button type="button" class="tedi-link tedi-header__link-button" aria-label="{{ $triggerAriaLabel }}">
        <span>{{ $displayedLanguage }}</span>
        <tedi:icon name="expand_more" :size="18" color="inherit" class="tedi-header-language__chevron" />
    </button>

    @foreach ($languages as $lang => $displayLabel)
        @if ($languageHrefs[$lang] ?? null)
            <a class="tedi-link tedi-header__link-button" href="{{ $languageHrefs[$lang] }}">
                <span>{{ $displayLabel }}</span>
            </a>
        @else
            <button type="button" class="tedi-link tedi-header__link-button">
                <span>{{ $displayLabel }}</span>
            </button>
        @endif
    @endforeach
</div>
