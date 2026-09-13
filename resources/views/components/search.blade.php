{{--
    TEDI Search.
    Port of angular/tedi/components/form/search/{search.component.ts,search.component.html}

    Angular's selector is the element `tedi-search`, but the vendored SCSS has
    no `tedi-search` ELEMENT rule — every rule keys on the `.tedi-search` class
    and its descendants — so per the batch ruling the root is a `<div>` carrying
    the host class, the way radio-card-group.blade.php does.

    Composition mirrors the Angular template: <tedi:form-field> wrapping a
    <tedi:text-field>, plus an optional trailing <tedi:button>. Because the
    component wraps a single native control, `{{ $attributes }}` goes on the
    `<input>` so `wire:model` binds directly (CONVENTIONS.md §6, the
    select.blade.php precedent); the root `<div>` therefore carries its
    computed `role` / `aria-label` / `style` raw, which is safe precisely
    because no attribute bag is merged onto it.

    Divergences:

    * EXTRA WRAPPER: `tedi-search__field` sits on a wrapper `<div>` around
      <tedi:form-field>, not on the form-field itself as in Angular. Blade's
      form-field.blade.php emits its `label` slot as a SIBLING before the
      `.tedi-form-field` div, so without the wrapper the label would become its
      own flex item under `.tedi-search { display: flex }`. Only one rule uses
      the class (`&__field { flex: 1 1 auto; min-width: 0 }`) and it is
      element-agnostic; every other search rule is a descendant chain, so the
      extra level changes nothing.
    * `searchEvent` and `clear` are output()s and are not re-emitted
      (CONVENTIONS.md §7 item 2). Bind your own listeners: `$attributes` reach
      the `<input>` (e.g. `wire:keydown.enter="search"`), `buttonAttributes`
      reach the trailing button and `clearAttributes` reach the form-field's
      clear button.
    * `invalid` / `valid` on the form-field are derived from
      `feedbackText['type']` ('error' → invalid, 'valid' → valid). Angular
      derives them at runtime from the projected `tedi-feedback-text`; there is
      no runtime here, so the derivation happens up front (CONVENTIONS.md §5).
    * `disabled` is the local prop only — Angular also folds in the
      reactive-forms disabled state, which has no server-side equivalent.
    * When `button.text` is set, the button's icon is rendered by
      <tedi:button>'s own `icon-start` and therefore uses that component's size
      mapping (18 small / 24 default and large) rather than Angular's
      `buttonIconSize()` (18 small and default / 24 large) — they differ only at
      `size="default"`. `icon-start` is used because it is the only way to get
      <tedi:button>'s exact class list (`--pr`, no `--pl`) for an
      icon-then-text button, and class parity is the tested invariant
      (CONVENTIONS.md §4). The icon-only branch passes `:icon-only="true"` and
      renders the icon in the slot, so there both the classes and
      `buttonIconSize()` are exact.
    * `value` is passed down to <tedi:form-field> so its clear button knows
      whether the field is non-empty; Angular reads that off the projected
      control at runtime instead.
--}}
@props([
    /** Id for the <input>, also the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Visible label text. When omitted, provide `ariaLabel`. */
    'label' => null,
    /** Value of the search input. Emitted only when non-empty (see text-field.blade.php). */
    'value' => '',
    /** Placeholder text for the search input. */
    'placeholder' => '',
    /** default|small|large */
    'size' => 'default',
    /** Shows a clear button once the field has a value. */
    'clearable' => true,
    /** Icon shown inside the input; ignored when `button` is set. Name, or a form-field icon array. */
    'searchIcon' => 'search',
    'disabled' => false,
    /** Trailing search button: ['text' => ..., 'icon' => 'search', 'variant' => 'primary', 'ariaLabel' => ...]. */
    'button' => null,
    /** Feedback text below the field: ['text' => ..., 'type' => 'hint', 'position' => 'left']. */
    'feedbackText' => null,
    /** Accessible name for the search region. Falls back to label, placeholder, then the translated "search". */
    'ariaLabel' => null,
    /** Extra attributes forwarded to the trailing search button (e.g. wire:click). */
    'buttonAttributes' => [],
    /** Extra attributes forwarded to the form-field's clear button (e.g. wire:click). */
    'clearAttributes' => [],
])

