/**
 * tediFormField: the form field's clear button.
 *
 * Port of form-field.component.ts `showClearButton()` and `clear()`. Angular
 * reads the projected control through `contentChild(TEDI_FORM_FIELD_CONTROL)`
 * and recomputes the button as the value changes. Blade renders it once, so
 * without this behaviour the button keeps its server-rendered state: it never
 * appears after typing into an empty field, and clearing never hides it.
 *
 * The scope sits on `.tedi-form-field__buttons`, a SIBLING of the slotted
 * control, never on an ancestor of it. An `x-data` around the slot would
 * become the closest Alpine root of everything in it, and Alpine registers an
 * `x-ref` on its closest root. A composing component's own refs (search's
 * `x-ref="trigger"` on the input) would then be invisible to that component.
 * The root modifier `--clear-on-interaction` is toggled from here via `x-effect`.
 *
 * The control is the field box's text input or textarea, the only controls
 * that register `reset` and therefore get a clear button (Tedi::fieldContext()).
 *
 * `clear()` mirrors `control.reset(); control.focus()`. It empties the native
 * control and fires `input` and `change`, so `wire:model` (any modifier) and
 * `x-model` see the reset the same way they see typing.
 *
 * Re-sync: typing fires `input`. A server-side change (Livewire resetting the
 * property) fires nothing, and the bound attributes would re-apply the stale
 * state over the fresh server markup. So the state is also re-read after every
 * Livewire morph of the control.
 *
 * Config:
 *   clearOnInteraction: boolean. Mirrors `showClearOnInteraction`. The CSS hides
 *                       the button until hover or focus, keyed on the root modifier.
 */
export function formField({ clearOnInteraction = false } = {}) {
    return {
        clearOnInteraction,
        filled: false,
        blocked: false,

        init() {
            this._root = this.$el.closest('.tedi-form-field');
            this._control = this.$el
                .closest('.tedi-form-field__box')
                ?.querySelector('input:not([type="hidden"]), textarea');

            if (!this._control) {
                return;
            }

            this._sync = () => this.sync();
            this.sync();
            this._control.addEventListener('input', this._sync);
            this._control.addEventListener('change', this._sync);

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    if (this._control && (el === this._control || el.contains?.(this._control))) {
                        queueMicrotask(this._sync);
                    }
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }
        },

        destroy() {
            this._control?.removeEventListener('input', this._sync);
            this._control?.removeEventListener('change', this._sync);
            this._offMorph?.();
            this._control = null;
        },

        sync() {
            const control = this._control;
            if (!control) {
                return;
            }
            this.filled = control.value !== '';
            this.blocked = control.disabled || control.readOnly;
        },

        /** showClearButton(): filled, enabled and editable. */
        get showClear() {
            return this.filled && !this.blocked;
        },

        /** The root's `--clear-on-interaction` modifier, kept in step with showClear. */
        syncRoot() {
            this._root?.classList.toggle(
                'tedi-form-field--clear-on-interaction',
                this.clearOnInteraction && this.showClear,
            );
        },

        clear() {
            const control = this._control;

            if (!control || this.blocked) {
                return;
            }

            control.value = '';
            control.dispatchEvent(new Event('input', { bubbles: true }));
            control.dispatchEvent(new Event('change', { bubbles: true }));
            control.focus();
            this.sync();
        },
    };
}
