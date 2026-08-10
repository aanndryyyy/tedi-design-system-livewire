/**
 * tediHashTrigger — scroll an element into view when the URL hash names it.
 *
 * A direct port of react/src/tedi/components/navigation/hash-trigger/hash-trigger.tsx.
 * The component has no styles at all; this is the whole of it.
 *
 * Three upstream behaviours that look like details but are the component:
 *
 * 1. The hash is parsed as a SLASH-SEPARATED LIST, not a single fragment.
 *    `#/foo/bar` matches an element with id `foo` and one with id `bar`, which
 *    is what lets a deep-linked page open an accordion section and scroll to a
 *    heading inside it from one URL. Segments that are empty, a single
 *    character, or start with `?` are skipped, and a leading `#` is stripped
 *    from the first one.
 *
 * 2. It does NOT scroll when the element is already fully in the viewport.
 *    `isInViewport` requires all four edges to be inside, so a partially
 *    visible element still scrolls.
 *
 * 3. The FIRST match — the one from the initial page load — scrolls with
 *    `behavior: 'instant'` and no block alignment; every later `hashchange`
 *    scrolls `smooth` and `block: 'center'`. Landing on a deep link should not
 *    animate, following one on the page should.
 *
 * Upstream's `onMatch` callback becomes a `tedi:hash-match` DOM event on the
 * element (CONVENTIONS.md §7 item 2), so a consumer binds it with
 * `x-on:tedi:hash-match` or `wire:` rather than passing a function.
 *
 * Config keys:
 *   id            — the element id to match. Required; without it nothing runs.
 *   scrollOnMatch — false fires the event but never scrolls. Default true.
 */
export function hashTrigger(config = {}) {
    const { id = null, scrollOnMatch = true } = config;

    return {
        isInitial: true,
        onHashChange: null,

        init() {
            if (!id) return;

            this.onHashChange = () => this.handle();

            // Upstream runs the check once on mount before subscribing, so a
            // page loaded at a matching hash scrolls without waiting for an
            // event that has already fired.
            this.handle();
            this.isInitial = false;

            window.addEventListener('hashchange', this.onHashChange);
        },

        destroy() {
            if (this.onHashChange) {
                window.removeEventListener('hashchange', this.onHashChange);
            }
        },

        hashes() {
            return window.location.hash
                .split('/')
                .filter((part) => part.indexOf('?') !== 0 && part.length !== 1 && part.length !== 0)
                .map((part) => (part.charAt(0) === '#' ? part.substring(1) : part));
        },

        isInViewport(element) {
            const rect = element.getBoundingClientRect();

            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        },

        handle() {
            if (this.hashes().indexOf(id) === -1) return;

            this.$el.dispatchEvent(
                new CustomEvent('tedi:hash-match', { detail: { id }, bubbles: true })
            );

            const element = document.getElementById(id);

            if (scrollOnMatch && element && !this.isInViewport(element)) {
                element.scrollIntoView(
                    this.isInitial ? { behavior: 'instant' } : { behavior: 'smooth', block: 'center' }
                );
            }
        },
    };
}
