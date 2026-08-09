// Carousel — index state plus prev/next, kept minimal so the markup and
// class names stay identical to the Angular original.
//
// Small enough to have lived inline in `register()` before the split; it is a
// file here only so that every `Alpine.data()` factory is findable in one
// place. KNOWN GAP: upstream's `carousel-content.component.ts` also handles
// ArrowLeft/ArrowRight/Home/End/PageUp/PageDown on the `role="region"`
// element, which this does not — see the note in carousel-content.blade.php.
export function carousel(slideCount = 0) {
    return {
        index: 0,
        count: slideCount,
        go(i) {
            if (this.count > 0) this.index = (i + this.count) % this.count;
        },
        next() { this.go(this.index + 1); },
        prev() { this.go(this.index - 1); },
        isActive(i) { return this.index === i; },
    };
}
