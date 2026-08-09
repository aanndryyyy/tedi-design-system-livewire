// =========================================================================
// Overlay positioning — the maths
// =========================================================================
//
// Angular anchors dropdown / tooltip / popover with CDK Overlay. There is no
// CDK here, and TEDI ships no placement CSS — its stylesheet only reads a
// `data-placement` attribute (to rotate the arrow) and expects the pane's
// x/y and the arrow's left/top to arrive as inline styles. So the placement
// maths has to exist somewhere.
//
// This module is that maths and nothing else: pure functions over rectangles,
// no Alpine and no DOM writes, so it can be reasoned about (and, if it ever
// earns tests, tested) without a component around it. `overlay.js` is the
// Alpine layer that calls into it.
//
// It is a direct port of the upstream algorithm in
// `tedi/components/overlay/overlay-position.util.ts`, not an invention:
//
//   - the 12 `side[-align]` placements, plus `auto[-start|-end]`
//   - the base 8px gap each placement carries, plus the component's own
//     `offset` on top of it
//   - `preventOverflow` → flip to the opposite side when the preferred one
//     does not fit
//   - horizontal-only push (upstream's `applyHorizontalPush`), so an overlay
//     still scrolls naturally with its trigger on the cross axis
//   - `calculateArrowOffset`, including its `padding + size * 0.7` edge
//     margin for the rotated arrow's visual extent

export var SIDES = ['top', 'bottom', 'left', 'right'];
export var OPPOSITE = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' };

/** Every placement carries this base gap upstream (POSITION_MAP's ±8). */
export var BASE_GAP = 8;

/** Viewport margin kept free when pushing an overflowing panel back in. */
export var VIEWPORT_PADDING = 4;

export function parsePlacement(placement) {
    var parts = String(placement || 'bottom').split('-');
    var side = parts[0];
    var align = parts[1] || 'center';

    if (side !== 'auto' && SIDES.indexOf(side) === -1) {
        side = 'bottom';
    }

    return { side: side, align: align };
}

export function viewport() {
    return {
        width: document.documentElement.clientWidth,
        height: document.documentElement.clientHeight,
    };
}

/** Does `side` have room for a panel of this size next to the trigger? */
export function fits(side, trigger, panel, gap, view) {
    if (side === 'top') return trigger.top - gap - panel.height >= 0;
    if (side === 'bottom') return trigger.bottom + gap + panel.height <= view.height;
    if (side === 'left') return trigger.left - gap - panel.width >= 0;
    return trigger.right + gap + panel.width <= view.width;
}

/** Top-left corner, in viewport coordinates, for a resolved side + align. */
export function coordsFor(side, align, trigger, panel, gap) {
    var x;
    var y;

    if (side === 'top' || side === 'bottom') {
        y = side === 'top'
            ? trigger.top - gap - panel.height
            : trigger.bottom + gap;

        if (align === 'start') {
            x = trigger.left;
        } else if (align === 'end') {
            x = trigger.right - panel.width;
        } else {
            x = trigger.left + (trigger.width - panel.width) / 2;
        }
    } else {
        x = side === 'left'
            ? trigger.left - gap - panel.width
            : trigger.right + gap;

        if (align === 'start') {
            y = trigger.top;
        } else if (align === 'end') {
            y = trigger.bottom - panel.height;
        } else {
            y = trigger.top + (trigger.height - panel.height) / 2;
        }
    }

    return { x: x, y: y };
}

/**
 * Upstream's calculateArrowOffset: centre the arrow on the trigger, then
 * keep it clear of the panel's own rounded corners. `size * 0.7` is the
 * rotated square's half-diagonal, which is wider than half its side.
 */
export function arrowOffset(side, trigger, panelRect, size) {
    var edgeMargin = VIEWPORT_PADDING + size * 0.7;

    if (side === 'top' || side === 'bottom') {
        var center = trigger.left + trigger.width / 2;
        var left = center - panelRect.left;

        return {
            left: Math.round(Math.max(edgeMargin, Math.min(panelRect.width - edgeMargin, left))),
            top: null,
        };
    }

    var centerY = trigger.top + trigger.height / 2;
    var top = centerY - panelRect.top;

    return {
        left: null,
        top: Math.round(Math.max(edgeMargin, Math.min(panelRect.height - edgeMargin, top))),
    };
}
