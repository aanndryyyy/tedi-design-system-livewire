/**
 * `tediModal` — the standalone (`[(open)]`) branch of Angular's modal:
 * backdrop click, Escape, body scroll lock, and focus restore. The service
 * branch is CDK Dialog and has no Blade equivalent.
 */
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
        hide: function () { this.open = false; },
        toggle: function () { this.open = !this.open; },

        onBackdropClick: function () {
            if (cfg.closeOnBackdropClick !== false) this.hide();
        },

        onKeydown: function (event) {
            if (event.key === 'Escape') this.hide();
        },

        _onOpen: function () {
            var self = this;

            this._previouslyFocused = document.activeElement;
            this._previousOverflow = document.body.style.overflow;
            document.body.style.overflow = 'hidden';

            this.$nextTick(function () {
                if (self.$refs.dialog) self.$refs.dialog.focus({ preventScroll: true });
            });
        },

        _onClose: function () {
            document.body.style.overflow = this._previousOverflow;

            if (this._previouslyFocused && this._previouslyFocused.focus) {
                this._previouslyFocused.focus({ preventScroll: true });
            }
        },
    };
}
