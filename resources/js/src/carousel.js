// Carousel — the track position, prev/next, bounded navigation and swipe,
// ported from upstream's `carousel-content.component.ts`.
//
// `tediCarousel` sits on `tedi-carousel`; `tedi:carousel-content` calls
// `configure()` from its own `x-init` with the inputs that live there (`loop`,
// `slidesPerView`, `gap`, `swipeThreshold`), and registers its root as the
// viewport the pointer maths measures against. Indicators and navigation read
// `canPrev` / `canNext` / `positionCount` / `activePosition` from this scope.
//
// What is ported verbatim from upstream:
//   - bounded mode (`loop: false`): `maxIndex`, the fractional final stop that
//     shows the last slide fully, `positionCount` / `activePosition`, and
//     `canPrev` / `canNext` (the arrows disable at the ends);
//   - the swipe: `swipeTarget()`, `adjacentStop()`, `isFlick()` and their
//     constants (EPSILON, FLICK_VELOCITY, FLICK_WINDOW, MIN_SWIPE_DISTANCE,
//     MIN_MOUSE_SWIPE_DISTANCE), `pointercancel` / `lostpointercapture`
//     returning to the start, and the release reading `pointerup`'s position;
//   - the keyboard: ArrowRight/PageDown, ArrowLeft/PageUp, Home, End.
//
// Pointer details that keep links and buttons inside slides usable: the
// viewport captures the pointer only once a drag passes `minSwipeDistance`
// (capturing on pointerdown retargets a plain click to the viewport in
// Chromium); a release after such a drag swallows the click it produces
// (`clickCapture`, bound in the capture phase); and the viewport prevents
// `dragstart`, so dragging a link or image swipes instead of starting a
// native drag, whose `pointercancel` would snap the track back.
//
// What is not: the clone buffer upstream renders around a looping track (so a
// looping track here rewinds from the last slide to the first rather than
// continuing seamlessly, and cannot be dragged past either end), the wheel
// handler, per-breakpoint `slidesPerView` / `gap`, and the LiveAnnouncer
// slide announcement. See carousel.blade.php.

/** Ignore tiny calculation differences when deciding the carousel's position. */
const EPSILON = 0.001;

/** Drag speed, in slides per ms, that counts as a flick. */
const FLICK_VELOCITY = 0.0015;

/** A flick's speed is measured over this many ms before release. */
const FLICK_WINDOW = 100;

/** Drags shorter than this (px) never move the carousel, so taps and jitter stay put. */
const MIN_SWIPE_DISTANCE = 10;

/** A mouse holds still while clicking, so a shorter drag is already deliberate. */
const MIN_MOUSE_SWIPE_DISTANCE = 4;

