// Progress bar value announcements — the opt-in live region behind
// `tedi:progress-bar`'s `announce` prop.
//
// Port of the announcement half of
// `src/tedi/components/loader/progress-bar/progress-bar.component.ts`
// (`announce`, `announceInterval`, `queueAnnouncement`, `announceNow`,
// `resetAnnouncement`). Upstream reacts to its `value` / `valueLabel` signals;
// Blade has none, so this watches the rendered `<progress>` instead. Whatever
// changes the bar — a Livewire morph rewriting the attributes, or an Alpine
// `:value` binding (HTMLProgressElement's `value` setter reflects to the
// content attribute) — shows up as an attribute mutation.
//
// The announced text is upstream's `formattedValue()`: `valueLabel` when set,
// otherwise `{value}%`. The template already renders `valueLabel` as the bar's
// `aria-valuetext`, so the text is derived from the element rather than passed
// in twice.
//
// Behaviour kept verbatim from upstream:
//   - the current value is NOT announced on init — only changes are;
//   - the first change is published immediately;
//   - later changes inside `interval` ms are coalesced into one update carrying
//     the latest value, published when the interval ends;
//   - `interval` 0 disables throttling; negative becomes 0; non-finite → 1000.
//
// The region is `wire:ignore`d in the template so a Livewire morph does not
// blank the text this module wrote.

/**
 * @param {object} config
 * @param {number} config.interval  minimum ms between live-region updates
 */
export function progressBar({ interval = 1000 } = {}) {
    const resolvedInterval = Number.isFinite(interval) ? Math.max(0, interval) : 1000;

    return {
        announcedValue: '',
        previousText: undefined,
        lastAnnouncedAt: undefined,
        timeout: undefined,
        observer: null,

        init() {
            const bar = this.$refs.bar;

            if (! bar) {
                return;
            }

            this.previousText = this.formattedValue();
            this.observer = new MutationObserver(() => this.onChange());
            this.observer.observe(bar, { attributes: true, attributeFilter: ['value', 'aria-valuetext'] });
        },

        destroy() {
            this.observer?.disconnect();
            clearTimeout(this.timeout);
        },

        formattedValue() {
            const bar = this.$refs.bar;
            const label = bar.getAttribute('aria-valuetext');

            return label !== null ? label : `${bar.value}%`;
        },

        onChange() {
            const text = this.formattedValue();
            const changed = text !== this.previousText;
            this.previousText = text;

            if (changed || this.timeout !== undefined) {
                this.queue(text);
            }
        },

        queue(text) {
            const remaining = this.lastAnnouncedAt === undefined
                ? 0
                : resolvedInterval - (Date.now() - this.lastAnnouncedAt);

            if (remaining <= 0) {
                this.announceNow(text);

                return;
            }

            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.announceNow(this.formattedValue()), remaining);
        },

        announceNow(text) {
            clearTimeout(this.timeout);
            this.timeout = undefined;
            this.lastAnnouncedAt = Date.now();
            this.announcedValue = text;
        },
    };
}
