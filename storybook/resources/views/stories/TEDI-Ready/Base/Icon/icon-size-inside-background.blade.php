@storybook([
    'name' => 'Icon Size Inside Background',
    'order' => 3,
    'status' => 'stable',
    'args' => [
        'background' => 'brand-secondary',
        'color' => 'brand',
    ],
    'argTypes' => [
        'background' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'brand-primary', 'brand-secondary'],
            'description' => 'Background color for the icon (adds a circular background).',
        ],
        'color' => [
            'control' => 'select',
            'options' => ['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white', 'inherit'],
            'description' => 'Color of the icon.',
        ],
    ],
])

@php
    $sizes = [16, 24];
@endphp

<div style="display: flex; flex-direction: column;">
    @foreach ($sizes as $i => $size)
        <div
            style="padding: 14px 16px; display: grid; grid-template-columns: repeat(2, 1fr); align-items: center; {{ $i < count($sizes) - 1 ? 'border-bottom: 1px solid var(--general-border-primary);' : '' }}"
        >
            <div>
                {{ $size }}
                @if ($size === 24)
                    <tedi:text as="small" color="secondary">default</tedi:text>
                @endif
            </div>
            <div style="display: flex;">
                <tedi:icon :name="$size === 16 ? 'info' : 'vaccines'" :size="$size" :color="$color" :background="$background" />
                &nbsp;
                <tedi:icon :name="$size === 16 ? 'info' : 'vaccines'" :size="$size" :color="$color" :background="$background" variant="filled" />
            </div>
        </div>
    @endforeach
</div>
