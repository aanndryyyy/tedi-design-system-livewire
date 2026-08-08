{{--
    TEDI Spinner.
    Port of angular/tedi/components/loader/spinner/spinner.component.{ts,html}
--}}
@props([
    /** 10|16|48 */
    'size' => 16,
    /** primary|secondary */
    'color' => 'primary',
    /** Accessible label; when omitted the spinner is aria-hidden. */
    'label' => null,
])

@php
    // sizeConfig from spinner.component.ts
    $sizeConfig = [
        10 => ['strokeWidth' => 4.4, 'r' => 19.8],
        16 => ['strokeWidth' => 5.5, 'r' => 19.25],
        48 => ['strokeWidth' => 3.6667, 'r' => 20.1667],
    ];
    $size = (int) $size;
    $config = $sizeConfig[$size] ?? $sizeConfig[16];
@endphp

{{--
    Root is the Angular selector's element (<tedi-spinner>), not a <span>: other
    components key rules on the element, e.g. tag.component.scss's
    `&__spinner-wrapper { tedi-spinner { --tedi-spinner-size: 12 } }`. The
    `.tedi-spinner { display: flex }` rule supplies the display, so the
    unknown-element `inline` default never applies.
--}}
<tedi-spinner
    role="status"
    aria-live="polite"
    @if ($label) aria-label="{{ $label }}" @else aria-hidden="true" @endif
    {{ $attributes->class([
        'tedi-spinner',
        'tedi-spinner--size-'.$size,
        'tedi-spinner--color-'.$color,
    ]) }}
>
    <svg viewBox="0 0 44 44" aria-hidden="true">
        <circle class="tedi-spinner--inner" cx="22" cy="22" r="{{ $config['r'] }}" fill="none"></circle>
    </svg>
</tedi-spinner>
