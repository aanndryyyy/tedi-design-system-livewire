{{--
    TEDI Affix.
    Port of react/src/tedi/components/misc/affix/affix.tsx (CONVENTIONS.md §13).

    Pins content to the viewport, either `sticky` (scrolls with the page until
    it reaches its offset, then holds) or `fixed` (always held).

    THE CLASS LIST, and why it is asymmetric. Upstream builds it with
    `styles[…]` lookups, so a name the stylesheet does not define resolves to
    `undefined` and is silently dropped. Two consequences are load-bearing and
    are reproduced exactly:

    * There is no `tedi-affix--sticky`. The sheet defines `--fixed` only, so the
      default position emits the base class alone.
    * The `top-*` modifier is emitted **only** in `fixed` mode (upstream guards
      it with `position === 'fixed'`), while `bottom-*`, `left-*` and `right-*`
      are emitted whenever they are set, in either mode. That is upstream's
      condition, not a simplification.

    Offsets come from a fixed scale — 0, 0.5, 1, 1.5, 2 and 'unset' — and the
    modifier spells the decimal point as a hyphen (`1.5` → `--top-1-5`).
    A value outside the scale emits a class the sheet has no rule for, so it is
    rejected and falls back to the default rather than producing dead markup.

    SUBSET — the sticky branch. Upstream mounts `react-sticky-box`, which
    measures its container and writes `position`/`top` inline; TEDI ships no
    positioning CSS for it (`.tedi-affix` is `z-index` and nothing else). Since
    the whole point of the component is lost without it, the port writes the
    equivalent inline style itself — `position: sticky` plus the `top`/`bottom`
    offset — which is what StickyBox produces in the common case. What is NOT
    ported is StickyBox's measurement: it also shrinks the sticky area to the
    parent's box and handles content taller than the viewport by pinning the
    bottom edge instead.

    Consequently `relative` is dropped. Upstream accepts `['header']` (the
    default) to add the rendered height of the layout header to the offset,
    which it reads from a LayoutContext at runtime. Blade has no such
    measurement (CONVENTIONS.md §13.5): add the header height into `top`
    yourself, or override `style="top: …"`, which merges over the computed one.
--}}
@props([
    /** sticky|fixed. */
    'position' => 'sticky',
    /** Offset from the top: 0|0.5|1|1.5|2|'unset'. */
    'top' => 1.5,
    /** Offset from the bottom: 0|0.5|1|1.5|2|'unset'. Null = not set. */
    'bottom' => null,
    /** Offset from the left: 0|0.5|1|1.5|2|'unset'. Null = not set. */
    'left' => null,
    /** Offset from the right: 0|0.5|1|1.5|2|'unset'. Null = not set. */
    'right' => null,
])

@php
    // $top-spacings in affix.module.scss. A value off this scale has no rule,
    // so it would emit dead markup; fall back rather than do that.
    $scale = ['0', '0.5', '1', '1.5', '2', 'unset'];

    $modifier = function ($side, $value) use ($scale) {
        if ($value === null || $value === '') {
            return null;
        }

        $key = is_numeric($value) ? rtrim(rtrim(number_format((float) $value, 1, '.', ''), '0'), '.') : (string) $value;
        $key = $key === '' ? '0' : $key;

        if (! in_array($key, $scale, true)) {
            return null;
        }

        return 'tedi-affix--'.$side.'-'.str_replace('.', '-', $key);
    };

    $isFixed = $position === 'fixed';

    $classes = array_filter([
        'tedi-affix',
        // No `--sticky` rule exists upstream, so only `fixed` emits a modifier.
        $isFixed ? 'tedi-affix--fixed' : null,
        // Upstream applies the top modifier in fixed mode only.
        $isFixed ? $modifier('top', $top) : null,
        $modifier('bottom', $bottom),
        $modifier('left', $left),
        $modifier('right', $right),
    ]);

    // Standing in for react-sticky-box — see the header comment.
    $stickyStyles = [];

    if (! $isFixed) {
        $stickyStyles[] = 'position: sticky';

        if (is_numeric($top)) {
            $stickyStyles[] = 'top: '.$top.'rem';
        }

        if (is_numeric($bottom)) {
            $stickyStyles[] = 'bottom: '.$bottom.'rem';
        }
    }

    // ->style([]) would emit a literal style="" in fixed mode, so only chain
    // it when there is something to write.
    $bag = $attributes->class($classes);
    $bag = $stickyStyles ? $bag->style($stickyStyles) : $bag;
@endphp

<div {{ $bag }}>{{ $slot }}</div>
