/**
 * `tediSheet` — port of react/src/tedi/components/overlays/sheet/{sheet.tsx,
 * components/sheet-content/sheet-content.tsx} (CONVENTIONS.md §13).
 *
 * Upstream splits the behaviour between `Sheet` (open / collapsed state,
 * Floating UI's click / dismiss / role interactions) and `Sheet.Content`
 * (transition status, focus management, scroll lock, and the drag gestures).
 * Here one Alpine scope on `tedi:sheet` holds all of it, because the trigger,
 * the panel and the header's collapse toggle all need the same state and an
 * anonymous Blade component has no context to share it through. The panel
 * hands its own options over with `register()`, from an `x-init`.
 *
 * Ported, with upstream's constants verbatim:
 * - open / close, with useTransitionStatus' `initial` → `open` → `close`
 *   statuses (350ms in, 300ms out) written to `data-status`;
 * - Escape (`closeOnEscape`) and outside press (`closeOnBackdropClick`) —
 *   Floating UI's `useDismiss` listens for `pointerdown` outside the panel and
 *   the trigger, which also covers the backdrop;
 * - focus: the first tabbable element on open, Tab cycling while `trapFocus`,
 *   and focus returned to the previously focused element (`returnFocus`);
 * - `lockScroll`, as `overflow: hidden` on `<body>`;
 * - the trigger's `aria-haspopup="dialog"` / `aria-expanded` /
 *   `aria-controls` (useRole + useClick), set on the trigger's first element
 *   child, since that is the element upstream clones them onto;
 * - collapse, reset to the initial `collapsed` value each time the sheet opens
 *   (but not on mount), as upstream does for an uncontrolled sheet;
 * - the drag gestures — start / expand / dismiss thresholds, the no-drag
 *   selector, and the snap-point maths — line for line from sheet-content.tsx.
 *
 * Not ported: FloatingPortal's re-parenting into `<body>` (the panel stays
 * where it is written — see CONVENTIONS.md §11) and the focus guards Floating
 * UI renders around the panel; the Tab cycling stands in for them.
 */

var DRAG_DISMISS_THRESHOLD = 120;
var DRAG_EXPAND_THRESHOLD = 40;
// A press only becomes a drag after moving this far, so a tap on the header stays a tap.
var DRAG_START_THRESHOLD_TOUCH = 10;
var DRAG_START_THRESHOLD_MOUSE = 2;
var DRAG_ZONE_SELECTOR = '.tedi-sheet__handle-zone, .tedi-sheet__header';
// Presses on interactive header content never drag.
var NO_DRAG_SELECTOR = [
    'a[href]',
    'button',
    'input',
    'select',
    'textarea',
    'label',
    '[contenteditable]:not([contenteditable="false"])',
    '[role="button"]',
    '[role="link"]',
    '[tabindex]:not([tabindex="-1"])',
].join(', ');

var TABBABLE_SELECTOR = [
    'a[href]',
    'area[href]',
    'button:not([disabled])',
    'input:not([disabled]):not([type="hidden"])',
    'select:not([disabled])',
    'textarea:not([disabled])',
    'iframe',
    '[contenteditable]:not([contenteditable="false"])',
    '[tabindex]:not([tabindex="-1"])',
].join(', ');

// useTransitionStatus' close duration; the 350ms open is the CSS transition's.
var CLOSE_DURATION = 300;

function closestInside(target, selector, panel) {
    var match = target.closest(selector);
    return match !== null && match !== panel && panel.contains(match);
}

function tabbables(container) {
    return Array.prototype.filter.call(container.querySelectorAll(TABBABLE_SELECTOR), function (el) {
        if (el.tabIndex < 0) return false;
        if (el.closest('[hidden], [inert]')) return false;
        var style = window.getComputedStyle(el);
        return style.display !== 'none' && style.visibility !== 'hidden';
    });
}

