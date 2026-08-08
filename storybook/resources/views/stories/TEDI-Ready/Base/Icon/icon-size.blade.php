@storybook([
    'name' => 'Icon Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'name' => 'account_circle',
    ],
    'argTypes' => [
        'name' => [
            'control' => 'text',
            'description' => 'Name of the Material Icon <br /> https://fonts.google.com/icons',
        ],
    ],
])

@php
    $sizes = [8, 12, 16, 18, 24, 36, 48, 'inherit'];
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
            <div style="display: flex; {{ $size === 'inherit' ? 'font-size: 60px;' : '' }}">
                <tedi:icon :name="$name" :size="$size" />
                <tedi:icon :name="$name" :size="$size" variant="filled" />
            </div>
        </div>
    @endforeach
</div>
