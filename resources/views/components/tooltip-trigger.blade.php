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
    parent's `@props` default — CONVENTIONS.md §3.

    DOM introspection → explicit props (CONVENTIONS.md §5). Angular inspects its
    own first projected child in `ngAfterContentChecked`:

      | Angular runtime detection                     | Blade                  |
      |-----------------------------------------------|------------------------|
      | first child is a text node → wrap in a span    | `:text="true"`         |
      |   with `__text --focus tabindex=0 role=button` |                        |
      | child is not natively focusable → add          | consumer's own markup  |
      |   `--focus`, `tabindex=0` and `role=button`    |                        |
      | set `aria-describedby` on the resolved child   | Alpine `x-init`        |

    The first branch is server-rendered. The second mutates an element the
    consumer wrote, and adding classes from JS would break CONVENTIONS.md §8's
    "classes stay identical without JS", so for a non-focusable element trigger
    write them yourself:

        <span class="tedi-tooltip-trigger--focus" tabindex="0" role="button">Trigger</span>

    A natively focusable child (`tedi:button`, `tedi:info-button`) needs
    nothing. Angular deliberately skips `--focus` there so the component's own
    `:focus-visible` ring is not overridden.

    `aria-describedby` is only an attribute, so it is set from Alpine on the
    same element Angular resolves: the first child when it is a `button` /
    `a[href]`, else its first `button, a[href], [tabindex]` descendant, else the
    child itself. It points at the content's `role="tooltip"` element: the
    tooltip's `description-id` (via `@aware`) or Alpine's `$id('tedi-tooltip')`.
    `described-by` overrides it. On the `:text="true"` span the attribute is
    rendered directly (or bound, for the Alpine id).

    Enter / Space on a non-native trigger (Angular 9, #585): the synthesized
    span, or a consumer `role="button"` element, has no native click on those
    keys, so a keydown handler toggles the tooltip for `click` / `both`. It
    skips `button` / `a[href]` targets, which already turn the key into a
    click; handling it twice would toggle twice.

    Touch handling (`touchstart`/`touchend`) is not ported — see
    tooltip.blade.php's header.
--}}
@aware([
    /** Must equal the tooltip's own @props default — CONVENTIONS.md §3. */
    'openWith' => 'both',
    /** The tooltip's `description-id`; null means Alpine's $id('tedi-tooltip'). */
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
    /** Overrides the id aria-describedby points at (default: the tooltip content's id). */
    'describedBy' => null,
])

@php
    $opensOnHover = $openWith === 'both' || $openWith === 'hover';
    $opensOnClick = $openWith === 'both' || $openWith === 'click';
    $wrapsText = $text && $interactive;
    $targetId = $describedBy ?: $descriptionId;
    // JS expression for the description id: a literal when known server-side,
    // otherwise the id scoped by `x-id` on the parent `tedi-tooltip`.
    $idExpression = $targetId ? \Illuminate\Support\Js::from($targetId) : "\$id('tedi-tooltip')";
@endphp

<tedi-tooltip-trigger
    {{ $attributes->class([
        'tedi-tooltip-trigger--clickable' => $openWith === 'click',
    ]) }}
    x-ref="trigger"
    @if ($interactive && ! $wrapsText)
        x-init="(() => { const c = $el.firstElementChild; if (! c) return; const t = c.matches('button, a[href]') ? c : (c.querySelector('button, a[href], [tabindex]') ?? c); t.setAttribute('aria-describedby', {!! $idExpression !!}) })()"
    @endif
    @if ($opensOnHover)
        x-on:mouseenter="hoverOpen()"
        x-on:mouseleave="hoverClose()"
        x-on:focusin="hoverOpen()"
        x-on:focusout="hoverClose()"
    @endif
    @if ($opensOnClick)
        x-on:click="toggle()"
        x-on:keydown.enter="if (! $event.target.matches('button, a[href]')) { $event.preventDefault(); toggle() }"
        x-on:keydown.space="if (! $event.target.matches('button, a[href]')) { $event.preventDefault(); toggle() }"
    @endif
>
    @if ($wrapsText)
        <span
            class="tedi-tooltip-trigger__text tedi-tooltip-trigger--focus"
            tabindex="0"
            role="button"
            @if ($targetId) aria-describedby="{{ $targetId }}" @else x-bind:aria-describedby="$id('tedi-tooltip')" @endif
        >{{ $slot }}</span>
    @else
        {{ $slot }}
    @endif
</tedi-tooltip-trigger>
