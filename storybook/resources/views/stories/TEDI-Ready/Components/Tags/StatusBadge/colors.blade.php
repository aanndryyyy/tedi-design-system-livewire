@storybook([
    'name' => 'Colors',
    'order' => 3,
    'status' => 'stable',
])

@php
    $demoColors = ['neutral', 'brand', 'accent', 'warning', 'danger', 'success'];
    $variants = ['filled', 'filled-bordered', 'bordered'];
    $colorToIconMap = [
        'neutral' => 'edit',
        'brand' => 'send',
        'accent' => 'sync',
        'warning' => 'warning',
        'danger' => 'error',
        'success' => 'check',
    ];
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    @foreach ($demoColors as $color)
        <tedi:row :cols="12" :gap="3">
            <tedi:col :width="2">
                <tedi:text as="p" modifiers="bold" style="text-transform: capitalize;">{{ $color }}</tedi:text>
            </tedi:col>
            <tedi:col :width="10" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                @foreach ($variants as $variant)
                    <tedi:status-badge text="Text" :color="$color" :variant="$variant" />
                    <tedi:status-badge text="Text" :color="$color" :variant="$variant" :icon="$colorToIconMap[$color]" />
                    <tedi:status-badge :color="$color" :variant="$variant" :icon="$colorToIconMap[$color]" />
                @endforeach
            </tedi:col>
        </tedi:row>
    @endforeach
</div>
