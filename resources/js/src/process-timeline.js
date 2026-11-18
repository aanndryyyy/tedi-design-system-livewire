/**
 * tediProcessTimeline — a horizontal process timeline falls back to vertical
 * while its stages don't fit the container's width.
 *
 * Port of the overflow half of
 * community/components/helpers/process-timeline/process-timeline.component.ts.
 * Upstream's `vertical` is `resolvedOrientation() === 'vertical' ||
 * overflowing()`. The breakpoint half of `resolvedOrientation` is not ported
 * (CONVENTIONS.md §7.1), so `tedi:process-timeline` registers this behaviour
 * only for `orientation="horizontal"`. A vertical timeline never overflows and
 * upstream does not measure it either.
 *
 * Kept from upstream:
 *
 *   checkFit()         overflowing = horizontalWidth() > clientWidth
 *   horizontalWidth()  measured in the HORIZONTAL layout every time. While the
 *                      fallback is on, the root and item classes are swapped
 *                      to horizontal for one synchronous `scrollWidth` read
 *                      and restored before the browser paints. The result
 *                      depends only on content and container, never on the
 *                      current layout, so it cannot flip back and forth.
 *   ResizeObserver     on the root and each of its children
 *   MutationObserver   childList / subtree / characterData only, so the class
 *                      writes below (and the measurement swap) never retrigger
 *                      it. A change re-observes the children, as upstream's
 *                      `afterRenderEffect` does when `items()` changes.
 *
 * The one difference is where the layout lands. Upstream's items read
 * `vertical()` from the PROCESS_TIMELINE token and re-render. Blade items are
 * server-rendered with the timeline's `orientation`, so `apply()` writes the
 * resolved layout onto the markup directly: the root's `--vertical` /
 * `--horizontal`, each direct item's `--vertical`, and each connector's
 * separator axis class plus the `width` / `height` inline style that
 * `tedi:separator` derives from its axis.
 *
 * Those are client-set attributes on server markup, so a Livewire morph puts
 * the server's (horizontal) ones back. `apply()` re-runs after every morph of
 * the timeline or anything inside it (CONVENTIONS.md §11).
 */

const VERTICAL_CLASS = 'tedi-process-timeline--vertical';
const HORIZONTAL_CLASS = 'tedi-process-timeline--horizontal';
const ITEM_CLASS = 'tedi-process-timeline-item';
const ITEM_VERTICAL_CLASS = 'tedi-process-timeline-item--vertical';

export function processTimeline() {
    return {
        overflowing: false,
        _el: null,
        _resize: null,
        _mutation: null,
        _offMorph: null,

        init() {
            this._el = this.$el;

            if (typeof ResizeObserver === 'undefined') {
                return;
            }

            this._resize = new ResizeObserver(() => this.checkFit());
            this._mutation = typeof MutationObserver === 'undefined'
                ? null
                : new MutationObserver(() => this.observe());
            this._mutation?.observe(this._el, { childList: true, subtree: true, characterData: true });

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    if (this._el && (el === this._el || this._el.contains(el))) {
                        queueMicrotask(() => this.apply());
                    }
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }

            this.observe();
        },

        destroy() {
            this._resize?.disconnect();
            this._mutation?.disconnect();
            this._offMorph?.();
            this._el = null;
        },

        /** (Re-)observe the root and its current children, then re-check. */
        observe() {
            if (!this._el) {
                return;
            }

            this._resize.disconnect();
            this._resize.observe(this._el);
            Array.from(this._el.children).forEach((child) => this._resize.observe(child));
            this.checkFit();
        },

        checkFit() {
            if (!this._el) {
                return;
            }

            this.overflowing = this.horizontalWidth() > this._el.clientWidth;
            this.apply();
        },

        horizontalWidth() {
            const el = this._el;

            if (!el.classList.contains(VERTICAL_CLASS)) {
                return el.scrollWidth;
            }

            const items = Array.from(el.querySelectorAll(`:scope > .${ITEM_VERTICAL_CLASS}`));
            el.classList.replace(VERTICAL_CLASS, HORIZONTAL_CLASS);
            items.forEach((item) => item.classList.remove(ITEM_VERTICAL_CLASS));

            const width = el.scrollWidth;

            el.classList.replace(HORIZONTAL_CLASS, VERTICAL_CLASS);
            items.forEach((item) => item.classList.add(ITEM_VERTICAL_CLASS));

            return width;
        },

        /** Write the resolved layout onto the server-rendered markup. Idempotent. */
        apply() {
            const el = this._el;
            if (!el) {
                return;
            }

            const vertical = this.overflowing;

            el.classList.toggle(VERTICAL_CLASS, vertical);
            el.classList.toggle(HORIZONTAL_CLASS, !vertical);

            el.querySelectorAll(`:scope > .${ITEM_CLASS}`).forEach((item) => {
                item.classList.toggle(ITEM_VERTICAL_CLASS, vertical);

                const separator = item.querySelector(':scope > .tedi-process-timeline-item__connector > .tedi-separator');
                if (separator) {
                    separator.classList.toggle('tedi-separator--vertical', vertical);
                    separator.classList.toggle('tedi-separator--horizontal', !vertical);
                    separator.style.width = vertical ? '0px' : '100%';
                    separator.style.height = vertical ? '100%' : '0px';
                }
            });
        },
    };
}
