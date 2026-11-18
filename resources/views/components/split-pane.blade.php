{{--
    TEDI Split Pane.
    Port of `SplitPane` in
    react/src/community/components/map-components/split-pane/split-pane.tsx
    (CONVENTIONS.md §13; React's community tree).

    Two panes, `first` and `second` named slots, separated by a divider the user
    drags (mouse or touch) or moves with the keyboard. The divider reallocates
    the size ratio between the panes, clamped to `min-ratio` – `max-ratio`.

    MARKUP is upstream's, element for element. `data-testid` attributes are
    test hooks and are not ported.
      - root `tedi-split-pane tedi-split-pane--<direction>`.
      - each pane `tedi-split-pane__pane` with `flex-grow` set to the ratio (first)
        and 100 minus the ratio (second). A `highlighted-side` pane also gets
        `tedi-split-pane__ring` with the `--omit-<edge>` modifier for the edge
        that faces the divider.
      - `tedi-split-pane__divider`, holding the `role="separator"` element
        (`tedi-split-pane__separator`, focusable, `aria-orientation`,
        `aria-valuemin` / `-max` / `-now`, named by the `splitPaneResize` label)
        and, as its sibling, the close button in `tedi-split-pane__close`. The
        button sits outside the separator because a separator's children are
        presentational in ARIA (upstream #1156).
      - `tedi-split-pane__handle`, an `aria-hidden` span holding the `code`
        (horizontal) or `unfold_more` (vertical) icon.

    The handle icon is a `<span>` with `tedi:icon`'s class list, not a
    `tedi:icon`. The vendored module colours it as
    `.tedi-split-pane__handle > span`, React's `Icon` element. See
    `tedi:resizer` for the same choice.

    The close button is `tedi:button variant="secondary"` icon-only, named by
    `aria-label`. React's `Button` puts the name in a visually hidden text span
    instead.

    BEHAVIOUR (`tediSplitPane`). Dragging the separator follows the pointer
    across the whole container. Arrow keys move the ratio by 2 (Left/Right when
    horizontal, Up/Down when vertical), and Home/End jump to the limits. The
    ratio is client state. It survives a Livewire morph of an ancestor, and
    `initial-ratio` is only the starting value.

    EVENTS, dispatched from the root and bubbling (upstream's callbacks):
      `tedi:ratio-change`  detail `{ ratio }`. The final ratio (%) when a drag
                           ends, and after each key press. upstream's onRatioChange
      `tedi:close`         the close button, shown when `closable`. upstream's onClose
--}}
@props([
    /** Required. horizontal|vertical — side by side, or stacked. */
    'direction' => null,
    /** first|second — draws an accent ring around that pane. */
    'highlightedSide' => null,
    /** Show the close button, which dispatches `tedi:close` (upstream: passing onClose). */
    'closable' => false,
    /** Starting size of the first pane in percent, clamped to min-ratio – max-ratio. */
    'initialRatio' => 50,
    /** Minimum size of the first pane in percent. */
    'minRatio' => 20,
    /** Maximum size of the first pane in percent. */
    'maxRatio' => 80,
])

@php
    if ($direction !== 'horizontal' && $direction !== 'vertical') {
        throw new \InvalidArgumentException('tedi:split-pane requires direction="horizontal" or "vertical".');
    }

    // Numbers, so a string attribute (initial-ratio="70") reaches the JS as one.
    [$initialRatio, $minRatio, $maxRatio] = [$initialRatio + 0, $minRatio + 0, $maxRatio + 0];
    $isHorizontal = $direction === 'horizontal';
    $ratio = max($minRatio, min($maxRatio, $initialRatio));
    $omittedEdge = fn (string $side) => $isHorizontal
        ? ($side === 'first' ? 'right' : 'left')
        : ($side === 'first' ? 'bottom' : 'top');
    $handleIcon = $isHorizontal ? 'code' : 'unfold_more';

    $config = [
        'direction' => $direction,
        'ratio' => $ratio,
        'minRatio' => $minRatio,
        'maxRatio' => $maxRatio,
    ];
@endphp

<div {{ $attributes->class(['tedi-split-pane', 'tedi-split-pane--'.$direction]) }} x-data="tediSplitPane(@js($config))">
    <div class="tedi-split-pane__pane" style="flex-grow: {{ $ratio }}" data-tedi-split-pane="first">
        {{ $first ?? '' }}
        @if ($highlightedSide === 'first')
            <div aria-hidden="true" class="tedi-split-pane__ring tedi-split-pane__ring--omit-{{ $omittedEdge('first') }}"></div>
        @endif
    </div>

    <div class="tedi-split-pane__divider">
        <div
            role="separator"
            tabindex="0"
            aria-orientation="{{ $isHorizontal ? 'vertical' : 'horizontal' }}"
            aria-valuemin="{{ $minRatio }}"
            aria-valuemax="{{ $maxRatio }}"
            aria-valuenow="{{ round($ratio) }}"
            aria-label="{{ __('tedi::tedi.splitPaneResize') }}"
            class="tedi-split-pane__separator"
            data-tedi-split-pane="separator"
            x-on:mousedown="startDrag($event)"
            x-on:touchstart="startDrag()"
            x-on:keydown="onKeydown($event)"
        >
            <span aria-hidden="true" class="tedi-split-pane__handle"><span role="img" aria-hidden="true" style="--_tedi-icon-size: var(--icon-03)" class="notranslate material-symbols material-symbols--outlined tedi-icon tedi-icon--color-primary">{{ $handleIcon }}</span></span>
        </div>

        @if ($closable)
            <div class="tedi-split-pane__close">
                <tedi:button
                    variant="secondary"
                    icon-start="close"
                    icon-only
                    :aria-label="__('tedi::tedi.close')"
                    x-on:click="close()"
                />
            </div>
        @endif
    </div>

    <div class="tedi-split-pane__pane" style="flex-grow: {{ 100 - $ratio }}" data-tedi-split-pane="second">
        {{ $second ?? '' }}
        @if ($highlightedSide === 'second')
            <div aria-hidden="true" class="tedi-split-pane__ring tedi-split-pane__ring--omit-{{ $omittedEdge('second') }}"></div>
        @endif
    </div>
</div>
