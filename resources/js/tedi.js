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
 *   src/position.js           placement maths, no Alpine and no DOM writes
 *   src/overlay.js            tediOverlay — open/close, dismissal, positioning
 *   src/dropdown.js           tediDropdown — tediOverlay + the ARIA menu keyboard layer
 *   src/modal.js              tediModal
 *   src/carousel.js           tediCarousel
 *   src/table-of-contents.js  tediTableOfContents
 */
import { carousel } from './src/carousel.js';
import { dropdown } from './src/dropdown.js';
import { modal } from './src/modal.js';
import { overlay } from './src/overlay.js';
import { tableOfContents } from './src/table-of-contents.js';

function register(Alpine) {
    Alpine.data('tediCarousel', carousel);
    Alpine.data('tediOverlay', overlay);
    Alpine.data('tediDropdown', dropdown);
    Alpine.data('tediModal', modal);
    Alpine.data('tediTableOfContents', tableOfContents);
}

if (window.Alpine) {
    register(window.Alpine);
} else {
    document.addEventListener('alpine:init', () => register(window.Alpine));
}
