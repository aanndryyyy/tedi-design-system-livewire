/**
 * tediScrollVisibility — hide or reveal content once the page has been
 * scrolled past a threshold.
 *
 * A direct port of react/src/tedi/components/misc/scroll-visibility/scroll-visibility.tsx.
 * The component itself is pure CSS: `.tedi-scroll-visibility--hidden` fades the
 * element out and translates it in the configured direction. All this does is
 * decide when that class is on.
 *
 * The decision, verbatim from upstream's second effect:
 *
 *   distance = scrollDirection === 'down'
 *       ? scrollTop
 *       : scrollHeight - clientHeight - scrollTop      // i.e. distance from the bottom
 *
 *   if (toggleVisibility && distance < lastDistance)   hidden = (visibility === 'show')
 *   else if (distance > scrollDistance)                hidden = (visibility !== 'show')
 *   else                                               hidden = (visibility === 'show')
 *
 * The middle branch is what "hide after scrolling 100px" means; the first is the
 * opt-in that flips it back when the user scrolls the other way, which is how a
 * hide-on-scroll-down / show-on-scroll-up header is built.
 *
 * Upstream gates the whole thing on a mounted flag so the server render is never
 * hidden. The equivalent here is that `hidden` starts false and the first
 * evaluation happens in `init()`, after the markup is already in the DOM with
 * its real class list (CONVENTIONS.md §8).
 *
 * Config keys, all optional:
 *   enabled          — false leaves the element permanently visible. Default true.
 *   visibility       — 'hide' (default) hides past the threshold; 'show' inverts it.
 *   toggleVisibility — re-show when scrolling the opposite way. Default false.
 *   scrollDistance   — threshold in px. Default 100.
 *   scrollDirection  — 'down' (default) measures from the top, 'up' from the bottom.
 *   scrollContainer  — CSS selector for the scrolling element. Default: the page.
 */
export function scrollVisibility(config = {}) {
    const {
        enabled = true,
        visibility = 'hide',
        toggleVisibility = false,
        scrollDistance = 100,
        scrollDirection = 'down',
        scrollContainer = null,
    } = config;

    return {
        hidden: false,
        lastDistance: 0,
        container: null,
        target: null,
        onScroll: null,

        init() {
            if (!enabled) return;

            this.container = scrollContainer
                ? document.querySelector(scrollContainer)
                : document.documentElement;

            if (!this.container) return;

            // A selector-addressed container scrolls itself; the page scrolls
            // on window, and documentElement's own scroll event does not fire.
            this.target = scrollContainer ? this.container : window;

            this.onScroll = () => this.update();
            this.target.addEventListener('scroll', this.onScroll, { passive: true });

            this.update();
        },

        destroy() {
            if (this.target && this.onScroll) {
                this.target.removeEventListener('scroll', this.onScroll);
            }
        },

        distance() {
            const { scrollTop, scrollHeight, clientHeight } = this.container;

            return scrollDirection === 'down'
                ? scrollTop
                : scrollHeight - clientHeight - scrollTop;
        },

        update() {
            const shouldShow = visibility === 'show';
            const distance = this.distance();

            if (toggleVisibility && distance < this.lastDistance) {
                this.hidden = shouldShow;
            } else if (distance > scrollDistance) {
                this.hidden = !shouldShow;
            } else {
                this.hidden = shouldShow;
            }

            this.lastDistance = distance;
        },
    };
}
