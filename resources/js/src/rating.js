/**
 * tediRating — the rating's hover preview and live selection state.
 *
 * A port of the client-side half of
 * angular/src/tedi/components/form/rating/rating.component.ts: the
 * `hoverValue` signal, `setHover()`, and the `items()` / `starCaption()`
 * computeds that turn the committed value and the hovered position into the
 * `tedi-rating__visual--selected` / `--hover` classes, the star glyph's
 * filled/outlined variant and the star caption text.
 *
 * The radios are native, so keyboard selection, focus and the form value are
 * the browser's (and `wire:model`'s / `x-model`'s): upstream adds no key
 * handling either. This behaviour never writes the value — it only READS the
 * checked radio, on init, after a form `reset`, and after a Livewire morph
 * (a `change` hands it the picked position directly)
 * of anything inside the rating. The server render already carries the right
 * classes, so without this bundle only the hover preview and the live
 * re-colouring after a pick are missing (CSS still paints `:hover`).
 *
 * State names are prefixed (`ratingValue`, `ratingHover`) on purpose: a
 * consumer's `x-model="value"` on the radios is evaluated inside this scope,
 * and a bare `value` here would capture it.
 *
 * Config keys:
 *   type     — 'star' | 'number' | 'icon'. `icon` is single-choice; the
 *              other two fill cumulatively.
 *   value    — the server-rendered value; replaced by the checked radio on init.
 *   labels   — item labels, for the star caption. Empty: no caption.
 */
export function rating(config = {}) {
    const { type = 'star', value = 0, labels = [] } = config;

    return {
        ratingValue: Number(value) || 0,
        ratingHover: null,
        _offMorph: null,
        _onReset: null,

        init() {
            this.ratingSync();
            // A child's x-model applies its value after this scope inits.
            this.$nextTick(() => this.ratingSync());

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    if (el && this.$el.contains(el)) this.$nextTick(() => this.ratingSync());
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }

            // A native form reset restores the server-rendered `checked`
            // attribute without firing `change`, so re-read after it lands.
            const form = this.$el.closest('form');
            if (form) {
                this._onReset = () => setTimeout(() => this.ratingSync());
                form.addEventListener('reset', this._onReset);
            }
        },

        destroy() {
            if (this._offMorph) this._offMorph();
            const form = this.$el.closest('form');
            if (form && this._onReset) form.removeEventListener('reset', this._onReset);
        },

        /**
         * Read the committed value off the checked radio. With none checked,
         * fall back to the server-rendered value: a fractional one (3.5)
         * checks no radio but still fills three stars, as upstream does.
         */
        ratingSync() {
            const checked = this.$el.querySelector('.tedi-rating__input:checked');
            this.ratingValue = checked ? Number(checked.value) : Number(value) || 0;
            // Upstream's effect: a preview cannot outlive interactivity.
            if (!this.ratingInteractive()) this.ratingHover = null;
        },

        /** `!disabled && !readOnly` — read off the DOM so a morph is picked up. */
        ratingInteractive() {
            return (
                !this.$el.classList.contains('tedi-rating--disabled') &&
                !this.$el.classList.contains('tedi-rating--readonly')
            );
        },

        ratingSelect(position) {
            if (!this.ratingInteractive()) return;
            this.ratingValue = position;
        },

        ratingSetHover(position) {
            this.ratingHover = this.ratingInteractive() ? position : null;
        },

        ratingInRange(position, target) {
            return type === 'icon' ? position === target : position <= target;
        },

        ratingHovering() {
            return this.ratingInteractive() && this.ratingHover !== null;
        },

        ratingPreviewed(position) {
            return this.ratingHovering() && this.ratingInRange(position, this.ratingHover);
        },

        // While hovering, items in the hovered range take the hover look; on
        // cumulative scales items past it read as unselected, while the
        // single-choice icon scale keeps its current pick visible.
        ratingSelected(position) {
            return (
                this.ratingInRange(position, this.ratingValue) &&
                (!this.ratingHovering() || this.ratingPreviewed(position) || type === 'icon')
            );
        },

        ratingStarCaption() {
            const display = this.ratingHovering() ? this.ratingHover : this.ratingValue;
            return display > 0 ? (labels[display - 1] ?? '') : '';
        },
    };
}
