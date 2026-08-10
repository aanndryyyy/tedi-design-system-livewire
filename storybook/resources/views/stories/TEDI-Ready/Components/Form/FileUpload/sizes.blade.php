@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Silt',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

@php $sizes = ['default', 'small']; @endphp

<div class="example-list">
    @foreach ($sizes as $key => $size)
        <tedi:row class="{{ $key === count($sizes) - 1 ? '' : 'border-bottom' }} padding-14-16">
            <tedi:col>
                <tedi:text modifiers="bold">{{ ucfirst($size) }}</tedi:text>
            </tedi:col>
            <tedi:col>
                <tedi:file-upload id="file-upload-{{ $key }}" name="file" :label="$label" :size="$size" />
            </tedi:col>
        </tedi:row>
    @endforeach
</div>
