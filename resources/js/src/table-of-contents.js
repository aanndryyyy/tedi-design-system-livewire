// =========================================================================
// Table of contents — the active trail, and the collapsible bottom sheet
// =========================================================================
//
// Port of `src/tedi/components/navigation/table-of-contents/`. Upstream's TOC
// is CONTROLLED: the consumer owns `activeId` (scroll-spy, routing, clicks)
// and the component only renders it. So this module has no scroll spy of its
// own — that moved into the consumer, exactly as upstream's StickyInLayout
// story does it. What it does own:
//
//   tableOfContents(config)
//     - `activeId` as reactive state, so a consumer can drive it from Alpine
//       (`x-model` / `wire:model` reach it through `x-modelable` on the root)
//       and the items' selected class and `aria-current` follow without a
//       server round-trip. The server renders the same state up front.
//     - `isOpen(group)` — upstream's `isOpen()`: a nested group shows always
//       when `defaultOpen`, otherwise only along the active trail. Angular
//       walks `contentChildren()`; here the trail is read from the DOM
//       through each item's `data-toc-item-id`.
//     - `scrollActiveIntoView` — upstream's `revealActiveItem()` +
//       `findScrollParent()`, verbatim: after `activeId` changes (and once on
//       init, as `afterRenderEffect` does), scroll the TOC's own scroll region
//       just enough to reveal the active row, 8px margin, smooth unless the
//       user prefers reduced motion.
//
//   tableOfContentsCollapsible(config)
//     - everything above, plus the bottom sheet: `open`, `close()`, Escape,
//       backdrop click and closing when a link or button in the list is
//       activated (`onListClick`). Focus moves to the first tabbable element
//       in the sheet on open and back to the bar's trigger on close — CDK
//       Dialog's `autoFocus: 'first-tabbable'` + `restoreFocus`.
//     - `hideOnScroll`: hide the bar on scroll down, reveal it on scroll up or
//       within 8px of the top, ignoring deltas under 4px, one update per
//       animation frame — upstream's `handleScroll()` verbatim. The scroll
//       target is the window, or the element `scrollContainer` selects.
//
// One structural divergence: CDK re-parents the sheet into an overlay
// container at <body>. Here the sheet stays where it was written and is
// positioned `fixed` (CONVENTIONS.md §11).

function revealActiveItem(host) {
    var activeItem = host.querySelector('[aria-current="location"]');
    if (!activeItem) return;

    var target = activeItem.querySelector(':scope > .tedi-table-of-contents__row') || activeItem;
    var scroller = findScrollParent(host, target);
    if (!scroller) return;

    var scrollerRect = scroller.getBoundingClientRect();
    var targetRect = target.getBoundingClientRect();
    var margin = 8;
    var behavior = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth';

    if (targetRect.top < scrollerRect.top + margin) {
        scroller.scrollBy({ top: targetRect.top - scrollerRect.top - margin, behavior: behavior });
    } else if (targetRect.bottom > scrollerRect.bottom - margin) {
        scroller.scrollBy({ top: targetRect.bottom - scrollerRect.bottom + margin, behavior: behavior });
    }
}

function findScrollParent(host, el) {
    var node = el.parentElement;

    while (node && host.contains(node)) {
        var overflowY = getComputedStyle(node).overflowY;

        if ((overflowY === 'auto' || overflowY === 'scroll') && node.scrollHeight > node.clientHeight) {
            return node;
        }

        node = node.parentElement;
    }

    return null;
}

export function tableOfContents(config) {
    var settings = config || {};

    return {
        activeId: settings.activeId || '',
        defaultOpen: settings.defaultOpen !== false,
        scrollActiveIntoView: settings.scrollActiveIntoView === true,

        init: function () {
            var self = this;

            if (!this.scrollActiveIntoView) return;

            var reveal = function () {
                if (self.activeId) self.$nextTick(function () { revealActiveItem(self.$root); });
            };

            reveal();
            this.$watch('activeId', reveal);
        },

        /** The group's item is open when the active item is it or below it. */
        isOpen: function (group) {
            if (this.defaultOpen) return true;
            if (!this.activeId) return false;

            var item = group.parentElement;
            if (item.getAttribute('data-toc-item-id') === this.activeId) return true;

            return Array.prototype.some.call(
                item.querySelectorAll('[data-toc-item-id]'),
                function (el) { return el.getAttribute('data-toc-item-id') === this.activeId; },
                this
            );
        },
    };
}

export function tableOfContentsCollapsible(config) {
    var settings = config || {};
    var base = tableOfContents(config);

    return Object.assign(base, {
        open: false,
        barHidden: false,
        hideOnScroll: settings.hideOnScroll === true,
        scrollContainer: settings.scrollContainer || null,
        _lastScrollY: 0,
        _ticking: false,
        _scrollTarget: null,
        _onScroll: null,
        _onKeydown: null,

        init: function () {
            var self = this;
            var container = this.scrollContainer ? document.querySelector(this.scrollContainer) : null;

            this._scrollTarget = container || window;
            this._lastScrollY = this._currentScrollY();
            this._onScroll = function () { self._handleScroll(); };
            this._scrollTarget.addEventListener('scroll', this._onScroll, { passive: true });

            this._onKeydown = function (event) {
                if (self.open && event.key === 'Escape') self.close();
            };
            document.addEventListener('keydown', this._onKeydown);

            this.$watch('open', function (open) {
                self.$nextTick(function () {
                    if (open) {
                        var first = self.$refs.sheet && self.$refs.sheet.querySelector(
                            'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
                        );
                        if (first) first.focus();
                    } else if (self.$refs.trigger) {
                        var trigger = self.$refs.trigger.querySelector('button') || self.$refs.trigger;
                        trigger.focus();
                    }
                });
            });
        },

        destroy: function () {
            if (this._scrollTarget) this._scrollTarget.removeEventListener('scroll', this._onScroll);
            document.removeEventListener('keydown', this._onKeydown);
        },

        close: function () {
            this.open = false;
        },

        onListClick: function (event) {
            if (event.target.closest('a, button')) this.close();
        },

        _currentScrollY: function () {
            return this._scrollTarget && this._scrollTarget !== window
                ? this._scrollTarget.scrollTop
                : window.scrollY;
        },

        _handleScroll: function () {
            var self = this;

            if (this._ticking) return;
            this._ticking = true;

            requestAnimationFrame(function () {
                self._ticking = false;
                var currentY = self._currentScrollY();

                if (!self.hideOnScroll) {
                    self.barHidden = false;
                    self._lastScrollY = currentY;
                    return;
                }

                var delta = currentY - self._lastScrollY;
                if (Math.abs(delta) < 4) return;

                self.barHidden = currentY > 8 && delta > 0;
                self._lastScrollY = currentY;
            });
        },
    });
}
