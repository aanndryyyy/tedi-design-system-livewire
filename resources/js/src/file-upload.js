// File upload — the client half of `tedi:file-upload`.
//
// Port of the parts of angular/src/tedi/components/form/file-upload/
// file-upload.component.ts that do not own the file list. In this port the list
// is server state: `files` is rendered by Blade, and a Livewire `wire:model` on
// the native input does the uploading (see the component's docblock for why
// the client-side list, `accept` / `maxSize` / `validator` checks, duplicate
// skipping and `keepRejectedFiles` stay on the server). What is ported here:
//
//   - openPicker(): the add button clicks the hidden `<input type="file">`.
//   - The label lookup (upstream's `afterRenderEffect`): the `label` whose
//     `for` is the input id gets an id (`{inputId}-label`) when it has none,
//     and names the add button (`aria-labelledby="{labelId} {inputId}-add"`)
//     and, in read-only mode, the file list. Without a label the attributes
//     are removed, as upstream binds `null`.
//   - Announcements, through the root-level regions of `announcer.js`
//     (upstream's ToastAnnouncerService):
//       * a selection announces `file-upload.added` — assertive and 300ms late
//         (ADD_ANNOUNCE_DELAY), because closing the file dialog leaves the
//         screen reader busy and drops anything inserted earlier;
//       * the clear button announces `file-upload.cleared` and a tag's remove
//         button `file-upload.removed`, politely, and focus moves to the add
//         button, which is the one control that is always there.
//     Every announcement supersedes a pending "added", as upstream does.
//
// Upstream clears `input.value` after every selection, because it keeps the
// files in its own list. Here the input IS the value — `wire:model` uploads
// from it and a native form submit sends it — so it is left alone, and only
// the clear button resets it.
//
// The label and the add button are re-decorated after a Livewire morph, which
// strips attributes the server HTML lacks and never re-runs `init()`
// (CONVENTIONS.md §11, "Client-set attributes survive a Livewire morph").
//
// Config keys:
//   inputId   — the native input's id (the label's `for`). Required.
//   multiple  — a single-file field always announces one file added.
//   labels    — { added, addedPlural, removed, cleared }: the translated
//               strings, with `:count` / `:file` placeholders left in.
//               `added` is the one-file form, `addedPlural` the rest.

import { announce, ensureRegions } from './announcer.js';

/** Upstream's private ADD_ANNOUNCE_DELAY. */
const ADD_ANNOUNCE_DELAY = 300;

export function fileUpload(config = {}) {
    const { inputId = null, multiple = false, labels = {} } = config;

    return {
        _addTimeout: null,
        _offMorph: null,
        _label: null,

        init() {
            ensureRegions();
            this.applyLabel();

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    if (el === this._label || el === this.$el || this.$el.contains(el)) {
                        queueMicrotask(() => this.applyLabel());
                    }
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }
        },

        destroy() {
            clearTimeout(this._addTimeout);
            this._offMorph?.();
            this._offMorph = null;
        },

        applyLabel() {
            if (!inputId) return;

            const label = Array.from(document.querySelectorAll('label')).find(
                (candidate) => candidate.htmlFor === inputId,
            );
            if (label && !label.id) label.id = `${inputId}-label`;
            this._label = label || null;

            const labelId = label ? label.id : null;
            this._setOrRemove(this.$refs.add, 'aria-labelledby', labelId ? `${labelId} ${inputId}-add` : null);
            this._setOrRemove(this.$refs.list, 'aria-labelledby', labelId);
        },

        openPicker() {
            const input = this.$refs.input;
            if (input && !input.disabled) input.click();
        },

        handleSelection(event) {
            const count = event.target.files ? event.target.files.length : 0;
            if (!count) return;

            const n = multiple ? count : 1;
            const message = ((n === 1 ? labels.added : labels.addedPlural) || labels.added || '').replace(':count', String(n));

            clearTimeout(this._addTimeout);
            this._addTimeout = setTimeout(() => announce(message, 'assertive'), ADD_ANNOUNCE_DELAY);
        },

        clearFiles() {
            if (this.$refs.input) this.$refs.input.value = '';
            this._afterUserRemoval(labels.cleared || '');
        },

        fileRemoved(name) {
            this._afterUserRemoval((labels.removed || '').replace(':file', name || ''));
        },

        _afterUserRemoval(message) {
            clearTimeout(this._addTimeout);
            announce(message, 'polite');
            this.$refs.add?.focus();
        },

        _setOrRemove(element, attribute, value) {
            if (!element) return;
            if (value) element.setAttribute(attribute, value);
            else element.removeAttribute(attribute);
        },
    };
}
