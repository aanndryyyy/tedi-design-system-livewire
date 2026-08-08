@storybook([
    'name' => 'Icon Colors',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'name' => 'account_circle',
        'size' => 48,
    ],
    'argTypes' => [
        'name' => [
            'control' => 'text',
            'description' => 'Name of the Material Icon <br /> https://fonts.google.com/icons',
        ],
        'size' => [
            'control' => 'select',
            'options' => [8, 12, 16, 18, 24, 36, 48, 'inherit'],
            'description' => 'Size of the icon in pixels.',
        ],
    ],
])

@php
    $colors = ['primary', 'secondary', 'tertiary', 'brand', 'brand-dark', 'success', 'warning', 'warning-dark', 'danger', 'white', 'inherit'];
@endphp

<div>
    <div style="display: flex; flex-direction: column;">
        Outlined
        <div style="display: flex; align-items: center; gap: 1rem;">
            @foreach ($colors as $color)
                <div style="background: {{ $color === 'white' ? 'var(--general-icon-background-brand-primary)' : 'none' }}; border-radius: {{ $color === 'white' ? '4px' : '0px' }}; padding: {{ $color === 'white' ? '16px' : '0px' }};">
                    <tedi:icon :name="$name" :size="$size" :color="$color" />
                </div>
            @endforeach
        </div>
    </div>
    <div style="display: flex; flex-direction: column;">
        Filled
        <div style="display: flex; align-items: center; gap: 1rem;">
            @foreach ($colors as $color)
                <div style="background: {{ $color === 'white' ? 'var(--general-icon-background-brand-primary)' : 'none' }}; border-radius: {{ $color === 'white' ? '4px' : '0px' }}; padding: {{ $color === 'white' ? '16px' : '0px' }};">
                    <tedi:icon :name="$name" :size="$size" variant="filled" :color="$color" />
                </div>
            @endforeach
        </div>
    </div>
</div>
