{{--
    TEDI Header Bottom.
    Port of angular/tedi/components/layout/header/header-bottom/header-bottom.component.ts

    Mobile-only visibility is handled entirely by the vendored SCSS
    (`display: none` from `md` up) — no breakpoint prop is needed.
--}}
@props([])

<div {{ $attributes->class(['tedi-header-bottom']) }}>
    {{ $slot }}
</div>
