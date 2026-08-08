{{--
    TEDI Text.
    Port of angular/tedi/components/base/text/text.component.{ts,html}

    Angular uses the attribute selector [tedi-text], so the consumer picks the
    element. In Blade that becomes the `as` prop.
--}}
@props([
    /** Element to render as. */
    'as' => 'span',
    /**
     * One modifier or a list. Heading modifiers (h1–h6) emit `tedi-text--hN`,
     * everything else emits `text-<modifier>`.
     */
    'modifiers' => null,
    /** primary|secondary|tertiary|white|disabled|brand|success|warning|danger|info|neutral|inherit */
    'color' => 'primary',
])

@php
    $modifierList = is_array($modifiers) ? $modifiers : (filled($modifiers) ? [$modifiers] : []);

    $classes = ['tedi-text--'.$color];

    foreach ($modifierList as $modifier) {
        $classes[] = preg_match('/^h[1-6]$/', $modifier)
            ? 'tedi-text--'.$modifier
            : 'text-'.$modifier;
    }
@endphp

<{{ $as }} {{ $attributes->class($classes) }}>{{ $slot }}</{{ $as }}>
