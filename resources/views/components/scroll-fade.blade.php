{{--
    TEDI Scroll Fade.
    Port of angular/src/tedi/components/helpers/scroll-fade/scroll-fade.component.{ts,html}

    Angular toggles the top/bottom fade classes from a ResizeObserver + scroll
    listener. That's genuinely runtime-measured (not a pure CSS media query),
    so per CONVENTIONS.md §8 this ships minimal inline Alpine on top of markup
    that is otherwise identical to Angular: same wrapper/inner structure, same
    class names. `scrolledToTop` / `scrolledToBottom` outputs are not
    re-emitted (CONVENTIONS.md §7.2) — bind your own `x-on:scroll` if needed.

    Since Angular 10.0.0-rc.26 (#924) the slot sits in a content wrapper that
    the ResizeObserver also watches: a scroller capped by `max-height` keeps
    its own size when content is added (a Livewire morph, an `x-for`), so only
    the wrapper's resize reveals it. Upstream's wrapper class
    `tedi-scroll-fade__content` has no rule in the vendored SCSS, so it is
    dropped (CONVENTIONS.md §4) and the wrapper is found by `x-ref`.
--}}
@props([
    /** 0|10|20 */
    'fadeSize' => 20,
    /** top|bottom|both */
    'fadePosition' => 'both',
    /** default|custom */
    'scrollBar' => 'custom',
    /** Accessible label for the scrollable region. */
    'ariaLabel' => null,
])

@php
    $showTop = in_array($fadePosition, ['both', 'top'], true) ? 'true' : 'false';
    $showBottom = in_array($fadePosition, ['both', 'bottom'], true) ? 'true' : 'false';
    $label = $ariaLabel ?: __('tedi::tedi.scroll-fade.label');
@endphp

<div
    x-data="{
        top: false,
        bottom: false,
        update() {
            const el = this.$refs.inner;
            const atTop = el.scrollTop === 0;
            const atBottom = Math.abs(el.scrollHeight - el.scrollTop - el.clientHeight) <= 1;
            this.top = !atTop;
            this.bottom = !atBottom;
        },
    }"
    x-init="update(); const ro = new ResizeObserver(() => update()); ro.observe($refs.inner); ro.observe($refs.tediScrollFadeContent)"
    {{ $attributes->class(['tedi-scroll-fade']) }}
    :class="{
        'tedi-scroll-fade--top-{{ $fadeSize }}': top && {{ $showTop }},
        'tedi-scroll-fade--bottom-{{ $fadeSize }}': bottom && {{ $showBottom }},
    }"
>
    <div
        x-ref="inner"
        x-on:scroll="update()"
        tabindex="0"
        role="group"
        aria-label="{{ $label }}"
        @class([
            'tedi-scroll-fade__inner',
            'tedi-scroll-fade__inner--custom-scroll' => $scrollBar === 'custom',
        ])
    >
        <div x-ref="tediScrollFadeContent">
            {{ $slot }}
        </div>
    </div>
</div>
