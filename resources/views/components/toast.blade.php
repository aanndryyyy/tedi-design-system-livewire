{{--
    TEDI Toast.
    Port of angular/src/tedi/components/notifications/toast/toast.component.{ts,html}

    Upstream's toast is rendered by `tedi-toast-container`, which ToastService
    fills; the service owns the timer, the pause on hover/focus and the screen
    reader announcement. Here `tedi:toast-container` and its bundled
    `tediToastContainer` behaviour play both parts (see that component). A toast
    placed in the container's slot is adopted on init, and from then on `role`,
    `duration`, `pauseOnHover` and `position` are live exactly where upstream
    uses them: the toast is announced with `role`'s politeness, closes after
    `duration` ms (0 keeps it), pauses while hovered or focused when
    `pauseOnHover` is on, and moves to the `position` group. The toast carries
    them as `data-*` attributes for the behaviour to read.

    A toast rendered anywhere else is static markup: nothing times, announces or
    places it, as an upstream `tedi-toast` outside the container does nothing
    either. `paused` sets the progress bar's initial paused class; the
    container toggles it.

    `position` is a Blade extension. Upstream it is a ToastConfig key passed to
    ToastService, not a `tedi-toast` input; a slotted toast has no service call
    to carry it, so it is a prop here. `id` is the toast's id for
    `TediToast.close(id)`, passed as the plain `id` attribute.
    `closed`/`mouseEnter`/`mouseLeave`/`focusIn`/`focusOut` outputs are not
    re-emitted; the container listens for them itself, and `closeAttributes`
    forwards extra attributes (wire:click) to the close button.

    The root is `<tedi-toast>`, the Angular selector's element — the sizing rule
    (`tedi-toast { display: block; width: var(--toast-width) }`) is keyed on it,
    as are the container's slide-in animations. `.tedi-toast__wrapper` sits
    inside, matching Angular's own template nesting, so the alert's drop shadow
    (`.tedi-toast__wrapper tedi-alert`) resolves too.

    A11y (Angular 8+). The visible toast is never a live region: the inner alert
    always renders `role="none"`, because announcing from both the toast and
    upstream's `ToastAnnouncerService` made screen readers read every toast two
    or three times. `role` picks the politeness of the container's announcement
    through that announcer's port (`resources/js/src/announcer.js`).
    The close button is the alert's: titled "Close", and described by the
    toast's title when there is one (Angular 10, #898/#900 — `toast.close` is gone).
--}}
@props([
    'title' => null,
    /** info|success|warning|danger */
    'type' => 'info',
    /** Material Symbols icon name. */
    'icon' => '',
    /** status|alert|none — politeness of the container's announcement; the toast itself is never a live region. */
    'role' => 'status',
    /** Duration in milliseconds for auto-close inside a container; 0 keeps the toast until closed and drops the progress bar. */
    'duration' => 6000,
    /** Whether to show the progress bar. */
    'showProgressBar' => false,
    /** Whether the toast timer is currently paused. */
    'paused' => false,
    /** Whether to pause the auto-close timer while the toast is hovered or holds keyboard focus. */
    'pauseOnHover' => true,
    /** top-left|top-right|bottom-left|bottom-right — the container group it moves to (Blade extension; upstream's ToastConfig.position). Null is bottom-right. */
    'position' => null,
    /** Extra attributes forwarded to the close button (e.g. wire:click). */
    'closeAttributes' => [],
])

<tedi-toast {{ $attributes->merge(array_filter([
    'data-position' => $position,
    'data-duration' => (string) (int) $duration,
    'data-role' => $role,
    'data-pause-on-hover' => $pauseOnHover ? 'true' : 'false',
], fn ($value) => $value !== null)) }}>
    <div class="tedi-toast__wrapper">
        <tedi:alert
            :title="$title"
            :type="$type"
            :icon="$icon"
            :show-close="true"
            :close-delay="300"
            role="none"
            :close-attributes="$closeAttributes"
        >
            {{ $slot }}
        </tedi:alert>

        @if ($showProgressBar && $duration > 0)
            <div
                @class([
                    'tedi-toast__progress',
                    'tedi-toast__progress--'.$type,
                    'tedi-toast__progress--paused' => $paused,
                ])
                style="animation-duration: {{ $duration }}ms"
            ></div>
        @endif
    </div>
</tedi-toast>
