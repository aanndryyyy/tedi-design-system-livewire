{{--
    TEDI Date input.
    Port of angular/tedi/components/form/date-field/date-input/{date-input.component.ts,html}

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
    attributes on the <input>, and by suppressing the clear button and the tag
    close buttons — only the host modifier class is omitted.

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
    * `value` is forwarded to <tedi:text-field>, which emits the `value` attribute
      only when non-empty — an unconditional `value=""` would blank a
      `wire:model`-bound field on the initial server render.
--}}
@props([
    /** Id for the <input>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
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
    /** Whether tags show a remove (close) button. */
    'removable' => true,
    /** Placeholder shown when the input is empty. */
    'placeholder' => '',
    'disabled' => false,
    'readOnly' => false,
    'required' => false,
    /** Renders the calendar icon in its active (open) state. */
    'iconActive' => false,
    /** Disables the calendar icon button without disabling the field. */
    'iconDisabled' => false,
    /** Show the clear button when the field has a value. */
    'clearable' => false,
    /** How many tags fit on one row; null = unmeasured (renders them all). */
    'visibleTagCount' => null,
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
    $showClear = $clearable && ! $disabled && ! $readOnly && ($stringValue !== '' || $hasTags);
    $tagsClosable = $removable && ! $disabled && ! $readOnly;
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
            :readonly="(bool) $readOnly"
            :required="(bool) $required"
            {{ $attributes->class(['tedi-date-input__input']) }}
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
        <button
            type="button"
            @class([
                'tedi-date-input__icon',
                'tedi-date-input__icon--active' => (bool) $iconActive,
            ])
            @disabled($disabled || $iconDisabled)
            aria-label="{{ __('tedi::tedi.date-picker.open-calendar') }}"
            aria-expanded="{{ $iconActive ? 'true' : 'false' }}"
        >
            <tedi:icon name="calendar_today" :size="18" color="inherit" />
        </button>
    </div>
</div>
