@storybook([
    'name' => 'Tooltip positions',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

@php
    $positions = [
        'auto', 'auto-start', 'auto-end',
        'top', 'top-start', 'top-end',
        'bottom', 'bottom-start', 'bottom-end',
        'right', 'right-start', 'right-end',
        'left', 'left-start', 'left-end',
    ];
@endphp

<tedi:row :cols="3" :gap-y="3" justify-items="center">
    @foreach ($positions as $pos)
        <tedi:col>
            <tedi:tooltip :position="$pos">
                <tedi:tooltip-trigger :text="true">{{ ucfirst($pos) }}</tedi:tooltip-trigger>
                <tedi:tooltip-content>
                    Tooltip content
                </tedi:tooltip-content>
            </tedi:tooltip>
        </tedi:col>
    @endforeach
</tedi:row>
