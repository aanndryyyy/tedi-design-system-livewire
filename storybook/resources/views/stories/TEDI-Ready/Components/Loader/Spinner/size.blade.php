@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'color' => 'primary',
        'label' => 'Loading...',
    ],
    'argTypes' => [
        'color' => [
            'control' => 'radio',
            'options' => ['primary', 'secondary'],
            'description' => 'Specifies the color theme of the spinner.',
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Provides a text label for screen readers to announce the spinners purpose or status.',
        ],
    ],
])

@php
    $sizes = [10, 16, 48];
@endphp

<div style="display: flex; flex-direction: column;">
    @foreach ($sizes as $i => $size)
        <div style="padding: 14px 16px; display: grid; grid-template-columns: repeat(2, 1fr); align-items: center; {{ $i < count($sizes) - 1 ? 'border-bottom: 1px solid var(--general-border-primary);' : '' }}">
            <div>{{ $size }}</div>
            <tedi:spinner :size="$size" :color="$color" :label="$label ?: null" />
        </div>
    @endforeach
</div>
