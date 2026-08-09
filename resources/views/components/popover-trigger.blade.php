{{--
    TEDI Popover Trigger.
    Port of angular/tedi/components/overlay/popover/popover-trigger/popover-trigger.directive.ts

    Angular's `[tedi-popover-trigger]` is an attribute directive applied to a
    consumer-chosen host (`<button tedi-button>`, `<span>`). The vendored SCSS
    keys off that literal attribute — `[tedi-popover-trigger] { cursor: pointer }`
    and a `:not(button, a[href]):focus-visible` outline — so per CONVENTIONS.md
    §4's element-selector rule the root carries `tedi-popover-trigger` verbatim,
    and `tag` picks the host element (`span` by default, matching the directive's
    unconditional `tabindex="0"`, which only makes sense on a non-focusable host).

    Consumers who need a fully-featured TEDI button as the trigger apply the
    wiring to `<tedi:button>` directly, exactly as Angular stacks the two
    directives on one element:

        <tedi:button tedi-popover-trigger tabindex="0" role="button"
            aria-haspopup="dialog" aria-expanded="false"
            x-ref="trigger" x-on:click="toggle()" x-bind:aria-expanded="open">…</tedi:button>

    `containerId` arrives through `@aware` from `<tedi:popover>` — see that
    component's header for why the pairing is explicit rather than generated.
    Without it the trigger renders no `id`/`aria-controls`, since a dangling id
    reference is worse than an absent one.
--}}
@aware([
    'containerId' => null,
])
@props([
    /** Host element the directive is applied to. */
    'tag' => 'span',
    /** Dashed underline treatment for text triggers. */
    'underline' => false,
    /** When false, drops the button role and dialog ARIA — the element is only a positioning anchor. */
    'interactive' => true,
    /** Popover id used for ARIA pairing; normally inherited from the parent tedi-popover. */
    'containerId' => null,
])

<{{ $tag }}
    tedi-popover-trigger
    tabindex="0"
    @if ($tag === 'button') type="button" @endif
    @if ($containerId) id="{{ $containerId }}_trigger" @endif
    {{ $attributes->class([
        'tedi-popover-trigger__text' => (bool) $underline,
    ])->merge(array_filter([
        'role' => $interactive ? 'button' : null,
        'aria-haspopup' => $interactive ? 'dialog' : null,
        'aria-expanded' => $interactive ? 'false' : null,
    ])) }}
    x-ref="trigger"
    x-on:click="toggle()"
    @if ($interactive)
        x-bind:aria-expanded="open"
        @if ($containerId)
            x-bind:aria-controls="open ? '{{ $containerId }}' : null"
        @endif
    @endif
>{{ $slot }}</{{ $tag }}>
