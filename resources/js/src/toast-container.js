// Toast container: the client half of `tedi:toast-container`, standing in for
// upstream's ToastService.
//
// Port of angular/src/tedi/services/toast/toast.service.ts and
// angular/src/tedi/components/notifications/toast/toast-container.component.ts.
// Upstream keeps the toasts in a signal and renders them through a CDK overlay
// it creates on demand. Here the container is server-rendered once in a layout,
// and this behaviour owns its DOM:
//
//   - A toast is a clone of the `<template>` prototype the container renders
//     from `tedi:toast` itself, so the markup (the alert, its translated close
//     button, the progress bar) has one source. The clone gets its title,
//     content, type, icon, progress bar and a fresh title id. Title and content
//     are set as text, never HTML, as upstream interpolates them.
//   - Groups: one `.tedi-toast-container__position--{position}` per position
//     with at least one toast, in upstream's POSITIONS order. A group is
//     removed when it empties, as upstream's `@if (… length > 0)` does.
//   - Timers: `show()`, `close()`, `pause()` and `resume()` are upstream's,
//     line for line — the timer runs only when `duration > 0`, pause/resume are
//     no-ops unless `pauseOnHover` is on and the toast is not exiting, and a
//     closed toast gets `tedi-toast--exiting` and is removed ANIMATION_DURATION
//     later. Pausing toggles `tedi-toast__progress--paused`, the class upstream
//     binds to `paused`.
//   - The toast's own outputs (toast.component.ts): mouseenter / mouseleave /
//     focusin / focusout on `.tedi-toast__wrapper` pause and resume, and a
//     focusout whose `relatedTarget` stays inside the wrapper is ignored. The
//     alert's close button closes the toast.
//   - Announcements go through announcer.js (upstream's ToastAnnouncerService):
//     "title: content" (or the title alone), `role="alert"` assertive, anything
//     else polite, `none` silent. The toast markup is never a live region.
//
// Server-rendered toasts. `tedi:toast` children in the container's slot are
// rendered into the bottom-right group (upstream's default position), so they
// are on the page without JS. On init each one is adopted as if it had been
// shown: moved to the group its `data-position` names, timed, and announced.
// The container root is `wire:ignore`d, so a Livewire morph neither strips the
// client toasts nor re-inserts adopted ones; server children are read once.
//
// Spawning. Upstream's ToastService is a root singleton, so any number of
// containers on a page (a Storybook Docs page renders one per story) must not
// show a toast more than once. The `tedi-toast` window listener and the
// `window.TediToast` API are therefore registered once, here, and route to the
// most recently initialised container; calls made before any container exists
// are queued until one does (upstream creates its container on demand).
//
//   window.TediToast.show({ title, content, type, icon, role, duration,
//                           showProgressBar, pauseOnHover, position, id })
//   window.TediToast.info|success|warning|danger(title, content, options)
//   window.TediToast.close(id)
//   window.dispatchEvent(new CustomEvent('tedi-toast', { detail: { … } }))
//   Livewire: $this->dispatch('tedi-toast', title: '…', type: 'success')
//
// As upstream, only `danger()` defaults `role` to `alert`; `show()` — and so
// the event — defaults every type to `status`.

import { announce, ensureRegions } from './announcer.js';

/** Upstream's TOAST_DEFAULT_DURATION. */
export const TOAST_DEFAULT_DURATION = 6000;

/** Upstream's ANIMATION_DURATION (the slide-out keyframes are 0.3s). */
const ANIMATION_DURATION = 300;

const POSITIONS = ['top-left', 'top-right', 'bottom-left', 'bottom-right'];
const TYPES = ['info', 'success', 'warning', 'danger'];
const ROLES = ['alert', 'status', 'none'];

// One registry per document, kept on `window`: if the bundle runs twice (a
// second script tag, or `wire:navigate` re-running body scripts), the second
// copy joins the first copy's containers and listener instead of adding its own.
const firstLoad = typeof window !== 'undefined' && !window.__tediToast;
const registry =
    typeof window === 'undefined'
        ? { containers: [], pending: [], id: 0 }
        : (window.__tediToast ??= { containers: [], pending: [], id: 0 });

/** Live containers, oldest first; the last one receives new toasts. */
const containers = registry.containers;

/** show() calls made while no container is on the page. */
const pending = registry.pending;

function nextId() {
    return `toast-${++registry.id}`;
}

function active() {
    return containers[containers.length - 1] ?? null;
}

