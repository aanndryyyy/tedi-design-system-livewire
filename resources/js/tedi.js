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
    // Deliberately not ported: focus trapping inside the panel. Escape-to-close,
    // outside-click dismissal and trigger focus return are ported, because they
    // are what makes the component usable rather than merely correct.
    //
    // The dropdown's keyboard layer — the roving tabindex over items and
    // `tabOutOfDropdown` — lives in `tediDropdown` below, which composes this
    // engine. It is deliberately NOT part of `overlay()`: tooltip and popover
    // share this function and have no item list to rove over.
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
    // =========================================================================
    // Dropdown keyboard layer
    // =========================================================================
    //
    // A direct port of the focus/keyboard half of
    // `tedi/components/overlay/dropdown/`, which is spread across three files
    // upstream:
    //
    //   dropdown.component.ts          activeIndex, updateTabindexes(),
    //                                  focusFirst/Last/Active/Next/PrevItem(),
    //                                  setActiveToSelectedOrFirst(),
    //                                  tabOutOfDropdown()
    //   dropdown-item.component.ts     @HostListener('keydown') — Arrow/Home/
    //                                  End/Enter/Space/Tab; mousedown guard
    //   dropdown-trigger.directive.ts  ArrowDown/ArrowUp open-and-focus
    //
    // Two structural divergences, both forced by Blade having no component
    // instances to query:
    //
    //   1. Angular reads its items from `contentChildren(DropdownItemComponent)`
    //      and their `disabled()` / `value()` signals. Here the DOM is the only
    //      registry, so items are found with a querySelector and their state is
    //      read off the attributes the template already emits: `aria-disabled`
    //      for disabled, `aria-selected` for the listbox selection that
    //      `setActiveToSelectedOrFirst` keys on.
    //   2. Angular binds `keydown` per item. Here it is one delegated listener
    //      on the panel — `<li tedi-dropdown-item>` is an anonymous Blade
    //      component with no place to hang per-instance Alpine state, and the
    //      handler needs the sibling list anyway.
    //
    // Everything else follows upstream exactly, including the details that are
    // easy to get wrong by inventing instead of porting:
    //
    //   - Arrow keys do NOT wrap at the ends (getNextEnabledIndex returns null).
    //   - Disabled items keep their roving tabindex in menus so they stay
    //     discoverable, but lose it in listboxes.
    //   - Opening focuses the selected item, or the first enabled one — even
    //     when opened by mouse. That is upstream's `showDropdown('selected')`.
    //   - Tab does not move within the panel: it closes the dropdown and jumps
    //     to the next focusable element AFTER the trigger in document order.
    //     Upstream has no focus trap here, and neither does this.

    var FOCUSABLE_SELECTOR = [
        'a[href]',
        'area[href]',
        "input:not([disabled]):not([type='hidden'])",
        'select:not([disabled])',
        'textarea:not([disabled])',
        'button:not([disabled])',
        'summary',
        'iframe',
        'audio[controls]',
        'video[controls]',
        "[contenteditable]:not([contenteditable='false'])",
        "[tabindex]:not([tabindex='-1'])",
    ].join(',');

    /** Port of `tedi/utils/elements.util.ts` — getFocusableElements(). */
    function getFocusableElements(container) {
        return Array.prototype.filter.call(
            container.querySelectorAll(FOCUSABLE_SELECTOR),
            function (el) {
                var style = window.getComputedStyle(el);

                if (style.display === 'none' || style.visibility === 'hidden') return false;
                if (el.hasAttribute('hidden')) return false;
                if (el.closest('[inert]')) return false;

                var fieldset = el.closest('fieldset[disabled]');
                if (fieldset) {
                    var legend = fieldset.querySelector('legend');
                    if (!legend || !legend.contains(el)) return false;
                }

                if (el.tagName !== 'SUMMARY' && el.closest('details:not([open])')) return false;

                return true;
            }
        );
    }

    /**
     * `tediDropdown` — `tediOverlay` plus the ARIA menu/listbox keyboard layer.
     *
     * Takes every `tediOverlay` config key (see above) and adds none of its own:
     * the item role is read from the rendered `<ul role>` rather than configured,
     * so <tedi:dropdown-content dropdown-role> stays the single source of truth.
     *
     * Markup contract, on top of tediOverlay's trigger/panel refs:
     *   the panel contains  <ul role="menu|listbox">
     *   items are           <li tedi-dropdown-item>
     *   disabled items say  aria-disabled="true"
     *   the selected item   aria-selected="true"   (listbox only)
     */
    function dropdown(config) {
        var base = overlay(config);
        var baseInit = base.init;
        var baseHide = base.hide;
        var baseOnOpen = base._onOpen;

        return Object.assign(base, {
            /** Index into items() of the element holding tabindex="0". */
            activeIndex: null,
            /**
             * Where the next open should land focus — upstream's
             * `showDropdown(initialFocus)` argument. Set by the trigger's arrow
             * keys and consumed (and reset) by `_onOpen`. It exists because
             * `show()` and the focus that follows it are separated by a tick
             * here: without it, the trigger's own `$nextTick` and `_onOpen`'s
             * would both focus, and the later one would win by accident.
             */
            _initialFocus: 'selected',

            init: function () {
                baseInit.call(this);
                this.updateTabindexes();
            },

            hide: function (focusTrigger) {
                this.activeIndex = null;
                this.updateTabindexes();
                baseHide.call(this, focusTrigger);
            },

            _onOpen: function () {
                var self = this;
                var initialFocus = this._initialFocus;
                this._initialFocus = 'selected';

                baseOnOpen.call(this);

                // Upstream defers the focus so the overlay content is attached
                // first; $nextTick is the Alpine equivalent of that setTimeout.
                this.$nextTick(function () {
                    self.setActiveToSelectedOrFirst();

                    if (initialFocus === 'first') {
                        self.focusFirstItem();
                    } else if (initialFocus === 'last') {
                        self.focusLastItem();
                    } else {
                        self.focusActiveItem();
                    }
                });
            },

            // -- item registry --------------------------------------------

            items: function () {
                if (!this.$refs.panel) return [];

                return Array.prototype.slice.call(
                    this.$refs.panel.querySelectorAll('li[tedi-dropdown-item]')
                );
            },

            /** 'menu' | 'listbox', read from the list the content component renders. */
            _role: function () {
                var list = this.$refs.panel && this.$refs.panel.querySelector('ul[role]');

                return list ? list.getAttribute('role') : 'menu';
            },

            _isDisabled: function (item) {
                return item.getAttribute('aria-disabled') === 'true';
            },

            // -- roving tabindex ------------------------------------------

            updateTabindexes: function () {
                var self = this;
                var role = this._role();
                var active = this.activeIndex;

                this.items().forEach(function (item, i) {
                    if (i === active && !self._isDisabled(item)) {
                        item.setAttribute('tabindex', '0');
                    } else if (role === 'listbox' && self._isDisabled(item)) {
                        item.removeAttribute('tabindex');
                    } else {
                        item.setAttribute('tabindex', '-1');
                    }
                });
            },

            setActiveToSelectedOrFirst: function () {
                var self = this;
                var items = this.items();

                var selected = items.findIndex(function (item) {
                    return item.getAttribute('aria-selected') === 'true';
                });

                if (selected !== -1 && !this._isDisabled(items[selected])) {
                    this.activeIndex = selected;
                    this.updateTabindexes();
                    return;
                }

                var first = items.findIndex(function (item) { return !self._isDisabled(item); });

                if (first !== -1) {
                    this.activeIndex = first;
                    this.updateTabindexes();
                }
            },

            // -- focus movement -------------------------------------------

            focusActiveItem: function () {
                if (this.activeIndex == null) return;

                var item = this.items()[this.activeIndex];
                if (!item) return;

                item.focus();
                item.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            },

            focusFirstItem: function () {
                var self = this;
                var items = this.items();
                var index = items.findIndex(function (item) { return !self._isDisabled(item); });

                if (index !== -1) this._activate(index);
            },

            focusLastItem: function () {
                var items = this.items();

                for (var i = items.length - 1; i >= 0; i--) {
                    if (!this._isDisabled(items[i])) {
                        this._activate(i);
                        return;
                    }
                }
            },

            focusNextItem: function (fromEl) {
                var items = this.items();
                var from = items.indexOf(fromEl);
                if (from === -1) return;

                // No wrap-around: upstream's getNextEnabledIndex stops at the end.
                for (var i = from + 1; i < items.length; i++) {
                    if (!this._isDisabled(items[i])) {
                        this._activate(i);
                        return;
                    }
                }
            },

            focusPrevItem: function (fromEl) {
                var items = this.items();
                var from = items.indexOf(fromEl);
                if (from === -1) return;

                for (var i = from - 1; i >= 0; i--) {
                    if (!this._isDisabled(items[i])) {
                        this._activate(i);
                        return;
                    }
                }
            },

            _activate: function (index) {
                this.activeIndex = index;
                this.updateTabindexes();
                this.focusActiveItem();
            },

            // -- keyboard entry points -------------------------------------

            /**
             * The trigger's ArrowDown/ArrowUp. Escape is deliberately absent —
             * tediOverlay already closes on it at the document level, and
             * handling it here too would fire `hide` twice.
             */
            triggerKeydown: function (event) {
                if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;

                event.preventDefault();
                var last = event.key === 'ArrowUp';

                if (this.open) {
                    last ? this.focusLastItem() : this.focusFirstItem();
                    return;
                }

                this._initialFocus = last ? 'last' : 'first';
                this.show();
            },

            /** Delegated from the panel; `event.target` is inside an item. */
            menuKeydown: function (event) {
                var item = event.target.closest('li[tedi-dropdown-item]');
                if (!item || !this.$refs.panel.contains(item)) return;

                if (this._isDisabled(item)) {
                    event.preventDefault();
                    return;
                }

                switch (event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        this.focusNextItem(item);
                        break;

                    case 'ArrowUp':
                        event.preventDefault();
                        this.focusPrevItem(item);
                        break;

                    case 'Home':
                        event.preventDefault();
                        this.focusFirstItem();
                        break;

                    case 'End':
                        event.preventDefault();
                        this.focusLastItem();
                        break;

                    case 'Enter':
                    case ' ':
                        // Upstream calls its own onItemSelect(). Here the item's
                        // click handler IS that logic — `x-on:click="hide(true)"`
                        // plus whatever wire:click the consumer bound — so
                        // activation has to go through a real click event or
                        // keyboard users would silently skip both.
                        event.preventDefault();
                        item.click();
                        break;

                    case 'Tab':
                        event.preventDefault();
                        this.tabOutOfDropdown(event.shiftKey);
                        break;
                }
            },

            /**
             * Tab/Shift+Tab close the panel and continue the page's tab order
             * from the trigger, rather than stepping through the items. The
             * panel is excluded from the sweep: the active item holds
             * tabindex="0" while open and would otherwise be the "next" stop.
             */
            tabOutOfDropdown: function (shiftKey) {
                var panel = this.$refs.panel;
                var trigger = this._focusableTrigger();

                var focusable = getFocusableElements(document.body).filter(function (el) {
                    return !panel || !panel.contains(el);
                });

                var index = focusable.indexOf(trigger);
                var next = shiftKey ? focusable[index - 1] : focusable[index + 1];

                this.hide(false);

                if (next) {
                    this.$nextTick(function () { next.focus(); });
                }
            },
        });
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

    // =========================================================================
    // Table of contents — scroll spy, seek, and the mobile panel
    // =========================================================================
    //
    // Port of the community `table-of-contents.component.ts`. Big enough to earn
    // its place here rather than inline in the template (CONVENTIONS.md §8).
    //
    // What upstream does and this keeps:
    //
    //   - one IntersectionObserver over every heading an item points at, whose
    //     callback sorts the targets by `getBoundingClientRect().top` and
    //     selects the first one wholly inside the viewport
    //   - `scrollAware` gates that callback, exactly as upstream's early return
    //   - `seekTo` sets a 500ms `isSeeking` window during which the observer
    //     stays quiet, so a smooth scroll does not repaint the active item on
    //     the way past every heading it crosses
    //   - clicking an item selects it, closes the mobile panel, then seeks
    //
    // Two deliberate differences, both consequences of there being no Angular
    // Router and no CDK Dialog here:
    //
    //   - upstream calls `router.navigate([], { fragment: id })`. This writes the
    //     same `#id` with `history.replaceState`, which records the fragment
    //     without the browser's instant jump that would fight the smooth scroll.
    //   - upstream opens the panel in a CDK dialog holding a second copy of the
    //     nav. Here the one nav gets `table-of-contents--modal-active`, which is
    //     the class upstream's copy is rendered with, so the same rule applies.
    function tableOfContents(config) {
        var settings = config || {};

        return {
            open: false,
            activeId: settings.activeId || '',
            scrollAware: settings.scrollAware !== false,
            scrollOnClick: settings.scrollOnClick !== false,
            isSeeking: false,
            _seekingTimeout: undefined,
            _observer: undefined,

            init: function () {
                var self = this;

                if (typeof IntersectionObserver === 'undefined') return;

                this._observer = new IntersectionObserver(function () {
                    self._onIntersect();
                }, { rootMargin: '0px 0px 0px 0px' });

                this._targets().forEach(function (target) {
                    self._observer.observe(target);
                });
            },

            destroy: function () {
                if (this._observer) this._observer.disconnect();
                clearTimeout(this._seekingTimeout);
            },

            /** Ids in document order, read off the rendered items. */
            _ids: function () {
                return Array.prototype.map.call(
                    this.$el.querySelectorAll('[data-toc-id]'),
                    function (el) { return el.getAttribute('data-toc-id'); }
                ).filter(Boolean);
            },

            _targets: function () {
                return this._ids()
                    .map(function (id) { return document.getElementById(id); })
                    .filter(Boolean);
            },

            _onIntersect: function () {
                if (!this.scrollAware || this.isSeeking) return;

                var targets = this._targets().sort(function (a, b) {
                    return a.getBoundingClientRect().top - b.getBoundingClientRect().top;
                });

                for (var i = 0; i < targets.length; i++) {
                    var rect = targets[i].getBoundingClientRect();

                    if (rect.top >= 0 && rect.bottom <= window.innerHeight) {
                        this.activeId = targets[i].id;
                        break;
                    }
                }
            },

            select: function (id) {
                this.activeId = id;
                this.open = false;
                this.seekTo(id);
            },

            seekTo: function (id) {
                var self = this;

                if (!this.scrollOnClick || !id) return;

                this.isSeeking = true;
                clearTimeout(this._seekingTimeout);
                this._seekingTimeout = setTimeout(function () {
                    self.isSeeking = false;
                }, 500);

                var target = document.getElementById(id);
                if (target) target.scrollIntoView({ behavior: 'smooth' });

                if (window.history && window.history.replaceState) {
                    window.history.replaceState(null, '', '#' + id);
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
        Alpine.data('tediDropdown', dropdown);
        Alpine.data('tediModal', modal);
        Alpine.data('tediTableOfContents', tableOfContents);
    }

    if (window.Alpine) {
        register(window.Alpine);
    } else {
        document.addEventListener('alpine:init', () => register(window.Alpine));
    }
})();
