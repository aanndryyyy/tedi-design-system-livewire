/**
 * `tediSplitPane`: port of `SplitPane` in
 * react/src/community/components/map-components/split-pane/split-pane.tsx
 * (CONVENTIONS.md §13).
 *
 * - **Drag.** mousedown (default prevented) or touchstart on the separator
 *   starts it. Moves anywhere on the document set the ratio from the pointer's
 *   position in the container, clamped. mouseup / touchend ends it and
 *   dispatches `tedi:ratio-change`, also when nothing moved, as upstream's
 *   commitRatio does.
 * - **Keyboard** on the separator: the decrease/increase arrow (Left/Right when
 *   horizontal, Up/Down when vertical) moves 2, Home/End jump to the limits.
 *   Each handled key dispatches `tedi:ratio-change`, at the limits too.
 * - **State** is written imperatively by `_apply()`: both panes' `flex-grow`
 *   and the separator's `aria-valuenow`. A Livewire morph of an ancestor
 *   rewrites them to the server's starting values, so `morphed` re-applies the
 *   client ratio (CONVENTIONS.md §11).
 *
 * Events are dispatched from the root and bubble: `tedi:ratio-change`
 * `{ ratio }` (onRatioChange) and `tedi:close` (onClose).
 */

const KEYBOARD_STEP = 2;

export function splitPane(config) {
    const cfg = config || {};
    // Private state, kept off the Alpine object: consumer content inside the
    // panes is in this scope's merged data proxy (see dock-panel.js).
    const s = {};

    const clamp = (value) => Math.max(cfg.minRatio, Math.min(cfg.maxRatio, value));

    return {
        ratio: cfg.ratio,

        init() {
            s.root = this.$el;
            s.onMouseMove = (event) => this._fromPoint(event.clientX, event.clientY);
            s.onTouchMove = (event) => {
                const touch = event.touches[0];
                if (touch) this._fromPoint(touch.clientX, touch.clientY);
            };
            s.onEnd = () => {
                this._stopDrag();
                this._commit();
            };

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morphed', ({ el }) => {
                    if (el && s.root && el.contains(s.root)) this._apply();
                });
                s.offMorph = () => typeof off === 'function' && off();
            }
        },

        destroy() {
            this._stopDrag();
            s.offMorph?.();
            s.root = null;
        },

        /** Upstream's handleMouseDown (with an event) and handleTouchStart (without). */
        startDrag(event) {
            if (event) event.preventDefault();
            this._stopDrag();
            document.addEventListener('mousemove', s.onMouseMove);
            document.addEventListener('mouseup', s.onEnd);
            document.addEventListener('touchmove', s.onTouchMove);
            document.addEventListener('touchend', s.onEnd);
            s.dragging = true;
        },

        onKeydown(event) {
            const horizontal = cfg.direction === 'horizontal';
            const decreaseKey = horizontal ? 'ArrowLeft' : 'ArrowUp';
            const increaseKey = horizontal ? 'ArrowRight' : 'ArrowDown';

            let next = null;
            if (event.key === decreaseKey) next = this.ratio - KEYBOARD_STEP;
            else if (event.key === increaseKey) next = this.ratio + KEYBOARD_STEP;
            else if (event.key === 'Home') next = cfg.minRatio;
            else if (event.key === 'End') next = cfg.maxRatio;

            if (next === null) return;

            event.preventDefault();
            this.setRatio(next);
            this._commit();
        },

        /** Sets the ratio (%), clamped. Does not dispatch `tedi:ratio-change`. */
        setRatio(next) {
            this.ratio = clamp(next);
            this._apply();
        },

        close() {
            this._dispatch('tedi:close');
        },

        _fromPoint(x, y) {
            if (!s.root) return;
            const rect = s.root.getBoundingClientRect();
            const horizontal = cfg.direction === 'horizontal';
            const size = horizontal ? rect.width : rect.height;
            if (size <= 0) return;
            this.setRatio(horizontal ? ((x - rect.left) / size) * 100 : ((y - rect.top) / size) * 100);
        },

        _stopDrag() {
            document.removeEventListener('mousemove', s.onMouseMove);
            document.removeEventListener('mouseup', s.onEnd);
            document.removeEventListener('touchmove', s.onTouchMove);
            document.removeEventListener('touchend', s.onEnd);
            s.dragging = false;
        },

        _commit() {
            this._dispatch('tedi:ratio-change', { ratio: this.ratio });
        },

        _part(name) {
            return s.root ? s.root.querySelector(`:scope > [data-tedi-split-pane="${name}"], :scope > .tedi-split-pane__divider > [data-tedi-split-pane="${name}"]`) : null;
        },

        _apply() {
            const first = this._part('first');
            const second = this._part('second');
            const separator = this._part('separator');
            if (first) first.style.flexGrow = String(this.ratio);
            if (second) second.style.flexGrow = String(100 - this.ratio);
            if (separator) separator.setAttribute('aria-valuenow', String(Math.round(this.ratio)));
        },

        _dispatch(name, detail) {
            s.root?.dispatchEvent(new CustomEvent(name, { detail, bubbles: true }));
        },
    };
}
