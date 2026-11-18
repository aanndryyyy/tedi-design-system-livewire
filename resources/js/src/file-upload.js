// File upload — the client half of `tedi:file-upload`.
//
// Port of angular/src/tedi/components/form/file-upload/file-upload.component.ts.
// The file LIST stays server state: `files` is rendered by Blade, and a
// Livewire `wire:model` on the native input uploads what the input holds (or a
// native form submit sends it). What runs here is everything upstream does
// between the file dialog and the list:
//
//   - THE SELECTION PIPELINE (upstream's `addFiles()`), on the input's
//     `change`, in a CAPTURE listener on the root. An ancestor's capture
//     listener runs before any listener on the input itself, whatever the
//     registration order, so the files are filtered before Livewire's
//     `wire:model` handler (or a consumer's `x-on:change`) reads them:
//       * duplicates — a file whose name, size and mtime match one the field
//         already holds — are skipped (upstream's `fileIdentity`);
//       * each fresh file is checked against `accept` (extension, exact MIME
//         type or `type/*`, as the browser reads it), `maxSize` and the
//         optional `validator`, and every rule it breaks is recorded;
//       * the accepted files are written back into `input.files` with a
//         `DataTransfer`: for `multiple`, APPENDED to the files the input held
//         before the dialog opened (upstream appends to its list); otherwise
//         the first one replaces them. The event then continues, so Livewire
//         uploads exactly that list;
//       * when nothing new was accepted, the input is put back as it was and
//         the event is stopped there: Livewire never sees the selection, as
//         upstream's list is never touched.
//     Under Livewire, appending re-uploads the earlier files with the new ones
//     (the input is the whole list). Livewire nulls the input on every click,
//     so the previous list is snapshotted in a capture `click` listener first,
//     and restored on `cancel`.
//   - REJECTED FILES (`keepRejectedFiles`, multiple fields only) are kept in
//     this component's state, never in `input.files` — they would upload and
//     come back as valid server entries. They render as danger tags after the
//     server's, cloned from the inert `<template>` prototypes the Blade renders
//     (so the markup is `tedi:tag`'s own). A single-file field always discards
//     them: its one slot is server state.
//   - THE REJECTION SUMMARY (`rejectionSummary()`), the field's error text:
//     the last selection's rejections, or with `keepRejectedFiles` the
//     rejections of the files still listed. It is joined with the server's
//     `error` (". "), turns the surface invalid, hides the success text, and
//     joins the add button's `aria-describedby`, as upstream's `errorText()`.
//   - Announcements, through the root-level regions of `announcer.js`
//     (upstream's ToastAnnouncerService): a selection announces its rejection
//     summary, the skipped duplicates and the files added — assertive and 300ms
//     late (ADD_ANNOUNCE_DELAY), because closing the file dialog leaves the
//     screen reader busy; the clear button and a tag's remove button announce
//     politely and move focus to the add button.
//   - openPicker() and the label lookup (upstream's `afterRenderEffect`): the
//     `label` whose `for` is the input id gets an id when it has none, and
//     names the add button and, read-only, the list.
//
// SURVIVING A LIVEWIRE MORPH. A morph strips everything the server HTML lacks
// (the cloned tags, a client error text, a client clear button, the classes
// and ids set here) and never re-runs `init()`. So every DOM write lives in
// `apply()`, which runs after each morph of anything inside the field
// (CONVENTIONS.md §11). The server state it combines with is read from the
// prototype template's `data-state`, which the morph refreshes — never from
// this config, which Alpine never re-evaluates. Client nodes are not kept with
// `skip()`: unkeyed morphing matches by position and would patch a client tag
// into the next server tag. When the server list goes from some files to none
// without this field removing them (the consumer cleared it), the kept
// rejected files and the summary go too, as upstream's `writeValue([])`.
//
// THE VALIDATOR is a JavaScript expression, evaluated with Alpine in the
// field's scope with `file` in scope. A function result is called with the
// file, so both `validator="noSpaces"` (a global function) and
// `validator="(file) => …"` work; anything else is used as the result. It
// must return the rejection message, or nothing. This is `eval`-class: it does
// not work with Alpine's CSP build, and it must never carry user input.
//
// Config keys:
//   inputId          — the native input's id (the label's `for`). Required.
//   multiple         — append rather than replace; announce counts.
//   accept, maxSize  — upstream's restrictions.
//   keepRejectedFiles
//   validator        — see above. Optional.
//   labels           — the translated strings, placeholders left in. Each
//                      count-worded message has a one-file form and an
//                      `…Plural` one: added, extensionRejected, sizeRejected,
//                      duplicatesSkipped; plus removed, cleared.

import { announce, ensureRegions } from './announcer.js';

/** Upstream's private ADD_ANNOUNCE_DELAY. */
const ADD_ANNOUNCE_DELAY = 300;

