/**
 * TEDI × Livewire — optional behaviour bundle.
 *
 * Most interactive components in this package declare their behaviour inline
 * with Alpine (`x-data` / `x-on`) directly in their Blade template, so they need
 * no registration here. This file exists for behaviours that are too large to
 * inline, and is emitted by the @tediScripts directive.
 *
 * Alpine itself is NOT bundled: Livewire already ships it. If you use these
 * components without Livewire, include Alpine yourself before this file.
 */
(function () {
    'use strict';

    function register(Alpine) {
        // Carousel — index state plus prev/next, kept minimal so the markup and
        // class names stay identical to the Angular original.
        Alpine.data('tediCarousel', (slideCount = 0) => ({
            index: 0,
            count: slideCount,
            go(i) {
                if (this.count > 0) this.index = (i + this.count) % this.count;
            },
            next() { this.go(this.index + 1); },
            prev() { this.go(this.index - 1); },
            isActive(i) { return this.index === i; },
        }));
    }

    if (window.Alpine) {
        register(window.Alpine);
    } else {
        document.addEventListener('alpine:init', () => register(window.Alpine));
    }
})();