function show(options = {}) {
    const config = { ...options };
    config.id = config.id || nextId();

    const container = active();
    if (container) {
        container.show(config);
    } else {
        pending.push(config);
    }

    return config.id;
}

function close(id) {
    containers.forEach((container) => container.close(id));
}

function withType(type, defaults = {}) {
    return (title, content, options = {}) =>
        show({ ...defaults, ...options, title, content, type });
}

export const TediToast = {
    show,
    close,
    info: withType('info'),
    success: withType('success'),
    warning: withType('warning'),
    danger: withType('danger', { role: 'alert' }),
};

if (firstLoad) {
    window.TediToast = TediToast;

    window.addEventListener('tedi-toast', (event) => {
        // Livewire's named arguments arrive as the detail object; a positional
        // array argument arrives wrapped in a list.
        let detail = event.detail;
        if (Array.isArray(detail)) detail = detail[0];
        if (detail && typeof detail === 'object') show(detail);
    });
}

function bool(value, fallback) {
    if (value === undefined || value === null || value === '') return fallback;
    return value === true || value === 'true' || value === '1' || value === 1;
}

/** Fills in upstream's `show()` defaults. */
function normalise(options) {
    const duration = Number(options.duration ?? TOAST_DEFAULT_DURATION);

    return {
        id: String(options.id),
        title: options.title == null ? '' : String(options.title),
        content: options.content == null ? '' : String(options.content),
        type: TYPES.includes(options.type) ? options.type : 'info',
        icon: options.icon ? String(options.icon) : '',
        role: ROLES.includes(options.role) ? options.role : 'status',
        duration: Number.isFinite(duration) ? duration : TOAST_DEFAULT_DURATION,
        showProgressBar: bool(options.showProgressBar, false),
        pauseOnHover: bool(options.pauseOnHover, true),
        position: POSITIONS.includes(options.position) ? options.position : 'bottom-right',
    };
}