/** Marks the nodes this behaviour inserted, so `apply()` can find them again. */
const CLIENT = 'data-tedi-file-upload-client';

/** Upstream's `fileIdentity`: name alone collides across devices. */
const identity = (file) => `${file.name}:${file.size}:${file.lastModified}`;

/** Upstream's translate() with a `count` compared as a string: "1" or not. */
const counted = (one, other, count) => (String(count) === '1' ? one : other || one) || '';

export function fileUpload(config = {}) {
    const {
        inputId = null,
        multiple = false,
        accept = null,
        maxSize = null,
        keepRejectedFiles = false,
        validator = null,
        labels = {},
    } = config;

    const acceptTokens = (accept || '')
        .split(',')
        .map((token) => token.trim().toLowerCase())
        .filter(Boolean);

    // A single-file field cannot keep a rejected file: see the header.
    const keepsRejected = !!keepRejectedFiles && !!multiple;

    return {
        _addTimeout: null,
        _offMorph: null,
        _label: null,
        _listeners: [],
        _held: [],
        _rejected: [],
        _selectionError: null,
        _serverCount: null,
        _ownRemoval: false,
        _morphQueued: false,
        _keySeed: 0,
        // `$el` is the element whose directive called a method — a cloned
        // tag's remove button, say — so the root is kept from init().
        _root: null,

        init() {
            ensureRegions();
            this._root = this.$el;

            const input = this.$refs.input;
            this._held = input ? Array.from(input.files || []) : [];
            this._serverCount = this._state().count;

            this._listen(this._root, 'click', (event) => {
                if (event.target === this.$refs.input) this._held = Array.from(event.target.files || []);
            });
            this._listen(this._root, 'change', (event) => this.handleSelection(event));
            this._listen(this._root, 'cancel', (event) => {
                if (event.target === this.$refs.input) this._setFiles(this._held);
            });

            this.applyLabel();
            this.apply();

            const livewire = window.Livewire;
            if (livewire && typeof livewire.hook === 'function') {
                const off = livewire.hook('morph.updated', ({ el }) => {
                    const ours = el === this._label || el === this._root || this._root.contains(el);
                    if (!ours || this._morphQueued) return;

                    // An ancestor's `updated` fires before its children are
                    // patched; run once the whole morph has landed.
                    this._morphQueued = true;
                    queueMicrotask(() => {
                        this._morphQueued = false;
                        this._afterMorph();
                    });
                });
                this._offMorph = typeof off === 'function' ? off : null;
            }
        },

        destroy() {
            clearTimeout(this._addTimeout);
            this._listeners.forEach(([target, type, handler]) => target.removeEventListener(type, handler, true));
            this._listeners = [];
            this._offMorph?.();
            this._offMorph = null;
        },

        // -- the pipeline -----------------------------------------------------

        handleSelection(event) {
            const input = this.$refs.input;
            if (!input || event.target !== input) return;

            const selected = Array.from(input.files || []);
            if (!selected.length) return;

            const listed = new Set([...this._held, ...this._rejected.map((entry) => entry.file)].map(identity));
            const duplicates = [];
            const fresh = [];
            for (const file of selected) (listed.has(identity(file)) ? duplicates : fresh).push(file);

            // Every rule a file breaks is reported, not just the first.
            const rejections = [];
            const accepted = [];
            const rejected = [];
            for (const file of fresh) {
                const rejection = { name: file.name, reasons: this._reasons(file), message: this._validate(file) };
                if (!rejection.reasons.length && !rejection.message) {
                    accepted.push(file);
                } else {
                    rejections.push(rejection);
                    rejected.push({ key: `r${++this._keySeed}`, file, rejection });
                }
            }

            if (accepted.length) {
                this._held = multiple ? [...this._held, ...accepted] : [accepted[0]];
                this._setFiles(this._held);
            } else {
                // Nothing new: the list is untouched, and Livewire never sees it.
                this._setFiles(this._held);
                event.stopPropagation();
            }

            if (keepsRejected) this._rejected = [...this._rejected, ...rejected];

            const messages = [];
            const rejection = this._summary(rejections);
            this._selectionError = rejection;
            if (rejection) messages.push(rejection);

            if (duplicates.length) {
                messages.push(
                    this._format(labels.duplicatesSkipped, labels.duplicatesSkippedPlural, duplicates.map((file) => `'${file.name}'`).join(', '), duplicates.length),
                );
            }

            if (accepted.length) {
                const count = multiple ? accepted.length : 1;
                messages.push(counted(labels.added, labels.addedPlural, count).replace(':count', String(count)));
            }

            this.apply();

            clearTimeout(this._addTimeout);
            const message = messages.join('. ');
            this._addTimeout = setTimeout(() => announce(message, 'assertive'), ADD_ANNOUNCE_DELAY);
        },

        /** isAcceptedType() + the size check. */
        _reasons(file) {
            const reasons = [];

            if (acceptTokens.length) {
                const extension = file.name.includes('.') ? `.${file.name.split('.').pop().toLowerCase()}` : '';
                const mimeType = (file.type || '').toLowerCase();
                const ok = acceptTokens.some((token) => {
                    if (token.startsWith('.')) return token === extension;
                    if (token.endsWith('/*')) return mimeType.startsWith(token.slice(0, -1));

                    return token === mimeType;
                });
                if (!ok) reasons.push('extension');
            }

            if (maxSize && file.size > maxSize) reasons.push('size');

            return reasons;
        },

        _validate(file) {
            const Alpine = window.Alpine;
            if (!validator || !Alpine || typeof Alpine.evaluate !== 'function') return undefined;

            try {
                const result = Alpine.evaluate(this._root, validator, { scope: { file }, params: [file] });

                return typeof result === 'string' && result ? result : undefined;
            } catch (error) {
                console.error('tedi:file-upload validator failed', error);

                return undefined;
            }
        },

        /**
         * One message for every rejection: built-in reasons first, grouped by
         * reason, then each validator message followed by the files it refused.
         */
        _summary(rejections) {
            const builtIn = { extension: [], size: [] };
            const custom = new Map();

            for (const { name, reasons, message } of rejections) {
                const quoted = `'${name}'`;
                reasons.forEach((reason) => builtIn[reason].push(quoted));
                if (message) custom.set(message, [...(custom.get(message) || []), quoted]);
            }

            const parts = [
                ...Object.entries(builtIn)
                    .filter(([, names]) => names.length)
                    .map(([reason, names]) =>
                        this._format(labels[`${reason}Rejected`], labels[`${reason}RejectedPlural`], names.join(', '), names.length),
                    ),
                ...Array.from(custom, ([message, names]) => `${message.replace(/[\s.]+$/, '')}: ${names.join(', ')}`),
            ];

            return parts.join('. ') || null;
        },

        _format(one, other, files, count) {
            return counted(one, other, count).replace(':files', files).replace(':count', String(count));
        },

        /** rejectionError(): what the field's error text says about the files. */
        rejectionError() {
            return keepsRejected ? this._summary(this._rejected.map((entry) => entry.rejection)) : this._selectionError;
        },

        _setFiles(files) {
            const input = this.$refs.input;
            if (!input) return;

            try {
                const transfer = new DataTransfer();
                files.forEach((file) => transfer.items.add(file));
                input.files = transfer.files;
            } catch (error) {
                // No DataTransfer constructor (very old Safari): leave the input.
            }
        },

        // -- buttons ----------------------------------------------------------

        openPicker() {
            const input = this.$refs.input;
            if (input && !input.disabled) input.click();
        },

        clearFiles() {
            if (this.$refs.input) this.$refs.input.value = '';
            this._held = [];
            this._rejected = [];
            this._selectionError = null;
            this._ownRemoval = true;
            this.apply();
            this._afterUserRemoval(labels.cleared || '');
        },

        /** A server-rendered tag's remove button. The server drops it from the list. */
        fileRemoved(name) {
            const index = this._held.findIndex((file) => file.name === name);
            if (index !== -1) {
                this._held = this._held.filter((_, i) => i !== index);
                this._setFiles(this._held);
            }
            this._ownRemoval = true;
            this._clearRejectionWhenResolved();
            this.apply();
            this._afterUserRemoval((labels.removed || '').replace(':file', name || ''));
        },

        /** A kept rejected file's remove button. */
        removeRejected(event) {
            const item = event?.target?.closest?.(`[${CLIENT}="tag"]`);
            const entry = this._rejected.find((candidate) => candidate.key === item?.dataset.key);
            if (!entry) return;

            this._rejected = this._rejected.filter((candidate) => candidate !== entry);
            this._clearRejectionWhenResolved();
            this.apply();
            this._afterUserRemoval((labels.removed || '').replace(':file', entry.file.name || ''));
        },

        /** Drops the summary once no invalid file is left for it to describe. */
        _clearRejectionWhenResolved() {
            if (!this._rejected.length && !this._state().invalidCount) this._selectionError = null;
        },

        _afterUserRemoval(message) {
            clearTimeout(this._addTimeout);
            announce(message, 'polite');
            this.$nextTick(() => this.$refs.add?.focus());
        },

        // -- the DOM ----------------------------------------------------------

        _afterMorph() {
            const count = this._state().count;
            if (this._serverCount > 0 && count === 0 && !this._ownRemoval) {
                // The consumer emptied the list: as upstream's writeValue([]).
                this._rejected = [];
                this._selectionError = null;
                this._held = [];
                if (this.$refs.input) this.$refs.input.value = '';
            }
            this._serverCount = count;
            this._ownRemoval = false;

            this.applyLabel();
            this.apply();
        },

        /** The server render's state, refreshed by every morph. */
        _state() {
            const raw = this.$refs.prototypes?.dataset.state;
            try {
                return { count: 0, invalidCount: 0, describedBy: [], ...(raw ? JSON.parse(raw) : {}) };
            } catch (error) {
                return { count: 0, invalidCount: 0, describedBy: [] };
            }
        },

        _prototype(name) {
            return this.$refs.prototypes?.content.querySelector(`[data-prototype="${name}"]`)?.cloneNode(true) || null;
        },

        /** Every client-side DOM write: idempotent, and re-run after a morph. */
        apply() {
            const prototypes = this.$refs.prototypes;
            if (!prototypes) return;

            const state = this._state();
            const root = this._root;
            root.querySelectorAll(`[${CLIENT}]`).forEach((node) => node.remove());

            // Kept rejected files, after the server's tags.
            const list = root.querySelector('.tedi-file-upload__value > .tedi-file-upload__tags');
            if (list) {
                this._rejected.forEach((entry, index) => {
                    const item = this._prototype('tag');
                    if (!item) return;
                    item.removeAttribute('data-prototype');
                    item.setAttribute(CLIENT, 'tag');
                    item.dataset.key = entry.key;

                    const content = item.querySelector('.tedi-tag__content');
                    if (content) {
                        const tagId = `${inputId}-rejected-${index}`;
                        const oldId = content.id;
                        content.id = tagId;
                        item.querySelectorAll(`[aria-describedby="${oldId}"]`).forEach((node) => node.setAttribute('aria-describedby', tagId));
                        content.insertBefore(document.createTextNode(entry.file.name), content.firstChild);
                    }
                    list.appendChild(item);
                });
            }

            const total = state.count + this._rejected.length;
            root.classList.toggle('tedi-file-upload--empty', total === 0);

            // The clear button, while only client entries are listed.
            const actions = root.querySelector('.tedi-file-upload__actions');
            if (actions && state.clearable && !state.disabled && total > 0 && !actions.querySelector('.tedi-file-upload__clear-group')) {
                const group = this._prototype('clear');
                if (group) {
                    group.removeAttribute('data-prototype');
                    group.setAttribute(CLIENT, 'clear');
                    actions.insertBefore(group, actions.firstChild);
                }
            }

            // errorText(): the rejection summary, then the server's `error`.
            const clientError = this.rejectionError();
            const errorText = [clientError, state.error].filter(Boolean).join('. ');
            const errorId = `${inputId}-error`;
            const field = root.querySelector('.tedi-file-upload__field');
            let error = document.getElementById(errorId);

            if (clientError) {
                if (!error || !root.contains(error)) {
                    error = this._prototype('error');
                    if (error && field) {
                        error.removeAttribute('data-prototype');
                        error.setAttribute(CLIENT, 'error');
                        error.id = errorId;
                        field.after(error);
                    }
                }
                if (error) error.textContent = errorText;
            } else if (error && root.contains(error) && state.error) {
                error.textContent = state.error;
            }

            const success = document.getElementById(`${inputId}-success`);
            if (success && root.contains(success)) success.hidden = !!clientError;

            // The surface's state modifiers, when the field paints it.
            const invalid = !!state.invalid || !!clientError;
            if (field && field.classList.contains('tedi-field-surface')) {
                field.classList.toggle('tedi-field-surface--invalid', invalid);
                field.classList.toggle('tedi-field-surface--valid', !invalid && !!state.valid);
            }

            // controlDescribedBy(): the base ids, then error / success / hint.
            const describedBy = [
                ...state.describedBy,
                errorText ? errorId : null,
                !errorText && state.success ? `${inputId}-success` : null,
                state.hint ? `${inputId}-hint` : null,
            ].filter((id, index, ids) => id && ids.indexOf(id) === index);
            this._setOrRemove(this.$refs.add, 'aria-describedby', describedBy.join(' ') || null);
        },

        applyLabel() {
            if (!inputId) return;

            const label = Array.from(document.querySelectorAll('label')).find((candidate) => candidate.htmlFor === inputId);
            if (label && !label.id) label.id = `${inputId}-label`;
            this._label = label || null;

            const labelId = label ? label.id : null;
            this._setOrRemove(this.$refs.add, 'aria-labelledby', labelId ? `${labelId} ${inputId}-add` : null);
            this._setOrRemove(this.$refs.list, 'aria-labelledby', labelId);
        },

        _listen(target, type, handler) {
            target.addEventListener(type, handler, true);
            this._listeners.push([target, type, handler]);
        },

        _setOrRemove(element, attribute, value) {
            if (!element) return;
            if (value) element.setAttribute(attribute, value);
            else element.removeAttribute(attribute);
        },
    };
}
