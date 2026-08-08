{{--
    TEDI Toast.
    Port of angular/tedi/components/notifications/toast/toast.component.{ts,html}

    Angular's toast is placed by a service into a `tedi-toast-container` that
    CDK Overlay positions (top-left/top-right/bottom-left/bottom-right) and
    animates in/out. Per CONVENTIONS.md §7 overlay positioning is out of scope
    for this phase — this component only renders the toast's own markup and
    classes as a statically-positioned element. The consumer is responsible
    for placing it (e.g. inside their own fixed-position container).

    `position` and `id` from ToastConfig are overlay/service concerns and are
    not ported. `pauseOnHover` is accepted for API parity but is inert — it
    only mattered to a JS timer that no longer exists; Alpine consumers can
    still bind wire:mouseenter/wire:mouseleave via $attributes on the
    host. `closed`/`mouseEnter`/`mouseLeave` outputs are not re-emitted;
    follow tag.blade.php's `closeAttributes` pattern to bind a close handler.

    The root is `<tedi-toast>`, the Angular selector's element — the sizing rule
    (`tedi-toast { display: block; width: var(--toast-width) }`) is keyed on it,
    as are the container's slide-in animations. `.tedi-toast__wrapper` sits
    inside, matching Angular's own template nesting, so the alert's drop shadow
    (`.tedi-toast__wrapper tedi-alert`) resolves too.
--}}
@props([
    'title' => null,
    /** info|success|warning|danger */
    'type' => 'info',
    /** Material Symbols icon name. */
    'icon' => '',
    /** status|alert|none */
    'role' => 'status',
    /** Duration in milliseconds for auto-close; 0 disables the progress bar. */
    'duration' => 6000,
    /** Whether to show the progress bar. */
    'showProgressBar' => false,
    /** Whether the toast timer is currently paused. */
    'paused' => false,
    /** Whether to pause the auto-close timer on hover; kept for API parity — inert without the JS timer. */
    'pauseOnHover' => true,
    /** Extra attributes forwarded to the close button (e.g. wire:click). */
    'closeAttributes' => [],
])

<tedi-toast {{ $attributes }}>
    <div class="tedi-toast__wrapper">
        <tedi:alert
            :title="$title"
            :type="$type"
            :icon="$icon"
            :show-close="true"
            :close-delay="300"
            :role="$role"
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
