{{--
    TEDI Tooltip trigger.
    Port of angular/tedi/components/overlay/tooltip/tooltip-trigger/tooltip-trigger.component.ts

    The Angular selector is the element `tedi-tooltip-trigger`, which the
    vendored tooltip SCSS styles directly (`display: inline-flex`, plus the
    descendant rules for `.tedi-tooltip-trigger__text` and
    `.tedi-tooltip-trigger--focus`), so the root must be that literal element —
    CONVENTIONS.md §4's element-selector rule. It also carries `x-ref="trigger"`,
    which is what `tediOverlay` anchors against (§11).

    `openWith` lives on `<tedi:tooltip>` and is read here through `@aware`
    (Angular: `inject(TooltipComponent)`), with the same `both` fallback as the
    parent's `@props` default — CONVENTIONS.md §3. `descriptionId` is aware'd
    the same way so `aria-describedby` can point at the content's role=tooltip
    id without the consumer repeating it on every trigger.

    DOM introspection → explicit props (CONVENTIONS.md §5). Angular inspects its
    own first projected child in `ngAfterContentChecked`:

      | Angular runtime detection                     | Blade prop            |
      |-----------------------------------------------|-----------------------|
      | first child is a text node → wrap in a span    | `:text="true"`        |
      |   with `__text --focus tabindex=0`             |                       |
      | child is not natively focusable → add          | consumer's own markup |
      |   `--focus` + `tabindex=0` to it               |                       |
      | set `aria-describedby` on the focusable child  | `described-by="…"`    |
      |                                                | or aware descriptionId|

    Only the first branch is reachable from Blade: the other two mutate an
    element the consumer wrote, which a server-rendered template cannot reach
    into. For a non-focusable element trigger (`<span>Trigger</span>`) write the
    class and tabindex yourself:

        <tedi:tooltip-trigger>
            <span class="tedi-tooltip-trigger--focus" tabindex="0">Trigger</span>
        </tedi:tooltip-trigger>

    A natively focusable child (`<tedi:button>`, `<tedi:info-button>`) needs
    nothing — Angular deliberately skips `--focus` there so the component's own
    `:focus-visible` ring is not overridden.

    Keyboard activation (Angular #585): Enter/Space toggle on the synthesised
    text span when `openWith` is `click` or `both`. Native buttons/anchors
    already synthesise a click from those keys, so the handler is only on the
    text span — putting it on the host would double-toggle a button child.

    Touch handling (`touchstart`/`touchend`) is not ported — see
    tooltip.blade.php's header.
--}}
@aware([
    /** Must equal the tooltip's own @props default — CONVENTIONS.md §3. */
    'openWith' => 'both',
    'descriptionId' => null,
])
@props([
    /**
     * When false the trigger is a pure positioning anchor: no synthesized
     * tabindex, focus ring or aria-describedby. Use for decorative or
     * externally-controlled origins where focus and ARIA live elsewhere.
     */
    'interactive' => true,
    /**
     * The slot is plain text. Renders the underlined, focusable span Angular
     * synthesizes when its first projected child is a text node.
     */
    'text' => false,
    /**
     * id of the content's role=tooltip element. Defaults to the parent's
     * aware'd descriptionId when omitted.
     */
    'describedBy' => null,
])

@php
    $opensOnHover = $openWith === 'both' || $openWith === 'hover';
    $opensOnClick = $openWith === 'both' || $openWith === 'click';
    $wrapsText = $text && $interactive;
    $describedBy = $describedBy ?: $descriptionId;
@endphp

<tedi-tooltip-trigger
    {{ $attributes->class([
        'tedi-tooltip-trigger--clickable' => $openWith === 'click',
    ])->merge(array_filter([
        'aria-describedby' => $wrapsText ? null : ($interactive ? $describedBy : null),
    ])) }}
    x-ref="trigger"
    @if ($opensOnHover)
        x-on:mouseenter="hoverOpen()"
        x-on:mouseleave="hoverClose()"
        x-on:focusin="hoverOpen()"
        x-on:focusout="hoverClose()"
    @endif
    @if ($opensOnClick)
        x-on:click="toggle()"
    @endif
>
    @if ($wrapsText)
        <span
            class="tedi-tooltip-trigger__text tedi-tooltip-trigger--focus"
            tabindex="0"
            @if ($describedBy) aria-describedby="{{ $describedBy }}" @endif
            @if ($opensOnClick)
                x-on:keydown.enter.prevent="toggle()"
                x-on:keydown.space.prevent="toggle()"
            @endif
        >{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</tedi-tooltip-trigger>
