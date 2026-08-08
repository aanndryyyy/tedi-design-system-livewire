{{--
    TEDI Accordion Item Content.
    Port of angular/tedi/components/content/accordion/accordion-item-content/accordion-item-content.component.{ts,html}

    `expanded` is read from the enclosing tedi-accordion-item's Alpine scope.
    See accordion-item.blade.php for the item-id/ARIA-pairing divergence.
--}}
@aware([
    'itemId' => null,
    'showIconCard' => false,
])
@props([
    'contentClass' => null,
])

@php
    $headerId = $itemId ? $itemId.'-header' : null;
    $contentId = $itemId ? $itemId.'-content' : \Tedi\Livewire\Tedi::id('tedi-accordion-item-content');
@endphp

<div
    id="{{ $contentId }}"
    x-bind:aria-hidden="(! expanded).toString()"
    x-bind:inert="! expanded"
    x-bind:role="expanded ? 'region' : null"
    @if ($headerId) aria-labelledby="{{ $headerId }}" @endif
    {{ $attributes->class([
        'tedi-accordion-item-content',
        'tedi-accordion-item-content--with-icon-card' => (bool) $showIconCard,
        $contentClass => (bool) $contentClass,
    ]) }}
>
    <div class="tedi-accordion-item-content__inner">
        {{ $slot }}
    </div>
</div>
