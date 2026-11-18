/**
 * TEDI × Livewire — optional behaviour bundle. ENTRY POINT.
 *
 * Most interactive components in this package declare their behaviour inline
 * with Alpine (`x-data` / `x-on`) directly in their Blade template, so they need
 * no registration here. This file exists for behaviours that are too large to
 * inline, and is emitted by the @tediScripts directive.
 *
 * Alpine itself is NOT bundled: Livewire already ships it. If you use these
 * components without Livewire, include Alpine yourself before this file.
 *
 * This file is the entry point only — every behaviour lives in `src/`, and
 * `npm run build:js` bundles them with esbuild into a single classic
 * (non-module) `dist/tedi.js`. Consumers still load one plain <script>; the
 * split is a source-organisation change and nothing more. See CONVENTIONS.md §8.
 *
 *   src/breakpoint.js         tediBreakpoint — matchMedia visibility (hide-at / show-at)
 *   src/position.js           placement maths, no Alpine and no DOM writes
 *   src/overlay.js            tediOverlay — open/close, dismissal, positioning
 *   src/dropdown.js           tediDropdown — tediOverlay + the ARIA menu keyboard layer
 *   src/filter.js             tediFilter — tediOverlay + the filter's listbox layer
 *   src/form-field.js         tediFormField — the clear button's live visibility and reset
 *   src/file-upload.js        tediFileUpload — picker, label naming and announcements for file-upload
 *   src/announcer.js          root-level screen-reader announcements, no Alpine (file-upload)
 *   src/progress-bar.js       tediProgressBar — opt-in throttled value announcements
 *   src/multiselect.js        tediMultiselect — tediOverlay + the multiselect's listbox layer
 *   src/search.js             tediSearch — tediOverlay + the search autocomplete's combobox layer
 *   src/modal.js              tediModal
 *   src/carousel.js           tediCarousel
 *   src/table-of-contents.js  tediTableOfContents, tediTableOfContentsCollapsible
 *   src/scroll-visibility.js  tediScrollVisibility — hide/reveal past a scroll threshold
 *   src/hash-trigger.js       tediHashTrigger — scroll to the element the URL hash names
 *   src/sheet.js              tediSheet — the React bottom sheet: open/close, focus, drag and snap points
 *   src/process-timeline.js   tediProcessTimeline — horizontal process timeline falls back to vertical when it overflows
 *   src/rating.js             tediRating — the rating's hover preview and live selected/caption state
 *   src/date-field.js         tediDateField — resolves a breakpoint-name date-field `modal` (popover vs modal)
 *   src/toast-container.js    tediToastContainer + window.TediToast — ToastService: spawn, time, pause and announce toasts
 */
import { breakpoint } from './src/breakpoint.js';
import { carousel } from './src/carousel.js';
import { dateField } from './src/date-field.js';
import { dropdown } from './src/dropdown.js';
import { fileUpload } from './src/file-upload.js';
import { filter } from './src/filter.js';
import { formField } from './src/form-field.js';
import { hashTrigger } from './src/hash-trigger.js';
import { modal } from './src/modal.js';
import { multiselect } from './src/multiselect.js';
import { overlay } from './src/overlay.js';
import { processTimeline } from './src/process-timeline.js';
import { progressBar } from './src/progress-bar.js';
import { rating } from './src/rating.js';
import { scrollVisibility } from './src/scroll-visibility.js';
import { sheet } from './src/sheet.js';
import { search } from './src/search.js';
import { tableOfContents, tableOfContentsCollapsible } from './src/table-of-contents.js';
import { toastContainer } from './src/toast-container.js';

function register(Alpine) {
    Alpine.data('tediBreakpoint', breakpoint);
    Alpine.data('tediCarousel', carousel);
    Alpine.data('tediOverlay', overlay);
    Alpine.data('tediDropdown', dropdown);
    Alpine.data('tediFilter', filter);
    Alpine.data('tediFormField', formField);
    Alpine.data('tediFileUpload', fileUpload);
    Alpine.data('tediMultiselect', multiselect);
    Alpine.data('tediSearch', search);
    Alpine.data('tediModal', modal);
    Alpine.data('tediTableOfContents', tableOfContents);
    Alpine.data('tediTableOfContentsCollapsible', tableOfContentsCollapsible);
    Alpine.data('tediScrollVisibility', scrollVisibility);
    Alpine.data('tediHashTrigger', hashTrigger);
    Alpine.data('tediSheet', sheet);
    Alpine.data('tediProgressBar', progressBar);
    Alpine.data('tediProcessTimeline', processTimeline);
    Alpine.data('tediRating', rating);
    Alpine.data('tediDateField', dateField);
    Alpine.data('tediToastContainer', toastContainer);
}

if (window.Alpine) {
    register(window.Alpine);
} else {
    document.addEventListener('alpine:init', () => register(window.Alpine));
}
