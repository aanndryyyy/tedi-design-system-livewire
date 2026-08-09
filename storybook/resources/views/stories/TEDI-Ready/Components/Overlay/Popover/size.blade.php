@storybook([
    'name' => 'Size',
    'order' => 7,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [],
    'argTypes' => [],
])

@php
    $widths = ['none', 'small', 'medium', 'large'];
    $polarBear = 'Jääkaru (Ursus maritimus) on suur karu, kes elab Arktikas ja selle lähialadel.';
@endphp

<tedi:row :gap="3">
    @foreach ($widths as $width)
        <tedi:col style="display: flex; justify-content: center;">
            <tedi:popover :container-id="'popover-size-'.$width">
                <x-slot:trigger>
                    <tedi:popover-trigger :underline="true">{{ ucfirst($width) }}</tedi:popover-trigger>
                </x-slot:trigger>

                <tedi:popover-content :max-width="$width">{{ $polarBear }}</tedi:popover-content>
            </tedi:popover>
        </tedi:col>
    @endforeach
</tedi:row>
