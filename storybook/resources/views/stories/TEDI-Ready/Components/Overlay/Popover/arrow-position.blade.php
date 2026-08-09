@storybook([
    'name' => 'Arrow Position',
    'order' => 5,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
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
    $polarBear = 'Jääkaru (Ursus maritimus) on suur karu, kes elab Arktikas ja selle lähialadel.';
@endphp

<tedi:row :cols="3" :gap="3">
    @foreach ($positions as $position)
        <tedi:col style="display: flex; justify-content: center;">
            <tedi:popover :position="$position" :container-id="'popover-arrow-'.$position">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">{{ ucfirst($position) }}</tedi:popover-trigger>
                </x-slot:trigger>

                <tedi:popover-content>{{ $polarBear }}</tedi:popover-content>
            </tedi:popover>
        </tedi:col>
    @endforeach
</tedi:row>
