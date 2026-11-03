{{--
    TEDI Date input.
    Port of angular/src/tedi/components/form/date-field/date-input/{date-input.component.ts,html}

    Angular's selector `tedi-date-input` is an element selector, but the vendored
    SCSS never targets that tag — it styles `.tedi-date-input` only (grepped across
    the whole resources/scss tree; `tedi-date-picker` is the single element-selector
    hit in this family). Per the batch ruling that maps to a <div> carrying the host
    classes, exactly like radio-card-group.blade.php.

    `{{ $attributes }}` goes on the inner <input>, not the root: this component
    exists so `wire:model` binds to a real form control (CONVENTIONS.md §6's
    native-control carve-out; precedent select.blade.php). The wrapper divs carry
    static classes.

    The <input> itself is <tedi:text-field>, not a hand-rolled element. Angular's
    text-field is the attribute directive `input[tedi-text-field]` while the
    vendored SCSS styles the class `.tedi-text-field`; text-field.blade.php already
    emits both, so composing it is the only way to get both selector forms right.
    Its `arrowsHidden` default (`true`) also matches Angular's directive default, so
    `tedi-text-field--arrows-hidden` is correct parity, not a stray class.

    Classes dropped because dist/tedi.css ships no rule for them (CONVENTIONS.md §4
    "classes Angular emits but TEDI never styles"; restore on a re-sync if TEDI ever
    adds the rules):
      * `tedi-date-input--disabled` (Angular emits it when `disabled` is set)
      * `tedi-date-input--readonly` (Angular emits it when `readOnly` is set)
    Both states are still rendered — as the native `disabled` / `readonly`
    attributes on the <input>, and (for `disabled`) by suppressing the clear
    button and the tag close buttons — only the host modifier class is omitted.
    Since Angular 9.0 (#726) `readOnly` only locks the text input: it no longer
    hides the clear button or the tag close buttons. The parent date-field
    decides whether the value can still change and passes `clearable` /
    `removable` accordingly.

    `name` (Angular 8.1, #686) is emitted on the <input> only when non-empty.
    `ariaDescribedby` (#675) is forwarded to the <input> as `aria-describedby`;
    Angular hands it to the `tedi-text-field` directive, which owns that
    attribute. A consumer-written `aria-describedby` on this component already
    reaches the <input> through `$attributes` and wins over the prop.

    Further divergences:
    * `useNativePicker` and `nativeIsoValue` are DROPPED. `useNativePicker` is a
      breakpoint prop on the parent date-field (CONVENTIONS.md §7 item 1) and the
      entire `type="date"` branch depends on it, so the input is always
      `type="text"` and `inputValue()` reduces to `value`.
    * `visibleTagCount` is an explicit prop standing in for Angular's
      `visibleTagsCount` signal, which is a live width measurement of the rendered
      tags (CONVENTIONS.md §5). `null` means "not measured" — every tag renders and
      `tedi-date-input--tags-measuring` is emitted, matching Angular's first paint.
    * The `inputChange`, `iconClick`, `tagRemove` and `clear` outputs are not
      re-emitted (CONVENTIONS.md §7 item 2). Bind `wire:click` / `x-on:click`
      yourself: `$attributes` reaches the <input>, and <tedi:tag>'s own
      `close-attributes` prop reaches each tag's close button.
    * `size` is port-only. Angular's date field provides a field context to
      everything inside it whose `size` is the field's resolved size, and the
      inner `tedi-text-field` reads it (`tedi-text-field--small|large`). The
      date field hands its resolved size down through this prop, and it is
      passed to the inner text-field as that field's own `size`.
    * `iconAttributes` is a port-only escape hatch onto the calendar icon
      button (Angular binds `(click)` there itself). `tedi:date-time-field`
      uses it for the `aria-haspopup` / `aria-controls` its React source puts
      on that button.
    * `value` is forwarded to <tedi:text-field>, which emits the `value` attribute
      only when non-empty — an unconditional `value=""` would blank a
      `wire:model`-bound field on the initial server render.
--}}
@props([
    /** Id for the <input>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** `name` attribute of the <input>, for native form submission. Omitted when empty. */
    'name' => null,
    /** Space-separated ids for the <input>'s `aria-describedby`. Omitted when empty. */
    'ariaDescribedby' => null,
    /** Display text shown in the input. Emitted as `value` only when non-empty. */
    'value' => '',
    /** Tags for `multiple` mode: [['id' => …, 'label' => …], …]. */
    'tags' => [],
    /** single|multiple|range — only `multiple` renders tags. */
    'mode' => 'single',
    /** Multiple mode: true wraps tags across rows, false keeps one row with a +N counter. */
    'multiRow' => true,
    /** false|'start'|'end' — which end a tag label truncates from. */
    'ellipsis' => false,
    /** Whether tags show a remove (close) button. Independent of `readOnly`. */
    'removable' => true,
    /** Placeholder shown when the input is empty. */
    'placeholder' => '',
    'disabled' => false,
    /** Renders `aria-invalid="true"` on the <input>. Angular's text-field reads it from the date field's context. */
    'invalid' => false,
    'readOnly' => false,
    'required' => false,
    /** Renders the calendar icon in its active (open) state. */
    'iconActive' => false,
    /** Disables the calendar icon button without disabling the field. */
    'iconDisabled' => false,
    /** Show the clear button when the field has a value. Independent of `readOnly`. */
    'clearable' => false,
    /** How many tags fit on one row; null = unmeasured (renders them all). */
    'visibleTagCount' => null,
    /** small|default|large — the date field's resolved size. Port-only: it is the `size` of Angular's date-field `childContext`, which the inner text-field reads (`tedi-text-field--small|large`). */
    'size' => null,
    /** Extra attributes for the calendar icon button (e.g. wire:click, aria-controls). Port-only, like form-field's clearAttributes. */
    'iconAttributes' => [],
])

@php
    // Unconditional: the id is referenced on every render path (CONVENTIONS.md §5,
    // precedent select.blade.php).
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-date-input');

    $tags = is_array($tags) ? array_values($tags) : [];
    $hasTags = $mode === 'multiple' && count($tags) > 0;

    // visibleTags() / hiddenTagsCount()
    $visibleTags = $tags;
    $hiddenTagsCount = 0;
    if (! $multiRow && $visibleTagCount !== null) {
        $visibleTagCount = max(0, (int) $visibleTagCount);
        $visibleTags = array_slice($tags, 0, $visibleTagCount);
        $hiddenTagsCount = max(0, count($tags) - $visibleTagCount);
    }

    $stringValue = (string) $value;
    // showClearButton() / the tag `[closable]` binding: `readOnly` no longer
    // takes part (Angular 9.0, #726).
    $showClear = $clearable && ! $disabled && ($stringValue !== '' || $hasTags);
    $tagsClosable = $removable && ! $disabled;
@endphp

<div @class([
    'tedi-date-input',
    'tedi-date-input--with-tags' => $hasTags,
    'tedi-date-input--tags-wrap' => $hasTags && $multiRow,
    'tedi-date-input--tags-single-row' => $hasTags && ! $multiRow,
    'tedi-date-input--tags-measuring' => $hasTags && ! $multiRow && $visibleTagCount === null,
])>
    <div class="tedi-date-input__field">
        @if ($hasTags)
            <div class="tedi-date-input__tags">
                @foreach ($visibleTags as $tag)
                    <tedi:tag :ellipsis="$ellipsis" :closable="(bool) $tagsClosable">{{ $tag['label'] ?? '' }}</tedi:tag>
                @endforeach
            </div>

            @if ($hiddenTagsCount > 0)
                <tedi:tag class="tedi-date-input__tags-counter">+{{ $hiddenTagsCount }}</tedi:tag>
            @endif
        @endif

        <tedi:text-field
            type="text"
            :id="$inputId"
            :value="$stringValue"
            :placeholder="$placeholder !== '' ? $placeholder : null"
            :disabled="(bool) $disabled"
            :invalid="(bool) $invalid"
            :size="$size"
            :readonly="(bool) $readOnly"
            :required="(bool) $required"
            {{ $attributes->class(['tedi-date-input__input'])->merge(array_filter([
                'name' => ($name !== null && $name !== '') ? $name : null,
                'aria-describedby' => ($ariaDescribedby !== null && trim((string) $ariaDescribedby) !== '') ? trim((string) $ariaDescribedby) : null,
            ])) }}
        />
    </div>

    <div class="tedi-date-input__actions">
        @if ($showClear)
            <tedi:closing-button
                size="small"
                class="tedi-date-input__clear"
                :icon-size="18"
                :aria-label="__('tedi::tedi.date-picker.clear-date')"
            />
            <tedi:separator axis="vertical" size="1rem" />
        @endif

        {{-- `aria-expanded` is a raw boolean binding in Angular, so it is always
             present as "true"/"false" — never omitted. --}}
        <button {{ (new \Illuminate\View\ComponentAttributeBag((array) $iconAttributes))->class([
                'tedi-date-input__icon',
                'tedi-date-input__icon--active' => (bool) $iconActive,
            ])->merge([
                'type' => 'button',
                'aria-label' => __('tedi::tedi.date-picker.open-calendar'),
                'aria-expanded' => $iconActive ? 'true' : 'false',
            ]) }}
            @disabled($disabled || $iconDisabled)
        >
            <tedi:icon name="calendar_today" :size="18" color="inherit" />
        </button>
    </div>
</div>
