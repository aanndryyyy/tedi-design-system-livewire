// =========================================================================
// Overlay — the Alpine layer
// =========================================================================
//
// Open/close state, dismissal, and the DOM writes that apply what
// `position.js` computes. Shared by dropdown, tooltip and popover.
//
// Deliberately not ported: focus trapping inside the panel. Escape-to-close,
// outside-click dismissal and trigger focus return are ported, because they
// are what makes the component usable rather than merely correct.
//
// The dropdown's keyboard layer — the roving tabindex over items and
// `tabOutOfDropdown` — lives in `dropdown.js`, which composes this function.
// It is deliberately NOT part of `overlay()`: tooltip and popover share this
// function and have no item list to rove over.
//
// The panel is positioned `fixed` in viewport coordinates and stays in the
// document where it was written, rather than being re-parented into an
// overlay container. That keeps the Blade markup a single tree — but it also
// means a panel inside a `transform`ed ancestor is positioned relative to
// that ancestor, which is the one case where CDK's detached container wins.

import {
    BASE_GAP,
    OPPOSITE,
    VIEWPORT_PADDING,
    arrowOffset,
    coordsFor,
    fits,
    parsePlacement,
    viewport,
} from './position.js';

/**
 * `tediOverlay` — shared open/close + anchoring for dropdown, tooltip and
 * popover. The component supplies refs; every one is optional except
 * `trigger` and `panel`.
 *
 *   x-ref="trigger"  the anchor element
 *   x-ref="panel"    the positioned element (gets position/top/left)
 *   x-ref="arrow"    optional; gets left/top so it points at the trigger
 *
 * Config:
 *   placement        'bottom-start' | … | 'auto'      default 'bottom'
 *   offset           extra px on top of the 8px base gap  default 0
 *   preventOverflow  flip to the opposite side when needed  default true
 *   hideOnScroll     close when the page (not the panel) scrolls  default false
 *   dismissible      close on outside click / focus  default true
 *   closeOnEscape    default true
 *   openWith         'click' | 'hover' | 'both' | 'none'   default 'click'
 *   hoverDelay       ms before a hover-opened overlay closes  default 100
 *   arrowSize        px, for the arrow offset maths  default 8
 *   matchTriggerWidth  set --_tedi-dropdown-trigger-width on the panel
 *   lockScroll       hide body overflow while open  default false
 */
