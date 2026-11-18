/**
 * `tediResizer`: port of `Resizer` in
 * react/src/community/components/map-components/resizer/resizer.tsx
 * (CONVENTIONS.md §13).
 *
 * Mouse only, as upstream: mousedown on the handle, then mousemove / mouseup
 * on the document. Upstream keeps the document listeners for the component's
 * life and ignores moves while not resizing. Here they are added on mousedown
 * and removed on mouseup, which behaves the same.
 *
 * The size is client state, written to the wrapper's inline `width` / `height`
 * exactly as upstream's style prop writes it. A Livewire morph of an ancestor
 * rewrites that style attribute to the server's, so `morphed` re-applies it
 * (CONVENTIONS.md §11).
 *
 * `configure()` and `size()` are for `tediDockPanel`, whose resizable mode
 * renders this component and re-initialises it the way upstream remounts it
 * with a new `key`.
 */

export function resizer(config) {
    const cfg = Object.assign({}, config || {});
    // Private state, kept off the Alpine object (see dock-panel.js).
    const s = {};

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

    return {
        init() {
            s.root = this.$el;
            s.width = cfg.width;
            s.height = cfg.height;

            s.onMove = (event) => this._move(event);
            s.onUp = () => this._end();

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morphed', ({ el }) => {
                    if (el && s.root && el.contains(s.root)) this._applySize();
                });
                s.offMorph = () => typeof off === 'function' && off();
            }
        },

        destroy() {
            this._end();
            s.offMorph?.();
            s.root = null;
        },

        /** Upstream's handleMouseDown. */
        start(event) {
            event.preventDefault();
            s.resizing = true;
            document.body.style.userSelect = 'none';
            s.start = { x: event.clientX, y: event.clientY, width: s.width, height: s.height };
            document.addEventListener('mousemove', s.onMove);
            document.addEventListener('mouseup', s.onUp);
        },

        /** Current size in pixels: `{ width, height }`. */
        size() {
            return { width: s.width, height: s.height };
        },

        /**
         * New limits and, optionally, a new starting size: upstream's
         * re-render with new props, or its remount with a new `key`.
         */
        configure(next) {
            ['minWidth', 'maxWidth', 'minHeight', 'maxHeight', 'handlePosition'].forEach((key) => {
                if (next[key] !== undefined) cfg[key] = next[key];
            });
            if (next.width !== undefined) s.width = next.width;
            if (next.height !== undefined) s.height = next.height;
            this._applySize();
        },

        _move(event) {
            if (!s.resizing || !s.root) return;

            event.preventDefault();
            const dx = event.clientX - s.start.x;
            const dy = event.clientY - s.start.y;
            const position = cfg.handlePosition;

            if (position === 'right') {
                s.width = clamp(s.start.width + dx, cfg.minWidth, cfg.maxWidth);
            } else if (position === 'left') {
                s.width = clamp(s.start.width - dx, cfg.minWidth, cfg.maxWidth);
            } else if (position === 'bottom') {
                s.height = clamp(s.start.height + dy, cfg.minHeight, cfg.maxHeight);
            } else if (position === 'top') {
                s.height = clamp(s.start.height - dy, cfg.minHeight, cfg.maxHeight);
            }

            this._applySize();
        },

        _end() {
            document.removeEventListener('mousemove', s.onMove);
            document.removeEventListener('mouseup', s.onUp);
            if (!s.resizing) return;
            s.resizing = false;
            document.body.style.userSelect = '';
        },

        _applySize() {
            if (!s.root) return;
            const vertical = cfg.handlePosition === 'top' || cfg.handlePosition === 'bottom';
            s.root.style.width = `${s.width}px`;
            s.root.style.height = vertical ? `${s.height}px` : 'auto';
        },
    };
}