export function sheet(config) {
    var cfg = config || {};
    var initiallyCollapsed = !!cfg.collapsed;

    return {
        open: !!cfg.open,
        collapsed: initiallyCollapsed,
        // useTransitionStatus: 'unmounted' | 'initial' | 'open' | 'close'.
        status: cfg.open ? 'open' : 'unmounted',
        active: !!cfg.open,
        content: {},
        snapIndex: null,
        dragOffset: 0,
        dragging: false,
        _drag: null,
        _previouslyFocused: null,
        _previousOverflow: '',
        _scrollLocked: false,
        _outsidePressTarget: null,
        _timer: null,
        _offMorph: null,

        /** The collapse toggle's open state — the inverse of `collapsed`. */
        get expanded() {
            return !this.collapsed;
        },
        set expanded(value) {
            this.collapsed = !value;
        },

        init: function () {
            var self = this;

            this.$watch('open', function (value) {
                if (value) {
                    self._onOpen();
                } else {
                    self._onClose();
                }
            });

            this.$watch('collapsed', function () {
                self.resetDrag();
            });

            this._onPointerDownOutside = function (event) {
                self._outsidePress(event);
            };
            this._onKeydown = function (event) {
                if (event.key === 'Escape' && self.open && cfg.closeOnEscape !== false) self.hide();
            };
            this._onBlur = function () {
                self.resetDrag();
            };

            document.addEventListener('pointerdown', this._onPointerDownOutside, true);
            document.addEventListener('keydown', this._onKeydown);
            window.addEventListener('blur', this._onBlur);

            this.$nextTick(function () {
                self._syncTrigger();
                if (self.open) self._activate(true);
            });

            // The trigger is the consumer's element, so its ARIA cannot be
            // server-rendered, and Livewire's morph strips attributes the
            // server HTML lacks. Re-apply once morph has patched that element
            // (not an ancestor: its children are patched after the hook).
            var livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                var off = livewire.hook('morph.updated', function (payload) {
                    if (payload && payload.el && payload.el === self._triggerElement()) {
                        self._syncTrigger();
                    }
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }
        },

        destroy: function () {
            if (this._offMorph) this._offMorph();
            this._offMorph = null;
            document.removeEventListener('pointerdown', this._onPointerDownOutside, true);
            document.removeEventListener('keydown', this._onKeydown);
            window.removeEventListener('blur', this._onBlur);
            this._unlockScroll();
        },

        /** Called by `tedi:sheet-content` with its own options. */
        register: function (contentConfig) {
            this.content = contentConfig || {};
            if (this.content.defaultSnapPoint !== null && this.content.defaultSnapPoint !== undefined) {
                this.snapIndex = this.content.defaultSnapPoint;
            }
        },

        show: function () { this.open = true; },
        hide: function () { this.open = false; },
        toggle: function () { this.open = !this.open; },

        // -- trigger ----------------------------------------------------------

        _triggerElement: function () {
            var ref = this.$refs.trigger;
            if (!ref) return null;
            // `tedi:sheet-trigger` is a wrapper: the element upstream clones is
            // its child. Anything else holding the ref is the trigger itself.
            return ref.hasAttribute('data-tedi-sheet-trigger') ? (ref.firstElementChild || ref) : ref;
        },

        _syncTrigger: function () {
            var trigger = this._triggerElement();
            var panel = this.$refs.panel;
            if (!trigger) return;

            trigger.setAttribute('aria-haspopup', 'dialog');
            trigger.setAttribute('aria-expanded', this.open ? 'true' : 'false');

            if (this.open && panel && panel.id) {
                trigger.setAttribute('aria-controls', panel.id);
            } else {
                trigger.removeAttribute('aria-controls');
            }
        },

        // -- open / close -----------------------------------------------------

        _onOpen: function () {
            // An uncontrolled sheet returns to its initial collapsed state each
            // time it opens (not on mount).
            this.collapsed = initiallyCollapsed;
            this._activate(false);
        },

        _activate: function (onMount) {
            var self = this;

            clearTimeout(this._timer);
            this._previouslyFocused = document.activeElement;
            this.active = true;
            if (!onMount) this.status = 'initial';
            this._syncTrigger();

            if (this.content.lockScroll !== false) this._lockScroll();

            this.$nextTick(function () {
                var panel = self.$refs.panel;
                if (!panel) return;

                // The `initial` state must be laid out before `open` for the
                // transform transition to run; reading a layout property forces
                // that, without waiting on animation frames.
                void panel.offsetHeight;
                if (self.open) self.status = 'open';

                var first = tabbables(panel)[0];
                (first || panel).focus({ preventScroll: true });
            });
        },

        _onClose: function () {
            var self = this;

            this.resetDrag();
            this.status = 'close';
            this._syncTrigger();
            this._unlockScroll();

            this._timer = setTimeout(function () {
                if (!self.open) {
                    self.active = false;
                    self.status = 'unmounted';
                }
            }, CLOSE_DURATION);

            var returnTo = this._previouslyFocused;
            var outsidePress = this._outsidePressTarget !== null;
            this._outsidePressTarget = null;
            if (this.content.returnFocus === false || !returnTo || !returnTo.focus) return;

            if (outsidePress) {
                // Closed by a press outside: the press's own mousedown moves
                // focus after this runs (to <body> for the backdrop), so return
                // focus once it has — unless the press gave focus to something
                // else on the page (a button, a `<label for>`'s input), which
                // keeps it, as Floating UI's focus manager does.
                var panel = this.$refs.panel;
                setTimeout(function () {
                    var current = document.activeElement;
                    if (self.open) return;
                    if (current && current !== document.body && !(panel && panel.contains(current))) return;
                    returnTo.focus({ preventScroll: true });
                }, 0);
                return;
            }

            returnTo.focus({ preventScroll: true });
        },

        _lockScroll: function () {
            if (this._scrollLocked) return;
            this._scrollLocked = true;
            this._previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';
        },

        _unlockScroll: function () {
            if (!this._scrollLocked) return;
            this._scrollLocked = false;
            document.body.style.overflow = this._previousOverflow;
        },

        _outsidePress: function (event) {
            if (!this.open || cfg.closeOnBackdropClick === false) return;

            var panel = this.$refs.panel;
            var trigger = this.$refs.trigger;
            var target = event.target;

            if (panel && panel.contains(target)) return;
            if (trigger && trigger.contains(target)) return;
            // The visually hidden dismiss buttons sit beside the panel.
            if (target.closest && target.closest('[data-tedi-sheet-dismiss]')) return;

            this._outsidePressTarget = target;
            this.hide();
        },

        /** Tab cycling inside the panel — FloatingFocusManager's `modal`. */
        onPanelKeydown: function (event) {
            if (event.key !== 'Tab' || !this.open || this.content.trapFocus === false) return;

            var panel = this.$refs.panel;
            var items = tabbables(panel);

            if (items.length === 0) {
                event.preventDefault();
                panel.focus({ preventScroll: true });
                return;
            }

            var first = items[0];
            var last = items[items.length - 1];

            if (event.shiftKey && (document.activeElement === first || document.activeElement === panel)) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },

        // -- snap points and drag (sheet-content.tsx) -------------------------

        _snaps: function () {
            var points = this.content.snapPoints;
            return points ? points.slice().sort(function (a, b) { return a - b; }) : [];
        },

        _hasSnaps: function () {
            return this._snaps().length > 0 && !this.collapsed;
        },

        _currentSnap: function () {
            var snaps = this._snaps();
            var maxSnap = snaps.length ? snaps[snaps.length - 1] : 1;
            var index = this.snapIndex === null ? snaps.length - 1 : this.snapIndex;
            var snap = snaps[Math.max(0, Math.min(index, snaps.length - 1))];
            return snap === undefined ? maxSnap : snap;
        },

        _snapHeightPx: function (snap) {
            return snap * window.innerHeight;
        },

        /** The panel's dynamic inline style. Every key is always present so a
         *  value that no longer applies is cleared rather than left behind. */
        get panelStyle() {
            var c = this.content;
            var style = { 'height': '', 'max-height': '', 'min-height': '', 'transform': '', 'transition': '' };
            var snaps = this._snaps();
            var hasSnaps = this._hasSnaps();

            if (c.minHeight && !this.collapsed) style['min-height'] = c.minHeight;
            if (c.maxHeight && !hasSnaps) style['max-height'] = c.maxHeight;

            if (hasSnaps) {
                var maxSnap = snaps[snaps.length - 1];
                var currentSnap = this._currentSnap();
                style['max-height'] = (maxSnap * 100) + 'dvh';

                if (this.dragging) {
                    var height = Math.min(
                        maxSnap * window.innerHeight,
                        Math.max(0, this._snapHeightPx(currentSnap) - this.dragOffset)
                    );
                    style['height'] = height + 'px';
                    style['transition'] = 'none';
                } else {
                    style['height'] = (currentSnap * 100) + 'dvh';
                }
            } else if (this.dragOffset > 0) {
                style['transform'] = 'translateY(' + this.dragOffset + 'px)';
                style['transition'] = 'none';
            }

            return style;
        },

        resetDrag: function () {
            var drag = this._drag;
            if (!drag) return;
            this._drag = null;
            this.dragging = false;
            this.dragOffset = 0;
            document.removeEventListener('pointermove', drag.onDocMove, true);
            document.removeEventListener('pointerup', drag.onDocUp, true);
            if (drag.target.hasPointerCapture && drag.target.hasPointerCapture(drag.pointerId)) {
                drag.target.releasePointerCapture(drag.pointerId);
            }
        },

        dragStart: function (event) {
            if (!this.open || this.content.showHandle === false || event.button !== 0 || event.ctrlKey || this._drag !== null) return;

            var target = event.target;
            var currentTarget = event.currentTarget;
            if (!(target instanceof Element) || !closestInside(target, DRAG_ZONE_SELECTOR, currentTarget)) return;
            if (closestInside(target, NO_DRAG_SELECTOR, currentTarget)) return;

            var self = this;
            var drag = {
                pointerId: event.pointerId,
                startX: event.clientX,
                startY: event.clientY,
                target: currentTarget,
                started: false,
            };
            // Until the drag starts the pointer is not captured, so a mouse
            // that leaves the panel within its first move — a quick flick up
            // from the handle, which sits at the panel's top edge — would be
            // lost, and its pointerup with it. Follow it at document level
            // until capture takes over (captured events target the panel and
            // are left to the panel's own listeners). Touch is implicitly
            // captured already. Not upstream: sheet-content.tsx has the gap.
            drag.onDocMove = function (e) {
                if (!currentTarget.contains(e.target)) self.dragMove(e);
            };
            drag.onDocUp = function (e) {
                if (!currentTarget.contains(e.target)) self.dragEnd(e);
            };
            document.addEventListener('pointermove', drag.onDocMove, true);
            document.addEventListener('pointerup', drag.onDocUp, true);
            this._drag = drag;
        },

        dragMove: function (event) {
            var drag = this._drag;
            if (!drag || drag.pointerId !== event.pointerId) return;
            if (event.pointerType === 'mouse' && (event.buttons & 1) === 0) {
                this.resetDrag();
                return;
            }
            var delta = event.clientY - drag.startY;

            if (!drag.started) {
                var deltaX = event.clientX - drag.startX;
                var threshold = event.pointerType === 'touch' ? DRAG_START_THRESHOLD_TOUCH : DRAG_START_THRESHOLD_MOUSE;
                if (Math.max(Math.abs(delta), Math.abs(deltaX)) < threshold) return;
                // A mostly horizontal swipe isn't a sheet drag.
                if (Math.abs(deltaX) > Math.abs(delta)) {
                    this.resetDrag();
                    return;
                }
                drag.started = true;
                this.dragging = true;
                try {
                    drag.target.setPointerCapture(drag.pointerId);
                } catch (e) {
                    // A pointer that is no longer active cannot be captured;
                    // the drag still works without capture.
                }
            }

            this.dragOffset = this._hasSnaps() ? delta : Math.max(0, delta);
        },

        dragEnd: function (event) {
            var self = this;
            var drag = this._drag;
            if (!drag || drag.pointerId !== event.pointerId) return;
            var delta = event.clientY - drag.startY;
            this.resetDrag();
            if (!drag.started) return;

            if (this._hasSnaps()) {
                var snaps = this._snaps();
                var height = this._snapHeightPx(this._currentSnap()) - delta;

                if (height < this._snapHeightPx(snaps[0]) - DRAG_DISMISS_THRESHOLD) {
                    this.hide();
                } else {
                    var nearest = 0;
                    var best = Infinity;
                    snaps.forEach(function (snap, index) {
                        var distance = Math.abs(self._snapHeightPx(snap) - height);
                        if (distance < best) {
                            best = distance;
                            nearest = index;
                        }
                    });
                    this.snapIndex = nearest;
                }
            } else if (this.collapsed && delta < -DRAG_EXPAND_THRESHOLD) {
                this.collapsed = false;
            } else if (delta > DRAG_DISMISS_THRESHOLD) {
                this.hide();
            }
        },

        dragCancel: function (event) {
            if (this._drag && this._drag.pointerId === event.pointerId) this.resetDrag();
        },
    };
}
