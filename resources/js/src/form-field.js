/**
 * tediFormField: the form field's clear button.
 *
 * Port of form-field.component.ts `showClearButton()` and `clear()`. Angular
 * reads the projected control through `contentChild(TEDI_FORM_FIELD_CONTROL)`
 * and recomputes the button as the value changes. Blade renders it once, so
 * without this behaviour the button keeps its server-rendered state: it never
 * appears after typing into an empty field, and clearing never hides it.
 *
 * The control is the field box's text input or textarea, the only controls
 * that register `reset` and therefore get a clear button (Tedi::fieldContext()).
 *
 * `clear()` mirrors `control.reset(); control.focus()`. It empties the native
 * control and fires `input` and `change`, so `wire:model` (any modifier) and
 * `x-model` see the reset the same way they see typing. Anything the consumer
 * bound through `clearAttributes` (for example `wire:click`) still runs as well.
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
            this._control = this.$el.querySelector(
                '.tedi-form-field__box input:not([type="hidden"]), .tedi-form-field__box textarea',
            );

            if (!this._control) {
                return;
            }

            this.sync();
            // A Livewire morph or a programmatic change fires neither event, so
            // the next render's server state covers those. Typing is what matters here.
            this._control.addEventListener('input', () => this.sync());
            this._control.addEventListener('change', () => this.sync());
        },

        sync() {
            const control = this._control;
            this.filled = control.value !== '';
            this.blocked = control.disabled || control.readOnly;
        },

        /** showClearButton(): filled, enabled and editable. */
        get showClear() {
            return this.filled && !this.blocked;
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