export function toastContainer() {
    return {
        /** id → { el, toast, timeout, startTime, remainingTime, paused, exiting } */
        _toasts: new Map(),

        init() {
            ensureRegions();

            // Adopt the server-rendered toasts before taking new ones.
            this.$el.querySelectorAll('tedi-toast').forEach((el) => {
                if (el.closest('template')) return;
                this.adopt(el);
            });
            this.pruneGroups();

            containers.push(this);
            pending.splice(0).forEach((config) => this.show(config));
        },

        destroy() {
            const index = containers.indexOf(this);
            if (index !== -1) containers.splice(index, 1);

            this._toasts.forEach((state) => clearTimeout(state.timeout));
            this._toasts.clear();
        },

        /** Upstream's ToastService.show(), minus id generation (done in show()). */
        show(options) {
            const toast = normalise(options);
            const el = this.render(toast);
            if (!el) return toast.id;

            this.register(el, toast);

            return toast.id;
        },

        /** Upstream's ToastService.close(). */
        close(id) {
            const state = this._toasts.get(String(id));
            if (!state) return;

            clearTimeout(state.timeout);
            state.timeout = null;
            state.remainingTime = 0;

            if (state.exiting) return;
            state.exiting = true;
            state.el.classList.add('tedi-toast--exiting');

            setTimeout(() => {
                state.el.remove();
                this._toasts.delete(state.toast.id);
                this.pruneGroups();
            }, ANIMATION_DURATION);
        },

        /** Upstream's ToastService.pause(). */
        pause(id) {
            const state = this._toasts.get(id);
            if (!state || !state.toast.pauseOnHover || state.exiting) return;
            if (!state.timeout) return;

            const elapsed = Date.now() - state.startTime;
            clearTimeout(state.timeout);
            state.timeout = null;
            state.remainingTime = Math.max(0, state.remainingTime - elapsed);

            this.setPaused(state, true);
        },

        /** Upstream's ToastService.resume(). */
        resume(id) {
            const state = this._toasts.get(id);
            if (!state || !state.toast.pauseOnHover || state.exiting) return;
            if (!state.hasTimer || state.timeout) return;

            this.setPaused(state, false);

            if (state.remainingTime > 0) {
                state.startTime = Date.now();
                state.timeout = setTimeout(() => this.close(id), state.remainingTime);
            }
        },

        setPaused(state, paused) {
            state.el
                .querySelector('.tedi-toast__progress')
                ?.classList.toggle('tedi-toast__progress--paused', paused);
        },

        /** Reads a server-rendered toast's config off the data attributes `tedi:toast` emits. */
        adopt(el) {
            const data = el.dataset;
            const title = el.querySelector('.tedi-alert__title')?.textContent.trim() ?? '';
            const content = el.querySelector('.tedi-alert__body > :last-child')?.textContent.trim() ?? '';

            const toast = normalise({
                id: el.id || nextId(),
                title,
                content: title ? content : '',
                type: TYPES.find((type) => el.querySelector(`.tedi-alert--${type}`)) ?? 'info',
                role: data.role,
                duration: data.duration,
                showProgressBar: !!el.querySelector('.tedi-toast__progress'),
                pauseOnHover: data.pauseOnHover,
                position: data.position,
            });

            // An untitled server toast reads its message from the content.
            if (!title) toast.title = content;

            this.group(toast.position).appendChild(el);
            this.register(el, toast);
        },

        register(el, toast) {
            const state = {
                el,
                toast,
                timeout: null,
                startTime: Date.now(),
                remainingTime: toast.duration,
                hasTimer: toast.duration > 0,
                exiting: false,
            };
            this._toasts.set(toast.id, state);

            const wrapper = el.querySelector('.tedi-toast__wrapper') ?? el;
            wrapper.addEventListener('mouseenter', () => this.pause(toast.id));
            wrapper.addEventListener('mouseleave', () => this.resume(toast.id));
            wrapper.addEventListener('focusin', () => this.pause(toast.id));
            wrapper.addEventListener('focusout', (event) => {
                const next = event.relatedTarget;
                if (next && wrapper.contains(next)) return;
                this.resume(toast.id);
            });

            el.querySelector('.tedi-alert__close')?.addEventListener('click', () => this.close(toast.id));

            this.announce(toast);

            if (state.hasTimer) {
                state.timeout = setTimeout(() => this.close(toast.id), toast.duration);
            }
        },

        announce(toast) {
            if (toast.role === 'none') return;

            const message = toast.content ? `${toast.title}: ${toast.content}` : toast.title;
            announce(message, toast.role === 'alert' ? 'assertive' : 'polite');
        },

        /** Clones the prototype and fills it in. */
        render(toast) {
            const prototype = this.$refs.prototype?.content?.querySelector('tedi-toast');
            if (!prototype) return null;

            const el = prototype.cloneNode(true);
            ['position', 'duration', 'role', 'pauseOnHover'].forEach((key) => delete el.dataset[key]);

            const alert = el.querySelector('.tedi-alert');
            // The prototype is `info`; replace in place so the class order is the server's.
            alert?.classList.replace('tedi-alert--info', `tedi-alert--${toast.type}`);

            const title = el.querySelector('.tedi-alert__title');
            if (title) {
                title.textContent = toast.title;
                title.id = `${toast.id}-title`;
            }
            el.querySelector('.tedi-alert__close')?.setAttribute('aria-describedby', `${toast.id}-title`);

            const icon = el.querySelector('.tedi-alert__head > tedi-icon');
            if (icon && toast.icon) {
                icon.textContent = toast.icon;
            } else {
                icon?.remove();
            }

            // The prototype has a title, so its content is the body's last `div`.
            const content = el.querySelector('.tedi-alert__body > div:last-child');
            if (content) content.textContent = toast.content;

            const progress = el.querySelector('.tedi-toast__progress');
            if (progress && toast.showProgressBar && toast.duration > 0) {
                progress.classList.replace('tedi-toast__progress--info', `tedi-toast__progress--${toast.type}`);
                progress.style.animationDuration = `${toast.duration}ms`;
            } else {
                progress?.remove();
            }

            this.group(toast.position).appendChild(el);

            return el;
        },

        /** The position's group, created in upstream's POSITIONS order when missing. */
        group(position) {
            const existing = this.$el.querySelector(
                `:scope > .tedi-toast-container__position--${position}`,
            );
            if (existing) return existing;

            const group = document.createElement('div');
            group.className = `tedi-toast-container__position tedi-toast-container__position--${position}`;

            const after = POSITIONS.slice(POSITIONS.indexOf(position) + 1)
                .map((next) => this.$el.querySelector(`:scope > .tedi-toast-container__position--${next}`))
                .find(Boolean);
            this.$el.insertBefore(group, after ?? null);

            return group;
        },

        /** Removes groups with no toasts left. */
        pruneGroups() {
            this.$el.querySelectorAll(':scope > .tedi-toast-container__position').forEach((group) => {
                if (!group.querySelector('tedi-toast')) group.remove();
            });
        },
    };
}
