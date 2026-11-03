{{--
    TEDI Skeleton.
    Port of angular/src/tedi/components/loader/skeleton/skeleton.component.{ts,html}

    Previously ported from React (CONVENTIONS.md §13); Angular now ships it in
    `tedi/`, which is the source from here on (§1).

    A loading placeholder wrapper: the literal `tedi-skeleton` element (§4)
    with `aria-busy="true"`, stacking the `tedi:skeleton-block` children it
    wraps. Use one wrapper per loading region, not one per block. Since the
    skeleton is removed once content arrives, set `aria-busy="false"` yourself
    on the region that swaps skeleton for content.

    Accessibility, as upstream. The `role="status"` live region is rendered
    EMPTY and gains `label` only after `label-delay` ms — screen readers only
    announce a live region they were already tracking, and brief loads stay
    silent. When the skeleton is removed (Alpine's `destroy`, e.g. a Livewire
    morph or an `x-if` swapping it out) and `label` had been announced,
    `completed-label` is announced through a page-level polite live region —
    the stand-in for CDK's `LiveAnnouncer`, which upstream uses because the
    skeleton's own region leaves with it. Both behaviours are inline Alpine
    (§8); without JS the region simply stays empty.

    `label` and `completed-label` default to the translated
    `skeleton.loading` / `skeleton.loading-completed`.
--}}
@props([
    /** Announced once the skeleton has been on screen for `label-delay`. Say what is loading. */
    'label' => null,
    /** Announced when the skeleton is removed, if `label` was announced first. */
    'completedLabel' => null,
    /** Delay in ms before `label` is announced. */
    'labelDelay' => 200,
])

@php
    $scope = \Illuminate\Support\Js::from([
        'label' => $label ?? __('tedi::tedi.skeleton.loading'),
        'completedLabel' => $completedLabel ?? __('tedi::tedi.skeleton.loading-completed'),
        'labelDelay' => (int) $labelDelay,
    ]);
@endphp

<tedi-skeleton
    aria-busy="true"
    {{ $attributes->class(['tedi-skeleton']) }}
    x-data="Object.assign({{ $scope }}, {
        announcement: '',
        init() { this._timer = setTimeout(() => { this.announcement = this.label }, this.labelDelay) },
        destroy() {
            clearTimeout(this._timer);
            if (! this.announcement) return;
            let region = document.getElementById('skeleton-live-announcer');
            if (! region) {
                region = document.createElement('div');
                region.id = 'skeleton-live-announcer';
                region.className = 'sr-only';
                region.setAttribute('aria-live', 'polite');
                region.setAttribute('aria-atomic', 'true');
                document.body.appendChild(region);
            }
            region.textContent = '';
            setTimeout(() => { region.textContent = this.completedLabel }, 100);
        },
    })"
>
    <span class="sr-only" role="status" aria-live="polite" aria-atomic="true" x-text="announcement"></span>
    {{ $slot }}
</tedi-skeleton>
