{{--
    TEDI Resizer.
    Port of `Resizer` in
    react/src/community/components/map-components/resizer/resizer.tsx
    (CONVENTIONS.md §13; React's community tree).

    A box whose width (handle right/left) or height (handle top/bottom) the
    user drags between pixel limits. The content fills the box.

    MARKUP is upstream's, attribute for attribute:
      - the wrapper `tedi-resize__wrapper` with upstream's inline style. The
        width is always set, also for top/bottom. Height is `auto` for
        right/left. Then `position: relative; display: block`.
      - `tedi-resize__content` around the slot.
      - the handle `tedi-resize__handle-wrapper direction-<position>` with
        `data-show-indicator` "true"/"false". `direction-*` is unprefixed, as
        upstream's module class is.
      - `tedi-resize__drag-handle`, and, only while `show-indicator` is false,
        `tedi-resize__drag-indicator`. That inversion is upstream's: the
        indicator tab is the at-rest hint, and `show-indicator` instead keeps
        the accent bar and the round handle visible.

    ICONS ARE SPANS, not `tedi:icon`. The vendored module styles the icon as
    `… > span` (it rotates `drag_handle` on the right/left handles and on the
    indicator, and colours it). React's `Icon` renders a `<span>`. This
    package's `tedi:icon` renders a `<tedi-icon>` element, which those rules do
    not match. So the icon is a `<span>` with the exact class list and style
    `tedi:icon` emits for the same props (`size` 18, `color` primary/brand).

    BEHAVIOUR (`tediResizer`). Mouse only, as upstream: mousedown on the handle,
    mousemove and mouseup on the document, `user-select: none` on `body` while
    dragging. The new size is the start size plus (or, for left/top, minus) the
    pointer delta, clamped to the limits. Upstream has no keyboard, touch or
    ARIA support, and none is added. The dragged size is client state. It
    survives a Livewire morph of an ancestor, and the server's `initial-*` is
    only the starting size.

    No events: upstream has no callbacks.
--}}
@props([
    /** Minimum width in pixels. */
    'minWidth' => 300,
    /** Maximum width in pixels. */
    'maxWidth' => 600,
    /** Starting width in pixels. */
    'initialWidth' => 350,
    /** Minimum height in pixels. */
    'minHeight' => 200,
    /** Maximum height in pixels. */
    'maxHeight' => 600,
    /** Starting height in pixels; applied for top/bottom handles only. */
    'initialHeight' => 350,
    /** right|left|top|bottom — the edge the handle sits on. */
    'handlePosition' => 'right',
    /** Keep the accent bar and round handle visible instead of the at-rest indicator tab. */
    'showIndicator' => false,
])

@php
    // Numbers, so a string attribute (initial-width="400") reaches the JS as one.
    foreach (['minWidth', 'maxWidth', 'initialWidth', 'minHeight', 'maxHeight', 'initialHeight'] as $number) {
        $$number = $$number + 0;
    }
    $isVertical = $handlePosition === 'top' || $handlePosition === 'bottom';
    $icon = fn (string $color) => new \Illuminate\Support\HtmlString(
        '<span role="img" aria-hidden="true" style="--_tedi-icon-size: var(--icon-03)"'
        .' class="notranslate material-symbols material-symbols--outlined tedi-icon tedi-icon--color-'.$color.'">drag_handle</span>'
    );
    $config = [
        'handlePosition' => $handlePosition,
        'minWidth' => $minWidth,
        'maxWidth' => $maxWidth,
        'minHeight' => $minHeight,
        'maxHeight' => $maxHeight,
        'width' => $initialWidth,
        'height' => $initialHeight,
    ];
@endphp

<div {{ $attributes->class(['tedi-resize__wrapper'])->style([
        'width: '.$initialWidth.'px',
        'height: '.($isVertical ? $initialHeight.'px' : 'auto'),
        'position: relative',
        'display: block',
    ]) }} x-data="tediResizer(@js($config))">
    <div class="tedi-resize__content">{{ $slot }}</div>
    <div class="tedi-resize__handle-wrapper direction-{{ $handlePosition }}" data-show-indicator="{{ $showIndicator ? 'true' : 'false' }}" x-on:mousedown="start($event)">
        <div class="tedi-resize__drag-handle">{{ $icon('primary') }}</div>
        @if (! $showIndicator)
            <div class="tedi-resize__drag-indicator">{{ $icon('brand') }}</div>
        @endif
    </div>
</div>
