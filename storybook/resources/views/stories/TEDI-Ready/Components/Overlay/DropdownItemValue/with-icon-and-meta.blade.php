@storybook([
    'name' => 'With Icon and Meta',
    'order' => 7,
    'status' => 'stable',
])

@php
    $locations = [['Tallinn', '3 timeslots'], ['Tartu', '5 timeslots']];
@endphp

<div class="flex flex-column gap-2">
    @foreach ($locations as [$label, $meta])
        <tedi:dropdown-item-value>
            <x-slot:icon><tedi:icon name="location_on" :size="18" /></x-slot:icon>
            <tedi:dropdown-item-value-label>{{ $label }}</tedi:dropdown-item-value-label>
            <tedi:dropdown-item-value-meta>{{ $meta }}</tedi:dropdown-item-value-meta>
        </tedi:dropdown-item-value>
    @endforeach
</div>
