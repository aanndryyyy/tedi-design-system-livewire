{{--
    TEDI Col (grid helper).
    Port of angular/tedi/components/helpers/grid/col/col.component.{ts,html}

    See row.blade.php for why "grid" ports as two components. Per
    CONVENTIONS.md §7 the xs/sm/md/lg/xl/xxl breakpoint inputs are not
    ported — only the base props are supported.
--}}
@props([
    /** 1-12 */
    'width' => 1,
    /** start|end|center|stretch */
    'justifySelf' => null,
    /** start|end|center|stretch */
    'alignSelf' => null,
])

<div
    role="presentation"
    {{ $attributes->class([
        'tedi-col',
        'tedi-col--width-'.$width,
        'tedi-col--justify-self-'.$justifySelf => filled($justifySelf),
        'tedi-col--align-self-'.$alignSelf => filled($alignSelf),
    ]) }}
>
    {{ $slot }}
</div>
