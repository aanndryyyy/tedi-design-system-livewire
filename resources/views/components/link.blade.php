{{--
    TEDI Link.
    Port of angular/tedi/components/navigation/link/link.component.{ts,html}

    Angular's selector `[tedi-link]` is an attribute directive applied to a
    consumer-chosen host (`<a>` or `<button>`); the `href` prop picks the tag,
    mirroring button.blade.php.

    `ngAfterContentChecked` wraps bare text child nodes in a classless `<span>`
    so the CSS underline rule (`:not(.tedi-link--no-underline) :not(tedi-icon)`,
    a *descendant element* selector) has an element to match — plain text in
    the host matches nothing, so `underline` would silently do nothing without
    it. `ng-content` can't do this itself; Blade renders once, so per
    CONVENTIONS.md §5 the icon positions become the explicit `icon-start` /
    `icon-end` props (as in button.blade.php) and the slot is always wrapped.

    Divergence: the vendored SCSS sizes/colors icons via the element selector
    `tedi-icon`, which never matches our `<span class="tedi-icon">` output
    (same pre-existing mismatch as button.blade.php) — so icon size/color are
    passed explicitly here instead, mirroring the `--icon-03`/`--icon-02`
    tokens the SCSS intended for default/small link icons.
--}}
@props([
    /** default|inverted */
    'variant' => 'default',
    /** default|small */
    'size' => 'default',
    /** Does the link have an underline? */
    'underline' => true,
    /** Target attribute (e.g. "_blank", "_self", "_parent", "_top"). */
    'target' => null,
    /** Renders as <a> when set; otherwise a <button type="button">. */
    'href' => null,
    /** Material Symbols icon name rendered before the label. */
    'iconStart' => null,
    /** Material Symbols icon name rendered after the label. */
    'iconEnd' => null,
])

@php
    $tag = $href ? 'a' : 'button';
    $iconSize = $size === 'small' ? 16 : 18;
@endphp

<{{ $tag }}
    tabindex="0"
    @if ($href)
        href="{{ $href }}"
    @else
        type="button"
    @endif
    @if ($target) target="{{ $target }}" @endif
    {{ $attributes->class([
        'tedi-link',
        'tedi-link--inverted' => $variant === 'inverted',
        'tedi-link--small' => $size === 'small',
        'tedi-link--no-underline' => ! $underline,
    ]) }}
>
    @if ($iconStart)
        <tedi:icon :name="$iconStart" :size="$iconSize" color="inherit" />
    @endif

    <span>{{ $slot }}</span>

    @if ($iconEnd)
        <tedi:icon :name="$iconEnd" :size="$iconSize" color="inherit" />
    @endif

    @if ($target === '_blank')
        <span class="sr-only">{{ __('tedi::tedi.anchor.new-tab') }}</span>
    @endif
</{{ $tag }}>
