// Number field — the step buttons of `tedi:number-field`.
//
// Port of the client half of
// angular/src/tedi/components/form/number-field/number-field.component.ts:
//
//   - handleButtonClick(action): the value moves by ±step. Dispatching `input`
//     and `change` on the native input stands in for upstream's
//     `onChange(nextValue)`, so `wire:model` (any modifier) and `x-model`
//     follow the value.
//   - announceValue(): the new value, politely, through the root-level regions
//     of `announcer.js` (`numberField.quantityUpdated`, or its `.suffix` form
//     when the field has a suffix). Upstream uses CDK's LiveAnnouncer; this
//     package has one announcer and it is that one.
//   - decrementDisabled() / incrementDisabled() / isInvalid(): recomputed from
//     the live input on every `input` / `change` and after a step, so the
//     buttons, `tedi-number-field--invalid` and `aria-invalid` follow typing,
//     not just the server render.
//   - the input wrapper's `(click)="focus()"` is inline in the template.
//
// Everything is read off the input at the moment it is needed — value, `min`,
// `max`, `step`, `disabled` — so a Livewire morph that changes any of them is
// picked up without re-initialising.
//
// Two deliberate divergences:
//
//   - The step is CLAMPED to `min` / `max` in the direction of travel. Upstream
//     only disables the button at a bound, so a value that is not a multiple
//     of the step (min 0, step 5, value 3) steps past the bound to -2. Here it
//     stops at 0. A value already outside the range still steps towards it.
//   - The sum is rounded to the larger of the step's and the value's decimal
//     places, so 0.1 + 0.2 is 0.3, not 0.30000000000000004. Upstream adds raw
//     floats. `stepUp()` is not used: it snaps to the `min` / `value` step base
//     and refuses to move an out-of-range value, neither of which upstream does.
//
// After a Livewire morph the server render's button `disabled` attributes and
// `--invalid` class reflect the SERVER value, which can lag a deferred
// `wire:model`, so they are recomputed from the input (CONVENTIONS.md §11,
// "Client-set attributes survive a Livewire morph"). The consumer's own
// `invalid` prop is recovered from that server render: the wrapper is
// server-invalid while the server value is in range only when `invalid` was set.
//
// Config keys:
//   suffix  — the field's suffix, for the announcement. Optional.
//   labels  — { updated, updatedSuffix }: the translated strings with their
//             `:count` / `:suffix` placeholders left in.

import { announce, ensureRegions } from './announcer.js';

/** Decimal places of a finite number, exponent notation included. */
function decimals(number) {
    if (!Number.isFinite(number)) return 0;

    const [mantissa, exponent] = String(number).toLowerCase().split('e');
    const fraction = (mantissa.split('.')[1] || '').length;

    return Math.max(0, fraction - (Number(exponent) || 0));
}

/** A numeric attribute, or null when it is absent or not a number. */
function bound(input, name) {
    const raw = input.getAttribute(name);
    if (raw === null || raw.trim() === '') return null;

    const number = Number(raw);

    return Number.isFinite(number) ? number : null;
}

/** Upstream's model coerces NaN (an empty or half-typed field) to 0. */
function current(input) {
    return Number.isNaN(input.valueAsNumber) ? 0 : input.valueAsNumber;
}

export function numberField(config = {}) {
    const { suffix = null, labels = {} } = config;

    return {
        _offMorph: null,
        _forcedInvalid: false,
        _morphQueued: false,

        init() {
            ensureRegions();
            this._readServerInvalid();
            // A child's wire:model / x-model writes its value after this scope
            // inits, so the first sync waits a tick.
            this.$nextTick(() => this.sync());

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    // Once per morph: a second read after sync() has toggled
                    // the class would take our own class for the server's.
                    if (el && !this._morphQueued && (el === this.$el || this.$el.contains(el))) {
                        this._morphQueued = true;
                        queueMicrotask(() => {
                            this._morphQueued = false;
                            this._readServerInvalid();
                            this.sync();
                            // wire:model writes a server-changed value from an
                            // Alpine effect that lands after the morph.
                            setTimeout(() => this.sync());
                        });
                    }
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }
        },

        destroy() {
            this._offMorph?.();
            this._offMorph = null;
        },

        /** handleButtonClick(): -1 decrements, 1 increments. */
        stepBy(direction) {
            const input = this.$refs.input;
            if (!input || input.disabled || input.readOnly) return;

            const step = bound(input, 'step') ?? 1;
            const value = current(input);
            let next = Number((value + step * direction).toFixed(Math.max(decimals(step), decimals(value))));

            const min = bound(input, 'min');
            const max = bound(input, 'max');
            if (direction > 0 && max !== null && next > max) next = max;
            if (direction < 0 && min !== null && next < min) next = min;

            input.value = String(next);
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            this.sync();
            this.announceValue(next);
        },

        announceValue(value) {
            const template = suffix ? labels.updatedSuffix : labels.updated;
            const message = (template || ':count')
                .replace(':count', String(value))
                .replace(':suffix', suffix || '');

            announce(message, 'polite');
        },

        /** The computeds that depend on the value. */
        sync() {
            const input = this.$refs.input;
            if (!input) return;

            const value = current(input);
            const min = bound(input, 'min');
            const max = bound(input, 'max');
            const disabled = input.disabled;

            const outOfRange = (min !== null && value < min) || (max !== null && value > max);
            const invalid = this._forcedInvalid || outOfRange;

            if (this.$refs.decrement) this.$refs.decrement.disabled = disabled || (min !== null && value <= min);
            if (this.$refs.increment) this.$refs.increment.disabled = disabled || (max !== null && value >= max);

            this.$refs.field?.classList.toggle('tedi-number-field--invalid', invalid);
            input.setAttribute('aria-invalid', invalid ? 'true' : 'false');
        },

        /**
         * The `invalid` prop is not in the config, because the config is not
         * re-read after a morph. It is recovered from the server render
         * instead: invalid there while the server-rendered value is in range.
         */
        _readServerInvalid() {
            const input = this.$refs.input;
            const field = this.$refs.field;
            if (!input || !field) return;

            const raw = input.getAttribute('value');
            const value = raw === null || raw.trim() === '' || !Number.isFinite(Number(raw)) ? 0 : Number(raw);
            const min = bound(input, 'min');
            const max = bound(input, 'max');
            const outOfRange = (min !== null && value < min) || (max !== null && value > max);

            this._forcedInvalid = field.classList.contains('tedi-number-field--invalid') && !outOfRange;
        },
    };
}
