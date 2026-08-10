{{--
    TEDI Header Language.
    Port of angular/tedi/components/layout/header/header-language/header-language.component.{ts,html}

    Angular wraps the trigger and the option list in `<tedi-popover>`. That
    component is now ported (`tedi:popover`, positioned by `tediOverlay` — see
    CONVENTIONS.md §11), so this port uses it too: the language list lives in a
    `<tedi:popover-content max-width="none">` and only appears when the trigger
    is clicked, exactly as upstream. (An earlier revision rendered the whole
    list as always-visible static markup because overlays were out of scope;
    that is no longer the case.)

    `displayedLanguage()` reads the active language from `TediTranslationService`
    (a runtime service), which has no Blade equivalent — per CONVENTIONS.md §5
    it becomes the explicit prop `currentLanguage`.

    `languageChange` (output) is not re-emitted (CONVENTIONS.md §2); bind
    `wire:click` / `x-on:click` on each option yourself if needed. Angular's
    `handleChangeLang()` also closes the popover — an option rendered as an
    `<a href>` navigates away anyway, and a `<button>` option here calls
    `hide(true)` so the panel closes and focus returns to the trigger, matching
    `popover.hidePopover()`.
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
    $popoverId = \Tedi\Livewire\Tedi::id('tedi-header-language');
@endphp

<div {{ $attributes->class([
    'tedi-header-language',
    'tedi-header-language--label-left' => $labelPosition === 'left',
]) }}>
    <tedi:text as="span" color="secondary" modifiers="small" class="tedi-header-language__label">
        {{ $resolvedSelectLabel }}
    </tedi:text>

    <tedi:popover :with-border="true" position="bottom" :prevent-overflow="true" :container-id="$popoverId">
        <x-slot:trigger>
            <tedi:popover-trigger
                tag="button"
                class="tedi-link tedi-header__link-button"
                :aria-label="$triggerAriaLabel"
            >
                <span>{{ $displayedLanguage }}</span>
                <tedi:icon name="expand_more" :size="18" color="inherit" class="tedi-header-language__chevron" />
            </tedi:popover-trigger>
        </x-slot:trigger>

        <tedi:popover-content max-width="none">
            @foreach ($languages as $lang => $displayLabel)
                @if ($languageHrefs[$lang] ?? null)
                    <a class="tedi-link tedi-header__link-button" href="{{ $languageHrefs[$lang] }}">
                        <span>{{ $displayLabel }}</span>
                    </a>
                @else
                    <button type="button" class="tedi-link tedi-header__link-button" x-on:click="hide(true)">
                        <span>{{ $displayLabel }}</span>
                    </button>
                @endif
            @endforeach
        </tedi:popover-content>
    </tedi:popover>
</div>
