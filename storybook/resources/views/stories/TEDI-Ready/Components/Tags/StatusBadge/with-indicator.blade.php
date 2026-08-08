@storybook([
    'name' => 'With Indicator',
    'order' => 4,
    'status' => 'stable',
])

@php
    $statuses = ['inactive', 'success', 'warning', 'danger'];
    $variants = ['filled', 'filled-bordered', 'bordered'];
    $statusToIconMap = [
        'inactive' => 'edit',
        'success' => 'send',
        'warning' => 'sync',
        'danger' => 'error',
    ];
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    @foreach ($statuses as $status)
        <tedi:row :cols="12" :gap="3">
            <tedi:col :width="2">
                <tedi:text as="p" modifiers="bold" style="text-transform: capitalize;">{{ $status }}</tedi:text>
            </tedi:col>
            <tedi:col :width="10" style="display: flex; flex-wrap: wrap; gap: 1rem;">
                @foreach ($variants as $variant)
                    <tedi:status-badge text="Text" :status="$status" :variant="$variant" />
                    <tedi:status-badge text="Text" :status="$status" :variant="$variant" :icon="$statusToIconMap[$status]" />
                    <tedi:status-badge :status="$status" :variant="$variant" :icon="$statusToIconMap[$status]" />
                @endforeach
            </tedi:col>
        </tedi:row>
    @endforeach
</div>
