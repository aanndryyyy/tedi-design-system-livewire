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
export function tableOfContents(config) {
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
