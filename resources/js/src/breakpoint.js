// Breakpoint visibility — the shared behaviour behind `tedi:hide-at` and
// `tedi:show-at`.
//
// Port of `tedi/directives/hide-at/hide-at.directive.ts` and
// `tedi/directives/show-at/show-at.directive.ts`, which resolve against
// `tedi/services/breakpoint/breakpoint.service.ts`. Upstream subscribes to a
// CDK BreakpointObserver over every `(min-width: Nrem)` in BREAKPOINTS, derives
// the current breakpoint name from the largest one that matched, and then
// compares indices:
//
//   isBelowBreakpoint(bp) -> currentIndex <  targetIndex   (hideAt is VISIBLE)
//   isAboveBreakpoint(bp) -> currentIndex >= targetIndex   (showAt is VISIBLE)
//
// Because the current breakpoint is by construction the largest matching
// `(min-width: …)`, `currentIndex >= targetIndex` is exactly
// `(min-width: BREAKPOINTS[bp]rem)` matching, and the two predicates are exact
// complements. So both directives collapse to a single matchMedia query here,
// with `mode` choosing which side of it is visible. No index arithmetic is
// needed and none is done — the boundary is inclusive at the breakpoint value:
// `show-at="md"` is visible from 48rem up, `hide-at="md"` hidden from 48rem up.
//
// `xs` is 0rem, so `(min-width: 0rem)` always matches: `show-at="xs"` is always
// visible and `hide-at="xs"` always hidden. That mirrors upstream, where index 0
// makes `currentIndex >= 0` unconditionally true.
//
// Upstream has one state this does not: before the observer's first emission
// `_currentBreakpoint` is undefined and BOTH predicates return false, i.e. both
// directives hide. matchMedia answers synchronously, so there is no equivalent
// moment — see the header comment on the Blade components for the fallback when
// this bundle is not loaded at all.

/**
 * Grid breakpoints in rem, mirroring `$grid-breakpoints` in
 * `@tedi-design-system/core` (`variables/_bootstrap-variables.scss`) and
 * `BREAKPOINTS` in the Angular breakpoint service. rem — not px — so they
 * resolve against the browser's base font size, as upstream's do.
 */
export const BREAKPOINTS = {
    xs: 0,
    sm: 36,
    md: 48,
    lg: 62,
    xl: 75,
    xxl: 87.5,
};

/**
 * @param {object}  config
 * @param {string}  config.breakpoint  one of BREAKPOINTS' keys
 * @param {string}  config.mode        'hide' — hidden at and above the
 *                                     breakpoint; 'show' — visible at and above
 */
export function breakpoint({ breakpoint: name = 'md', mode = 'hide' } = {}) {
    return {
        matches: false,
        query: null,
        onChange: null,

        init() {
            const rem = BREAKPOINTS[name];

            // An unknown breakpoint name leaves `matches` false, so a `show-at`
            // stays hidden and a `hide-at` stays visible — the same content-is-
            // visible-by-default bias as the no-JS fallback.
            if (rem === undefined) {
                return;
            }

            this.query = window.matchMedia(`(min-width: ${rem}rem)`);
            this.onChange = () => {
                this.matches = this.query.matches;
            };

            this.onChange();
            this.query.addEventListener('change', this.onChange);
        },

        destroy() {
            if (this.query && this.onChange) {
                this.query.removeEventListener('change', this.onChange);
            }
        },

        get visible() {
            return mode === 'show' ? this.matches : ! this.matches;
        },
    };
}
