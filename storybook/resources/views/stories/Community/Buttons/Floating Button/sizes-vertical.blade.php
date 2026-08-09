{{--
    Angular's sizesVertical: both sizes × (primary, secondary) × (text,
    text + icon, icon only). `textOffset` pushes each size's caption clear of
    the rotated buttons, exactly as upstream.
--}}
@storybook([
    'name' => 'Sizes Vertical',
    'order' => 2,
    'status' => 'stable',
    'args' => [
        'axis' => 'vertical',
        'textOffset' => '30px',
    ],
    'argTypes' => [
        'axis' => ['table' => ['disable' => true]],
        'textOffset' => ['table' => ['disable' => true]],
    ],
])

@php
    $sizes = ['default', 'large'];
@endphp

<div style="display: flex; flex-direction: column; gap: 8rem; margin: 2rem; overflow: visible; white-space: nowrap;">
    @foreach ($sizes as $size)
        <div>
            <div style="transform: translateY({{ $textOffset }});">{{ $size }}</div>
            <tedi:floating-button :axis="$axis" :size="$size">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" :size="$size" icon-end="arrow_upward">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" :size="$size" icon-only icon-start="arrow_upward" aria-label="Üles" />

            <tedi:floating-button :axis="$axis" :size="$size" variant="secondary">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" :size="$size" variant="secondary" icon-end="arrow_upward">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" :size="$size" variant="secondary" icon-only icon-start="arrow_upward" aria-label="Üles" />
        </div>
    @endforeach
</div>