export function overlay(config) {
    var cfg = config || {};

    return {
        open: !!cfg.open,
        /** Resolved side, mirrored to `data-placement` for the arrow CSS. */
        side: parsePlacement(cfg.placement).side === 'auto'
            ? 'bottom'
            : parsePlacement(cfg.placement).side,
        _hideTimer: null,
        _frame: null,
        _listeners: [],
        _contentHovered: false,

        init: function () {
            var self = this;

            this.$watch('open', function (value) {
                if (value) {
                    self._onOpen();
                } else {
                    self._onClose();
                }
            });

            if (this.open) {
                this.$nextTick(function () { self._onOpen(); });
            }
        },

        destroy: function () {
            this._onClose();
        },

        // -- open state -----------------------------------------------

        show: function () {
            clearTimeout(this._hideTimer);
            this.open = true;
        },

        hide: function (focusTrigger) {
            clearTimeout(this._hideTimer);
            this.open = false;

            if (focusTrigger && this.$refs.trigger) {
                var focusable = this._focusableTrigger();
                if (focusable) focusable.focus({ preventScroll: true });
            }
        },

        toggle: function () {
            if (this.open) {
                this.hide(true);
            } else {
                this.show();
            }
        },

        /** Hover intent: leaving trigger or panel closes after a delay. */
        hoverOpen: function () {
            if (cfg.openWith === 'none') return;
            this.show();
        },

        hoverClose: function () {
            if (cfg.openWith === 'none') return;

            var self = this;
            clearTimeout(this._hideTimer);
            this._hideTimer = setTimeout(function () {
                if (!self._contentHovered) self.hide(false);
            }, cfg.hoverDelay == null ? 100 : cfg.hoverDelay);
        },

        contentEnter: function () {
            this._contentHovered = true;
            clearTimeout(this._hideTimer);
        },

        contentLeave: function () {
            this._contentHovered = false;
            this.hoverClose();
        },

        // -- positioning ----------------------------------------------

        position: function () {
            var trigger = this.$refs.trigger;
            var panel = this.$refs.panel;

            if (!trigger || !panel) return;

            // Measure unshifted: a previous run's inline offsets would
            // otherwise feed back into the next measurement.
            panel.style.position = 'fixed';
            panel.style.left = '0px';
            panel.style.top = '0px';

            var triggerRect = trigger.getBoundingClientRect();
            var panelRect = panel.getBoundingClientRect();
            var view = viewport();
            var parsed = parsePlacement(cfg.placement);
            var gap = BASE_GAP + (cfg.offset || 0);
            var size = { width: panelRect.width, height: panelRect.height };
            var side;

            if (parsed.side === 'auto') {
                // Upstream tries top, bottom, right, left in that order.
                var order = ['top', 'bottom', 'right', 'left'];
                side = order[0];

                for (var i = 0; i < order.length; i++) {
                    if (fits(order[i], triggerRect, size, gap, view)) {
                        side = order[i];
                        break;
                    }
                }
            } else {
                side = parsed.side;

                var preventOverflow = cfg.preventOverflow !== false;
                if (preventOverflow && !fits(side, triggerRect, size, gap, view)
                    && fits(OPPOSITE[side], triggerRect, size, gap, view)) {
                    side = OPPOSITE[side];
                }
            }

            var coords = coordsFor(side, parsed.align, triggerRect, size, gap);

            // Horizontal-only push, matching upstream's applyHorizontalPush:
            // the cross axis is left alone so the panel scrolls with its
            // trigger instead of sticking to the viewport edge.
            var maxX = view.width - size.width - VIEWPORT_PADDING;
            if (coords.x > maxX) coords.x = maxX;
            if (coords.x < VIEWPORT_PADDING) coords.x = VIEWPORT_PADDING;

            panel.style.left = Math.round(coords.x) + 'px';
            panel.style.top = Math.round(coords.y) + 'px';

            this.side = side;

            if (cfg.matchTriggerWidth) {
                panel.style.setProperty(
                    '--_tedi-dropdown-trigger-width',
                    Math.round(triggerRect.width) + 'px'
                );
            }

            this._positionArrow(side, triggerRect, panel);
        },

        _positionArrow: function (side, triggerRect, panel) {
            var arrow = this.$refs.arrow;
            if (!arrow) return;

            var size = arrow.offsetWidth || cfg.arrowSize || 8;
            var offset = arrowOffset(side, triggerRect, panel.getBoundingClientRect(), size);

            arrow.style.left = offset.left == null ? '' : offset.left + 'px';
            arrow.style.top = offset.top == null ? '' : offset.top + 'px';
        },

        // -- lifecycle ------------------------------------------------

        _onOpen: function () {
            var self = this;

            // The panel is x-show'd; measure only once it has a box.
            this.$nextTick(function () { self.position(); });

            // A single measurement at open time is not enough. The trigger
            // can still change size afterwards — a webfont swapping in is
            // the common case, and with `matchTriggerWidth` that leaves the
            // panel pinned to the fallback font's narrower width until it is
            // reopened. Watch the trigger instead of measuring once.
            if (window.ResizeObserver && this.$refs.trigger) {
                var observer = new ResizeObserver(function () { self._schedule(); });
                observer.observe(this.$refs.trigger);
                this._listeners.push(function () { observer.disconnect(); });
            }

            this._listen(window, 'resize', function () { self._schedule(); });
            this._listen(document, 'scroll', function (event) {
                if (cfg.hideOnScroll && !self._contains(event.target)) {
                    self.hide(false);
                    return;
                }

                self._schedule();
            }, true);

            if (cfg.closeOnEscape !== false) {
                this._listen(document, 'keydown', function (event) {
                    if (event.key === 'Escape' && self.open) {
                        event.preventDefault();
                        self.hide(true);
                    }
                });
            }

            if (cfg.dismissible !== false) {
                // mousedown, not click: a click that starts inside the panel
                // and ends outside must not dismiss.
                this._listen(document, 'mousedown', function (event) {
                    if (!self._contains(event.target)) self.hide(false);
                });

                // Upstream dismisses on focus leaving the trigger/panel too,
                // not only on an outside pointer press — otherwise tabbing
                // past an open panel leaves it stranded on screen.
                this._listen(document, 'focusin', function (event) {
                    if (!self._contains(event.target)) self.hide(false);
                });
            }

            if (cfg.lockScroll) {
                document.body.style.overflow = 'hidden';
                this._lockedScroll = true;
            }
        },

        _onClose: function () {
            clearTimeout(this._hideTimer);

            if (this._frame) {
                cancelAnimationFrame(this._frame);
                this._frame = null;
            }

            this._listeners.forEach(function (off) { off(); });
            this._listeners = [];
            this._contentHovered = false;

            if (this._lockedScroll) {
                document.body.style.overflow = '';
                this._lockedScroll = false;
            }
        },

        _schedule: function () {
            var self = this;
            if (this._frame) cancelAnimationFrame(this._frame);

            this._frame = requestAnimationFrame(function () {
                self._frame = null;
                if (self.open) self.position();
            });
        },

        _listen: function (target, event, handler, capture) {
            var options = capture ? { capture: true, passive: true } : undefined;
            target.addEventListener(event, handler, options);
            this._listeners.push(function () {
                target.removeEventListener(event, handler, options);
            });
        },

        _contains: function (node) {
            if (!node) return false;

            return (this.$refs.trigger && this.$refs.trigger.contains(node))
                || (this.$refs.panel && this.$refs.panel.contains(node));
        },

        /**
         * The element that is actually in the tab order. Mirrors the
         * upstream trigger directives, which resolve through a wrapper to
         * the button it renders rather than focusing the wrapper itself.
         */
        _focusableTrigger: function () {
            var el = this.$refs.trigger;
            if (!el) return null;

            if (el.tagName === 'BUTTON' || (el.tagName === 'A' && el.hasAttribute('href'))) {
                return el;
            }

            return el.querySelector('button, a[href], [tabindex]') || el;
        },
    };
}
