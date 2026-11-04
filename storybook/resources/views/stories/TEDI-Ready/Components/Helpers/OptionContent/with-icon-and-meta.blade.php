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
        <tedi:option-content>
            <x-slot:icon><tedi:icon name="location_on" :size="18" /></x-slot:icon>
            <tedi:option-content-label>{{ $label }}</tedi:option-content-label>
            <tedi:option-content-meta>{{ $meta }}</tedi:option-content-meta>
        </tedi:option-content>
    @endforeach
</div>
