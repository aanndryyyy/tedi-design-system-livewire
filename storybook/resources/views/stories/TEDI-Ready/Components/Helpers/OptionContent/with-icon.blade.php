@storybook([
    'name' => 'With Leading Icon',
    'order' => 6,
    'status' => 'stable',
])

@php
    $devices = ['computer' => 'Desktop', 'smartphone' => 'Phone', 'tablet_mac' => 'Tablet'];
@endphp

<div class="flex flex-column gap-2">
    @foreach ($devices as $icon => $label)
        <tedi:option-content>
            <x-slot:icon><tedi:icon :name="$icon" :size="18" /></x-slot:icon>
            <tedi:option-content-label>{{ $label }}</tedi:option-content-label>
        </tedi:option-content>
    @endforeach
</div>
