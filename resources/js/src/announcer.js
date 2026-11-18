// Root-level screen-reader announcements — no Alpine, shared by behaviours
// that announce something the component's own markup cannot.
//
// Port of angular/src/tedi/services/toast/toast-announcer.service.ts. Upstream
// deliberately avoids CDK's `LiveAnnouncer`: one region per politeness level
// is created up front and kept for the page's lifetime, and each message is
// appended as its own child, so rapid messages queue rather than overwrite
// each other, and TalkBack (which ignores a region whose `aria-live` is
// rewritten) still reads them. The same ids, attributes and delays are kept:
//
//   - region: `div#tedi-toast-announcer-{politeness}.sr-only`,
//     `aria-live` = politeness, `aria-atomic="false"` (messages are appended,
//     so an atomic region would re-read the earlier ones on every append);
//   - a message is appended after ANNOUNCE_DELAY and removed CLEAR_DELAY later.
//
// `ensureRegions()` exists because a live region inserted in the same breath
// as its first message is not reliably announced; a behaviour calls it from
// its `init()`, which stands in for upstream's service constructor.

const ANNOUNCE_DELAY = 100;
const CLEAR_DELAY = 1000;

function region(politeness) {
    const id = `tedi-toast-announcer-${politeness}`;
    let element = document.getElementById(id);

    if (!element) {
        element = document.createElement('div');
        element.setAttribute('aria-live', politeness);
        element.setAttribute('aria-atomic', 'false');
        element.classList.add('sr-only');
        element.id = id;
        document.body.appendChild(element);
    }

    return element;
}

/** Creates both regions now, so the first message is not lost. */
export function ensureRegions() {
    region('polite');
    region('assertive');
}

/**
 * @param {string} message
 * @param {'polite'|'assertive'} politeness
 */
export function announce(message, politeness = 'polite') {
    if (!message) return;

    const target = region(politeness);
    const entry = document.createElement('div');
    entry.textContent = message;

    setTimeout(() => {
        target.appendChild(entry);
        setTimeout(() => entry.remove(), CLEAR_DELAY);
    }, ANNOUNCE_DELAY);
}
