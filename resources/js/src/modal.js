/**
 * `tediModal` — the standalone (`[(open)]`) branch of Angular's modal:
 * backdrop click, Escape, body scroll lock, and focus restore. The service
 * branch is CDK Dialog and has no Blade equivalent.
 *
 * Accessible name (upstream #680, `modal-label.ts`): when the consumer passed
 * neither `aria-label` nor `aria-labelledby`, every open looks up the first
 * `h1`–`h6` inside `tedi-modal-header`, gives it an id if it has none
 * (`tedi-modal-title-N`, as upstream's `_IdGenerator` prefix) and points the
 * dialog's `aria-labelledby` at it. Re-resolved on each open, as upstream,
 * because the heading is slot content and may change. No heading → no
 * attribute, so there is never a dangling reference.
 *
 * Close guard (upstream #674, `ModalRef.canClose`): upstream puts the guard on
 * `ModalRef`, which only exists in service mode. The port has no service mode,
 * so the same decision point is exposed on the template branch instead: every
 * close goes through `requestClose(reason)`, which dispatches a cancelable,
 * bubbling `tedi-modal-close` event on the `tedi-modal` element with
 * `detail.reason` set to upstream's `ModalCloseReason`
 * (`backdrop` | `escape` | `close-button` | `programmatic`). Calling
 * `preventDefault()` keeps the modal open. Upstream's Promise/Observable
 * verdicts become "prevent now, call `close()` later": `close()` is the
 * unguarded close, as upstream's `ModalService.closeAll()` is.
 *
 * Escape is handled by the topmost open modal only, so a confirmation modal
 * opened over a guarded one closes itself rather than re-triggering the guard
 * underneath it.
 */
var openStack = [];
var titleCounter = 0;

export function modal(config) {
    var cfg = config || {};

    return {
        open: !!cfg.open,
        _previouslyFocused: null,
        _previousOverflow: '',

        init: function () {
            var self = this;

            this.$watch('open', function (value) {
                if (value) {
                    self._onOpen();
                } else {
                    self._onClose();
                }
            });

            if (this.open) this._onOpen();
        },

        destroy: function () {
            if (this.open) this._onClose();
        },

        show: function () { this.open = true; },
        /** Guarded close, as upstream's `ModalRef.close()` (`programmatic`). */
        hide: function () { this.requestClose('programmatic'); },
        /** Unguarded close — upstream's `closeAll()` path. */
        close: function () { this.open = false; },
        toggle: function () {
            if (this.open) {
                this.hide();
            } else {
                this.show();
            }
        },

        requestClose: function (reason) {
            if (!this.open) return;

            var event = new CustomEvent('tedi-modal-close', {
                bubbles: true,
                cancelable: true,
                detail: { reason: reason || 'programmatic' },
            });

            if (this.$root.dispatchEvent(event)) this.open = false;
        },

        onBackdropClick: function () {
            if (cfg.closeOnBackdropClick !== false) this.requestClose('backdrop');
        },

        onKeydown: function (event) {
            if (event.key !== 'Escape' || !this.open) return;
            if (openStack[openStack.length - 1] !== this.$root) return;

            this.requestClose('escape');
        },

        _resolveHeadingLabel: function () {
            var dialog = this.$refs.dialog;
            if (!dialog || cfg.labelled) return;

            var heading = dialog.querySelector(
                ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'].map(function (tag) {
                    return 'tedi-modal-header ' + tag;
                }).join(', ')
            );

            if (!heading) {
                dialog.removeAttribute('aria-labelledby');
                return;
            }

            if (!heading.id) heading.id = 'tedi-modal-title-' + titleCounter++;

            dialog.setAttribute('aria-labelledby', heading.id);
        },

        _onOpen: function () {
            var self = this;

            openStack.push(this.$root);
            this._resolveHeadingLabel();
            this._previouslyFocused = document.activeElement;
            this._previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';

            this.$nextTick(function () {
                if (self.$refs.dialog) self.$refs.dialog.focus({ preventScroll: true });
            });
        },

        _onClose: function () {
            var index = openStack.indexOf(this.$root);
            if (index !== -1) openStack.splice(index, 1);

            document.body.style.overflow = this._previousOverflow;

            if (this._previouslyFocused && this._previouslyFocused.focus) {
                this._previouslyFocused.focus({ preventScroll: true });
            }
        },
    };
}
