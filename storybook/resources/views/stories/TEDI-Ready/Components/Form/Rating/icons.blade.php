@storybook([
    'name' => 'Icons',
    'order' => 4,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

@php
    $type = 'icon';
    $max = 5;
    $itemLabels = ['Väga halb', 'Halb', 'Keskmine', 'Hea', 'Väga hea'];
    $rows = array_map(fn ($value) => ['value' => $value, 'label' => $value === 0 ? 'No rating' : $value.' of '.$max], range(0, $max));
@endphp

{{-- Row label sits on top on mobile (so the scale gets the full width) and moves to a left column from md up. --}}
<div class="flex flex-column gap-3">
    @foreach ($rows as $row)
        <div class="flex flex-column flex-md-row gap-3 align-items-start">
            <div style="width: 5rem; flex-shrink: 0"><tedi:text as="p" modifiers="bold">{{ $row['label'] }}</tedi:text></div>
            <tedi:rating :type="$type" :count="$max" :value="$row['value']" :item-labels="$itemLabels" :label="$row['label']" />
        </div>
    @endforeach
    <div class="flex flex-column flex-md-row gap-3 align-items-start">
        <div style="width: 5rem; flex-shrink: 0"><tedi:text as="p" modifiers="bold">Read only</tedi:text></div>
        <div class="flex flex-column gap-2">
            <tedi:rating :type="$type" :count="$max" :read-only="true" :value="3.5" :ratings-count="271" :label="$type.' read-only, with raters'" />
            <tedi:rating :type="$type" :count="$max" :read-only="true" :value="3.5" :ratings-count="271" :show-ratings-count-label="false" :label="$type.' read-only, count only'" />
            <tedi:rating :type="$type" :count="$max" :read-only="true" :value="3.5" :label="$type.' read-only, no count'" />
        </div>
    </div>
</div>
