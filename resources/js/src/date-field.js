// `tediDateField` — resolves `tedi:date-field`'s breakpoint-name `modal` on the
// client, so the open picker renders as the popover from that breakpoint up and
// as the date-field modal below it.
//
// Port of `modalEnabled()` in
// `src/tedi/components/form/date-field/date-field.component.ts`:
//
//   typeof m === "boolean" ? m : breakpointService.isBelowBreakpoint(m)()
//
// `isBelowBreakpoint(bp)` is exactly "`(min-width: BREAKPOINTS[bp]rem)` does not
// match" — see the derivation in `breakpoint.js`, whose BREAKPOINTS (core's
// `$grid-breakpoints`) this reuses. Only the breakpoint-name form needs this
// scope: `modal=true` renders the modal alone and `modal=false` the popover
// alone, both server-side.
//
// The scope holds viewport state only, never the open state. Open/closed is the
// server's `open` prop (the popover is `@if`d on it) and, inside the modal,
// `tediModal`'s own `open`, which the modal host drives off `modalActive` with an
// `x-effect`. So a Livewire morph — which keeps this scope — cannot leave a stale
// open flag behind.
//
// Before Alpine runs, `modalActive` is unknown and the server markup shows the
// popover with the modal closed: upstream's own state before the breakpoint
// observer's first emission, when `isBelowBreakpoint` returns false.
import { BREAKPOINTS } from './breakpoint.js';

/**
 * @param {object} config
 * @param {string} config.modal  breakpoint name: the modal is used below it
 */
export function dateField({ modal: name = 'md' } = {}) {
    return {
        modalActive: false,
        _modalQuery: null,
        _onModalQuery: null,

        init() {
            const rem = BREAKPOINTS[name];

            // An unknown name keeps the popover, as an unresolved upstream
            // breakpoint does.
            if (rem === undefined) {
                return;
            }

            this._modalQuery = window.matchMedia(`(min-width: ${rem}rem)`);
            this._onModalQuery = () => {
                this.modalActive = ! this._modalQuery.matches;
            };

            this._onModalQuery();
            this._modalQuery.addEventListener('change', this._onModalQuery);
        },

        destroy() {
            if (this._modalQuery && this._onModalQuery) {
                this._modalQuery.removeEventListener('change', this._onModalQuery);
            }
        },
    };
}
