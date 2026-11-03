{{--
    TEDI Textarea.
    Port of angular/src/tedi/components/form/textarea/textarea.component.ts

    Angular's selector is the attribute-based `textarea[tedi-textarea]`, so the
    root is a native `<textarea>` carrying the literal `tedi-textarea` attribute
    alongside the host classes (CONVENTIONS.md §4, "element selectors in the
    vendored SCSS"). `{{ $attributes }}` lands on the control itself so
    `wire:model` binds directly (CONVENTIONS.md §6).

    The three computed host styles are ported verbatim from `heightStyle()`,
    `minHeightStyle()` and `maxHeightStyle()`, including the
    `calc(N * 1lh + 2 * var(--_field-padding-y))` row→height formula, the
    `minRows` floor of 1, and the `min(a, b)` combination when both `maxRows`
    and `maxHeight` apply. `min-height` is always emitted — `minRows` is the
    resting height when no `height` is set. They go through
    `$attributes->style([...])` so a consumer-supplied `style` merges instead of
    being dropped.

    Like text-field, the textarea paints its own surface (`tedi-field-surface`)
    unless a wrapping `tedi:form-field` renders a box, and it registers itself
    as that form-field's control — since Angular 9.0 (#751) a textarea can be
    reset, so it gets the form-field's clear button once it holds a value. The
    field context (`Tedi::fieldContext()`) supplies `size`, `invalid`,
    `disabled` and `valid` as described in text-field.blade.php.

    Divergences:

    * BLADE EXTENSION: the textarea's content comes from the `value` prop when
      it is non-empty, and from `{{ $slot }}` otherwise. Angular has no
      `<ng-content>` here at all (its `template` is `""`); the slot is added
      deliberately because a `<textarea>` with text content is the natural
      HTML way to supply initial content and Blade components always have a
      slot. `value` wins when both are given, and only `value` reaches a
      wrapping form-field's clear button and counter.
    * `value` is never emitted as an attribute (a `<textarea>` has none); when
      empty, nothing is written, so a `wire:model`-bound field is not blanked
      out on the initial server render.
    * The ControlValueAccessor plumbing (writeValue, registerOnChange,
      setDisabledState, the `effect()` that syncs the DOM value, `reset()`,
      `focus()`) and reactive-forms state are not ported — CONVENTIONS.md §7
      item 2. Pass `invalid` / `disabled` explicitly.
    * `scrollbarWidth` (a ResizeObserver measuring the classic scrollbar for the
      form-field) is not ported; see form-field.blade.php.
    * `readonly` is one prop; Angular accepts both `readonly` and `[readOnly]`.
--}}
@props([
    /** Initial content. Falls back to the slot when empty. */
    'value' => '',
    /** small|default. Falls back to the wrapping form-field's size. */
    'size' => null,
    /** Allows vertical drag-resizing; false emits tedi-textarea--not-resizable. */
    'resizable' => true,
    /** Content-driven growth via CSS `field-sizing`; bounded by minRows/maxRows. */
    'autoGrow' => false,
    /** Rows the field rests at, and the fewest it can show. Values below 1 are treated as 1. */
    'minRows' => 3,
    /** Most rows before the field scrolls. Caps autoGrow and the resize grip. Unset: no limit. */
    'maxRows' => null,
    /** Exact resting height ('7.5rem', 200 → '200px'). Prefer minRows. Ignored while autoGrow is on. */
    'height' => null,
    /** Cap on the grown/resized height ('200px', 12 → '12px'). Combined with maxRows, whichever is smaller. */
    'maxHeight' => null,
    /** Forces the error state (`aria-invalid="true"`). */
    'invalid' => false,
    'disabled' => false,
    /** Native `readonly`. A wrapping form-field hides its clear button while it is set. */
    'readonly' => false,
])

@php
    $context = \Tedi\Livewire\Tedi::fieldContext($__env, [
        'reset' => true,
        'value' => $value,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readonly,
        'invalid' => (bool) $invalid,
    ]);

    $isDisabled = (bool) $disabled || ($context['disabled'] ?? false);
    $isInvalid = (bool) $invalid || ($context['invalid'] ?? false);
    $resolvedSize = $size ?? ($context['size'] ?? 'default');
    $paintsSurface = ! ($context['ownsSurface'] ?? false);
    $isValid = $context['valid'] ?? false;

    // Angular: toCssSize() — a bare number means pixels.
    $toCssSize = fn ($v) => is_numeric($v) ? $v.'px' : (string) $v;
    // Angular: rowsToHeight().
    $rowsToHeight = fn ($rows) => 'calc('.$rows.' * 1lh + 2 * var(--_field-padding-y))';

    $hasHeight = $height !== null && $height !== '';

    $heightStyle = ($autoGrow || ! $hasHeight) ? null : $toCssSize($height);
    $minHeightStyle = $rowsToHeight(max(1, (int) $minRows));

    $limits = [];
    if ($maxRows !== null && $maxRows !== '') {
        $limits[] = $rowsToHeight($maxRows);
    }
    if ($maxHeight !== null && $maxHeight !== '') {
        $limits[] = $toCssSize($maxHeight);
    }
    $maxHeightStyle = match (count($limits)) {
        0 => null,
        1 => $limits[0],
        default => 'min('.implode(', ', $limits).')',
    };

    $describedBy = array_values(array_unique(array_merge(
        preg_split('/\s+/', trim((string) $attributes->get('aria-describedby', '')), -1, PREG_SPLIT_NO_EMPTY),
        $context['describedBy'] ?? [],
    )));
@endphp

<textarea
    tedi-textarea
    @disabled($isDisabled)
    @readonly($readonly)
    {{ $attributes->except('aria-describedby')->class([
        'tedi-textarea',
        'tedi-field-surface' => $paintsSurface,
        'tedi-field-surface--valid' => $paintsSurface && $isValid,
        'tedi-textarea--small' => $resolvedSize === 'small',
        'tedi-textarea--not-resizable' => ! $resizable,
        'tedi-textarea--auto-grow' => (bool) $autoGrow,
    ])->merge(array_filter([
        'aria-invalid' => $isInvalid ? 'true' : null,
        'aria-describedby' => $describedBy !== [] ? implode(' ', $describedBy) : null,
    ], fn ($v) => $v !== null))->style(array_filter([
        $heightStyle !== null ? 'height: '.$heightStyle : null,
        'min-height: '.$minHeightStyle,
        $maxHeightStyle !== null ? 'max-height: '.$maxHeightStyle : null,
    ])) }}
>{{ (string) $value !== '' ? $value : $slot }}</textarea>