export function carousel(slideCount = 0) {
    return {
        /** Track position, in slides. Fractional while dragging, and at a bounded track's final stop. */
        index: 0,
        count: slideCount,
        loop: true,
        slidesPerView: 1,
        gap: 16,
        swipeThreshold: 0.3,
        transitionMs: 400,
        /** False while a pointer drags the track, so it follows without easing. */
        animate: true,
        dragging: false,
        locked: false,
        viewport: null,
        startX: 0,
        startIndex: 0,
        lastX: 0,
        lastTime: 0,
        samples: [],
        minSwipeDistance: MIN_SWIPE_DISTANCE,
        /** The dragging pointer, and whether the viewport has captured it yet. */
        pointerId: null,
        captured: false,
        /** Set by a release that was a swipe, so the click it produces is swallowed. */
        suppressClick: false,

        configure(options = {}) {
            Object.assign(this, options);
            this.swipeThreshold = Math.min(Math.max(Number(this.swipeThreshold) || 0, 0), 1);
            this.clampIntoRange();
        },

        /** Furthest track position in bounded mode; may be fractional to show the last slide fully. */
        get maxIndex() {
            return Math.max(0, this.count - this.slidesPerView);
        },

        /** Number of indicator positions: one per slide when looping, or one per reachable stop when bounded. */
        get positionCount() {
            if (this.loop || !this.count) return this.count;
            return Math.ceil(this.maxIndex - EPSILON) + 1;
        },

        /** Index of the leftmost visible slide, skipping a partially visible one in bounded mode. */
        get slideIndex() {
            if (!this.count) return 0;
            if (!this.loop) {
                return Math.min(Math.max(Math.ceil(this.index - EPSILON), 0), this.count - 1);
            }
            const i = Math.floor(this.index);
            return ((i % this.count) + this.count) % this.count;
        },

        /** Index of the active indicator; a fractional final stop gets its own index. */
        get activePosition() {
            if (this.loop) return this.slideIndex;
            const nearest = this.snap(this.index);
            return nearest >= this.maxIndex - EPSILON ? this.positionCount - 1 : Math.round(nearest);
        },

        get canPrev() {
            return this.loop || this.index > EPSILON;
        },

        get canNext() {
            return this.loop || this.index < this.maxIndex - EPSILON;
        },

        isActive(i) {
            return this.activePosition === i;
        },

        trackStyle() {
            const transition = this.animate ? `transform ${this.transitionMs}ms ease` : 'none';
            return `gap: ${this.gap}px; transform: translate3d(calc(-1 * ${this.index} * (100% + ${this.gap}px) / ${this.slidesPerView}), 0, 0); transition: ${transition};`;
        },

        next() {
            if (!this.count || this.locked || !this.canNext) return;
            this.moveTo(this.clampIndex(Math.floor(this.index + EPSILON) + 1));
        },

        prev() {
            if (!this.count || this.locked || !this.canPrev) return;
            this.moveTo(this.clampIndex(Math.ceil(this.index - EPSILON) - 1));
        },

        go(i) {
            if (!this.count) return;
            this.moveTo(this.loop ? i : this.clampIndex(i));
        },

        /** Settles the track on `target`, wrapping it into range when looping (no clone buffer here). */
        moveTo(target) {
            this.animate = true;
            this.index = this.loop ? ((target % this.count) + this.count) % this.count : target;
            this.locked = true;
            setTimeout(() => (this.locked = false), this.transitionMs);
        },

        keydown(event) {
            switch (event.key) {
                case 'ArrowRight':
                case 'PageDown':
                    event.preventDefault();
                    this.next();
                    break;
                case 'ArrowLeft':
                case 'PageUp':
                    event.preventDefault();
                    this.prev();
                    break;
                case 'Home':
                    event.preventDefault();
                    this.go(0);
                    break;
                case 'End':
                    event.preventDefault();
                    this.go(this.count - 1);
                    break;
                default:
                    break;
            }
        },

        cellWidth() {
            const width = this.viewport ? this.viewport.getBoundingClientRect().width : 0;
            return (width - this.gap * (this.slidesPerView - 1)) / this.slidesPerView + this.gap;
        },

        pointerDown(ev) {
            if (!this.count) return;
            // No pointer capture yet: in Chromium a captured pointer's click is
            // dispatched to the viewport, so capturing here would stop a plain
            // click from ever reaching a link or button inside a slide.
            // `pointerMove` captures once the drag passes `minSwipeDistance`.
            this.pointerId = ev.pointerId;
            this.captured = false;
            this.suppressClick = false;
            this.dragging = true;
            this.animate = false;
            this.startX = ev.clientX;
            this.startIndex = this.index;
            this.lastX = ev.clientX;
            this.lastTime = ev.timeStamp;
            this.samples = [{ x: ev.clientX, t: ev.timeStamp }];
            this.minSwipeDistance = ev.pointerType === 'mouse' ? MIN_MOUSE_SWIPE_DISTANCE : MIN_SWIPE_DISTANCE;
        },

        pointerMove(ev) {
            if (!this.dragging || ev.pointerId !== this.pointerId) return;

            // Uncaptured, a mouse released outside the viewport sends no pointerup here.
            if (!this.captured && ev.pointerType === 'mouse' && ev.buttons === 0) {
                this.pointerCancel();
                return;
            }

            const dx = ev.clientX - this.startX;

            if (!this.captured && Math.abs(dx) >= this.minSwipeDistance) {
                this.captured = true;
                if (this.viewport && this.viewport.setPointerCapture) {
                    try {
                        this.viewport.setPointerCapture(ev.pointerId);
                    } catch (e) {
                        // The pointer is already gone; pointerup/cancel ends the drag.
                    }
                }
            }

            this.lastX = ev.clientX;
            this.lastTime = ev.timeStamp;
            this.samples.push({ x: ev.clientX, t: ev.timeStamp });
            while (this.samples[0].t < ev.timeStamp - FLICK_WINDOW) {
                this.samples.shift();
            }

            const cellWidth = this.cellWidth();
            if (!cellWidth) return;

            const { min, max } = this.dragBounds();
            this.index = Math.min(Math.max(this.startIndex - dx / cellWidth, min), max);
        },

        /**
         * The browser ended the drag, e.g. to scroll the page, or capture was lost
         * mid-drag: not a swipe, so return to where it started. After a normal
         * release this is a no-op, as `lostpointercapture` follows `pointerup`.
         */
        pointerCancel() {
            if (!this.dragging) return;
            this.dragging = false;
            this.animate = true;
            this.index = this.snap(this.startIndex);
        },

        pointerUp(ev) {
            if (!this.dragging || (ev && ev.pointerId !== undefined && ev.pointerId !== this.pointerId)) return;

            // The pointer can move after the last pointermove; the release reports where it ended.
            if (ev && typeof ev.clientX === 'number') {
                this.pointerMove(ev);
            }

            this.dragging = false;

            // A swipe is not a click: the release over a link or button inside
            // a slide must not activate it. `clickCapture` swallows the click
            // this release is about to produce; the timer clears the flag when
            // none follows (touch, or a release outside the viewport).
            if (Math.abs(this.lastX - this.startX) >= this.minSwipeDistance) {
                this.suppressClick = true;
                setTimeout(() => (this.suppressClick = false), 0);
            }

            const target = this.swipeTarget(ev && ev.timeStamp !== undefined ? ev.timeStamp : this.lastTime);
            if (Math.abs(target - this.index) < EPSILON && Math.abs(target - this.startIndex) < EPSILON) {
                this.animate = true;
                this.index = target;
                return;
            }
            this.moveTo(target);
        },

        /** Capture-phase click on the viewport: swallow the click a swipe's release produced. */
        clickCapture(ev) {
            if (!this.suppressClick) return;
            this.suppressClick = false;
            ev.preventDefault();
            ev.stopPropagation();
        },

        /**
         * Where a released drag settles. Walks stop position to stop position in the
         * drag direction: each gap fully dragged moves on, and a partly dragged gap
         * moves on once it reaches `swipeThreshold` of that gap (a bounded track's
         * final gap can be short). A flick that did not move on otherwise moves one
         * position.
         */
        swipeTarget(releasedAt) {
            const dx = this.lastX - this.startX;

            if (Math.abs(dx) < this.minSwipeDistance) {
                return this.snap(this.startIndex);
            }

            // Take the direction from the pointer: at a bound the clamped track does not move.
            const forward = dx < 0;
            let remaining = Math.abs(this.index - this.startIndex);
            let position = this.startIndex;
            let moved = false;

            for (;;) {
                const next = this.adjacentStop(position, forward);
                const gap = Math.abs(next - position);

                if (gap < EPSILON) break;

                if (remaining >= gap - EPSILON) {
                    position = next;
                    remaining -= gap;
                    moved = true;
                    continue;
                }

                if (remaining > EPSILON && remaining >= gap * this.swipeThreshold - EPSILON) {
                    position = next;
                    moved = true;
                }

                break;
            }

            if (!moved && this.isFlick(releasedAt)) {
                return this.adjacentStop(this.startIndex, forward);
            }

            return moved ? position : this.snap(this.startIndex);
        },

        /** The next stop position after `position` in the given direction (itself at a bound). */
        adjacentStop(position, forward) {
            return forward
                ? this.clampIndex(Math.floor(position + EPSILON) + 1)
                : this.clampIndex(Math.ceil(position - EPSILON) - 1);
        },

        /**
         * Whether the drag ended in a flick: at least `minSwipeDistance` of movement in
         * the drag direction within the last `FLICK_WINDOW` ms before release, fast enough.
         */
        isFlick(releasedAt) {
            // Unmeasured, the gap alone would give a tiny cell width and make any drag a flick.
            if (!this.viewport || !this.viewport.getBoundingClientRect().width) return false;

            const recent = this.samples.find((sample) => sample.t >= releasedAt - FLICK_WINDOW);
            if (!recent) return false;

            const distance = this.lastX - recent.x;
            const elapsed = releasedAt - recent.t;
            const sameDirection = Math.sign(distance) === Math.sign(this.lastX - this.startX);

            if (elapsed <= 0 || !sameDirection || Math.abs(distance) < this.minSwipeDistance) {
                return false;
            }

            return Math.abs(distance) / this.cellWidth() / elapsed > FLICK_VELOCITY;
        },

        clampIndex(value) {
            return this.loop ? value : Math.min(Math.max(value, 0), this.maxIndex);
        },

        /** Re-clamp without animation when bounded mode starts or its limit changes. */
        clampIntoRange() {
            if (this.loop) return;
            const clamped = Math.min(Math.max(this.index, 0), this.maxIndex);
            if (clamped !== this.index) {
                this.animate = false;
                this.index = clamped;
            }
        },

        /** Snap to the nearest reachable position, including a fractional end. */
        snap(value) {
            if (this.loop) return Math.round(value);
            const below = this.clampIndex(Math.floor(value));
            const above = this.clampIndex(Math.ceil(value));
            return value - below <= above - value ? below : above;
        },

        /**
         * Range the track may be dragged within. Upstream lets a looping track run
         * into its rendered clone buffer; with no buffer here, both modes stop at
         * the slides that exist.
         */
        dragBounds() {
            return { min: 0, max: this.loop ? Math.max(0, this.count - 1) : this.maxIndex };
        },
    };
}