@php
    // Unconditional: the <input> id and the label's `for` both read it on every
    // render path, so it must never depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-search');

    // An empty array is a legitimate `button` config (icon-only, all defaults),
    // and PHP treats it as falsy — so presence is tested against null, not truthiness.
    $hasButton = $button !== null;

    // Angular: fieldIcon() — the inline icon is suppressed once a button is shown.
    $fieldIcon = $hasButton ? null : $searchIcon;

    // Angular: fieldHeight().
    $fieldHeight = match ($size) {
        'small' => 'var(--form-field-height-sm)',
        'large' => 'var(--form-field-height-lg)',
        default => 'var(--form-field-height)',
    };

    // Angular: buttonSize() / buttonIconSize().
    $buttonSize = $size === 'small' ? 'small' : 'default';
    $buttonIconSize = $size === 'large' ? 24 : 18;

    $buttonText = $button['text'] ?? null;
    $buttonIcon = $button['icon'] ?? 'search';
    $buttonVariant = $button['variant'] ?? 'primary';

    // Angular: buttonAriaLabel() — a labelled button needs no aria-label.
    $buttonAriaLabel = $buttonText ? null : ($button['ariaLabel'] ?? __('tedi::tedi.search'));

    // Angular: searchAriaLabel() / inputAriaLabel(). A visible label already
    // names the input via for/id, so aria-label would silently override it.
    $searchAriaLabel = $ariaLabel ?: ($label ?: ($placeholder ?: __('tedi::tedi.search')));
    $inputAriaLabel = $label ? null : $searchAriaLabel;

    // Angular: feedbackId().
    $feedbackId = $feedbackText ? $inputId.'-feedback' : null;

    $feedbackType = $feedbackText['type'] ?? 'hint';
@endphp

<div
    role="search"
    aria-label="{{ $searchAriaLabel }}"
    style="--tedi-search-field-height: {{ $fieldHeight }}"
    @class([
        'tedi-search',
        'tedi-search--has-button' => $hasButton,
        'tedi-search--button-icon-only' => $hasButton && ! $buttonText,
    ])
>
    <div class="tedi-search__field">
        <tedi:form-field
            :size="$size"
            :icon="$fieldIcon"
            :clearable="$clearable && ! $disabled"
            :value="$value"
            :disabled="$disabled"
            :invalid="$feedbackType === 'error'"
            :valid="$feedbackType === 'valid'"
            :clear-attributes="$clearAttributes"
        >
            @if ($label)
                <x-slot:label>
                    <tedi:form.label :for="$inputId" :size="$size === 'small' ? 'small' : 'default'">
                        {{ $label }}
                    </tedi:form.label>
                </x-slot:label>
            @endif

            <tedi:text-field
                type="text"
                :id="$inputId"
                :value="$value"
                :placeholder="$placeholder"
                :disabled="$disabled"
                {{ $attributes->merge(array_filter([
                    'role' => 'searchbox',
                    'aria-label' => $inputAriaLabel,
                    'aria-describedby' => $feedbackId,
                ], fn ($v) => $v !== null)) }}
            />

            @if ($feedbackText)
                <x-slot:feedback>
                    <tedi:feedback-text
                        :id="$feedbackId"
                        :text="$feedbackText['text']"
                        :type="$feedbackType"
                        :position="$feedbackText['position'] ?? 'left'"
                    />
                </x-slot:feedback>
            @endif
        </tedi:form-field>
    </div>

    @if ($hasButton)
        <tedi:button
            class="tedi-search__button"
            type="button"
            :variant="$buttonVariant"
            :size="$buttonSize"
            :disabled="$disabled"
            :aria-label="$buttonAriaLabel"
            :icon-start="$buttonText ? $buttonIcon : null"
            :icon-only="! $buttonText"
            {{ $attributes->only([])->merge($buttonAttributes) }}
        >
            @if ($buttonText)
                {{ $buttonText }}
            @else
                <tedi:icon :name="$buttonIcon" :size="$buttonIconSize" color="inherit" />
            @endif
        </tedi:button>
    @endif
</div>
