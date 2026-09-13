{{--
    TEDI Textarea.
    Port of angular/tedi/components/form/textarea/textarea.component.ts

    Angular's selector is the attribute-based `textarea[tedi-textarea]`, so the
    root is a native `<textarea>` carrying the literal `tedi-textarea` attribute
    alongside the host classes (CONVENTIONS.md §4, "element selectors in the
    vendored SCSS"). `{{ $attributes }}` lands on the control itself so
    `wire:model` binds directly (CONVENTIONS.md §6).

    The three computed host styles are ported verbatim from `heightStyle()`,
    `minHeightStyle()` and `maxHeightStyle()`, including the
    `calc(N * 1lh + 2 * var(--_field-padding-y))` row→height formula
    and the `min(a, b)` combination when both `maxRows` and `maxHeight` apply.
    They are emitted through `$attributes->style([...])` so a consumer-supplied
    `style` merges instead of being dropped.

    Divergences:

    * BLADE EXTENSION: the textarea's content comes from the `value` prop when
      it is non-empty, and from `{{ $slot }}` otherwise. Angular has no
      `<ng-content>` here at all (its `template` is `""`); the slot is added
      deliberately because `<textarea>Kirjuta siia</textarea>` is the natural
      HTML way to supply initial content and Blade components always have a
      slot. `value` wins when both are given.
    * `value` is never emitted as an attribute (a `<textarea>` has none); when
      empty, nothing is written, so a `wire:model`-bound field is not blanked
      out on the initial server render.
    * `:height="null"` cannot switch off the `"7.5rem"` default — Blade resolves
      @props defaults with isset() (CONVENTIONS.md §3). Use `height=""` to fall
      back to the native `rows` attribute for the resting height.
    * The `output()`-free ControlValueAccessor plumbing (writeValue,
      registerOnChange, setDisabledState, the `effect()` that syncs the DOM
      value) is not ported — CONVENTIONS.md §7 item 2.
    * `invalid` is an Angular signal driven by the parent form-field's NgControl
      subscription (`setInvalidState`); server-side it is an explicit prop
      (CONVENTIONS.md §5).
    * `ownsSurface` / `valid` / `size` come from a wrapping `<tedi:form-field>`
      via @aware. When the wrapper has no box, this control paints
      `tedi-field-surface` itself (Angular 8).
--}}
@props([
    /** Initial content. Falls back to the slot when empty. */
    'value' => '',
    /** Allows vertical drag-resizing; false emits tedi-textarea--not-resizable. */
    'resizable' => true,
    /** Content-driven growth via CSS `field-sizing`; bounded by minRows/maxRows. */
    'autoGrow' => false,
    /** Minimum visible rows while autoGrow is on. */
    'minRows' => 3,
    /** Maximum visible rows before scrolling, while autoGrow is on. */
    'maxRows' => 12,
    /** Fixed height ('7.5rem', 200 → '200px'). Ignored when autoGrow is on; `height=""` disables it. */
    'height' => '7.5rem',
    /** Cap on the grown/resized height ('200px', 12 → '12px'). */
    'maxHeight' => null,
    /** Renders `aria-invalid="true"`; the visual error state lives on the wrapping form-field. */
    'invalid' => false,
    'disabled' => false,
    'valid' => false,
    /** default|small|large — falls back to a wrapping form-field's size. */
    'size' => 'default',
    'ownsSurface' => false,
])

@aware([
    'ownsSurface' => false,
    'valid' => false,
    'size' => 'default',
    'invalid' => false,
    'disabled' => false,
])

@php
    // Angular: toCssSize() — a bare number means pixels.
    $toCssSize = fn ($v) => is_numeric($v) ? $v.'px' : (string) $v;
    // Angular: rowsToHeight().
    $rowsToHeight = fn ($rows) => 'calc('.$rows.' * 1lh + 2 * var(--_field-padding-y))';

    $hasHeight = $height !== null && $height !== '';
    $hasMaxHeight = $maxHeight !== null && $maxHeight !== '';

    $heightStyle = ($autoGrow || ! $hasHeight) ? null : $toCssSize($height);
    $minHeightStyle = $autoGrow ? $rowsToHeight($minRows) : null;

    $limits = [];
    if ($autoGrow) {
        $limits[] = $rowsToHeight($maxRows);
    }
    if ($hasMaxHeight) {
        $limits[] = $toCssSize($maxHeight);
    }
    $maxHeightStyle = match (count($limits)) {
        0 => null,
        1 => $limits[0],
        default => 'min('.implode(', ', $limits).')',
    };

    $styles = array_filter([
        $heightStyle !== null ? 'height: '.$heightStyle : null,
        $minHeightStyle !== null ? 'min-height: '.$minHeightStyle : null,
        $maxHeightStyle !== null ? 'max-height: '.$maxHeightStyle : null,
    ], fn ($v) => $v !== null);

    $bag = $attributes->class([
        'tedi-textarea',
        'tedi-textarea--not-resizable' => ! $resizable,
        'tedi-textarea--auto-grow' => (bool) $autoGrow,
        'tedi-textarea--small' => $size === 'small',
        'tedi-field-surface' => ! $ownsSurface,
        'tedi-field-surface--valid' => ! $ownsSurface && $valid,
    ])->merge(array_filter([
        'aria-invalid' => $invalid ? 'true' : null,
    ], fn ($v) => $v !== null));

    // ->style([]) would still write style="" — every style here is conditional,
    // so the call only happens when at least one of them applies.
    if ($styles !== []) {
        $bag = $bag->style($styles);
    }
@endphp

<textarea
    tedi-textarea
    @disabled($disabled)
    {{ $bag }}
>{{ (string) $value !== '' ? $value : $slot }}</textarea>
