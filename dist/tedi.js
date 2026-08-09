/**
 * TEDI × Livewire — optional behaviour bundle.
 *
 * Most interactive components in this package declare their behaviour inline
 * with Alpine (`x-data` / `x-on`) directly in their Blade template, so they need
 * no registration here. This file exists for behaviours that are too large to
 * inline, and is emitted by the @tediScripts directive.
 *
 * Alpine itself is NOT bundled: Livewire already ships it. If you use these
 * components without Livewire, include Alpine yourself before this file.
 */
(function () {
    'use strict';

    // =========================================================================
    // Overlay positioning
    // =========================================================================
    //
    // Angular anchors dropdown / tooltip / popover with CDK Overlay. There is no
    // CDK here, and TEDI ships no placement CSS — its stylesheet only reads a
    // `data-placement` attribute (to rotate the arrow) and expects the pane's
    // x/y and the arrow's left/top to arrive as inline styles. So the placement
    // maths has to exist somewhere.
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
    //
    // Deliberately not ported: focus trapping inside the panel, the roving
    // tabindex over dropdown items, and `tabOutOfDropdown`. Escape-to-close,
    // outside-click dismissal and trigger focus return are ported, because they
    // are what makes the component usable rather than merely correct.
    //
    // The panel is positioned `fixed` in viewport coordinates and stays in the
    // document where it was written, rather than being re-parented into an
    // overlay container. That keeps the Blade markup a single tree — but it also
    // means a panel inside a `transform`ed ancestor is positioned relative to
    // that ancestor, which is the one case where CDK's detached container wins.

    var SIDES = ['top', 'bottom', 'left', 'right'];
    var OPPOSITE = { top: 'bottom', bottom: 'top', left: 'right', right: 'left' };

    /** Every placement carries this base gap upstream (POSITION_MAP's ±8). */
    var BASE_GAP = 8;

    /** Viewport margin kept free when pushing an overflowing panel back in. */
    var VIEWPORT_PADDING = 4;

    function parsePlacement(placement) {
        var parts = String(placement || 'bottom').split('-');
        var side = parts[0];
        var align = parts[1] || 'center';

        if (side !== 'auto' && SIDES.indexOf(side) === -1) {
            side = 'bottom';
        }

        return { side: side, align: align };
    }

    function viewport() {
        return {
            width: document.documentElement.clientWidth,
            height: document.documentElement.clientHeight,
        };
    }

    /** Does `side` have room for a panel of this size next to the trigger? */
    function fits(side, trigger, panel, gap, view) {
        if (side === 'top') return trigger.top - gap - panel.height >= 0;
        if (side === 'bottom') return trigger.bottom + gap + panel.height <= view.height;
        if (side === 'left') return trigger.left - gap - panel.width >= 0;
        return trigger.right + gap + panel.width <= view.width;
    }

    /** Top-left corner, in viewport coordinates, for a resolved side + align. */
    function coordsFor(side, align, trigger, panel, gap) {
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
    function arrowOffset(side, trigger, panelRect, size) {
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
    function overlay(config) {
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

    /**
     * `tediModal` — the standalone (`[(open)]`) branch of Angular's modal:
     * backdrop click, Escape, body scroll lock, and focus restore. The service
     * branch is CDK Dialog and has no Blade equivalent.
     */
    function modal(config) {
        var cfg = config || {};

        return {
            open: !!cfg.open,
            _previouslyFocused: null,
            _previousOverflow: '',

            init: function () {
                var self = this;

                this.$watch('open', function (value) {
                    if (value) {
                        self._onOpen();
                    } else {
                        self._onClose();
                    }
                });

                if (this.open) this._onOpen();
            },

            destroy: function () {
                if (this.open) this._onClose();
            },

            show: function () { this.open = true; },
            hide: function () { this.open = false; },
            toggle: function () { this.open = !this.open; },

            onBackdropClick: function () {
                if (cfg.closeOnBackdropClick !== false) this.hide();
            },

            onKeydown: function (event) {
                if (event.key === 'Escape') this.hide();
            },

            _onOpen: function () {
                var self = this;

                this._previouslyFocused = document.activeElement;
                this._previousOverflow = document.body.style.overflow;
                document.body.style.overflow = 'hidden';

                this.$nextTick(function () {
                    if (self.$refs.dialog) self.$refs.dialog.focus({ preventScroll: true });
                });
            },

            _onClose: function () {
                document.body.style.overflow = this._previousOverflow;

                if (this._previouslyFocused && this._previouslyFocused.focus) {
                    this._previouslyFocused.focus({ preventScroll: true });
                }
            },
        };
    }

    function register(Alpine) {
        // Carousel — index state plus prev/next, kept minimal so the markup and
        // class names stay identical to the Angular original.
        Alpine.data('tediCarousel', (slideCount = 0) => ({
            index: 0,
            count: slideCount,
            go(i) {
                if (this.count > 0) this.index = (i + this.count) % this.count;
            },
            next() { this.go(this.index + 1); },
            prev() { this.go(this.index - 1); },
            isActive(i) { return this.index === i; },
        }));

        Alpine.data('tediOverlay', overlay);
        Alpine.data('tediModal', modal);
    }

    if (window.Alpine) {
        register(window.Alpine);
    } else {
        document.addEventListener('alpine:init', () => register(window.Alpine));
    }
})();
