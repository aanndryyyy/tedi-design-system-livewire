{{--
    Angular's statesHorizontal. The Hover / Active / Focus rows are forced by
    storybook-addon-pseudo-states — the `pseudoStates` arg becomes the addon's
    `parameters.pseudo` in .storybook/preview.js, and the `id` on each button is
    what it targets (storybook/CONTRACT.md §5).
--}}
@storybook([
    'name' => 'States Horizontal',
    'order' => 5,
    'status' => 'stable',
    'args' => [
        'axis' => 'horizontal',
        'textOffset' => '0px',
        'pseudoStates' => true,
    ],
    'argTypes' => [
        'axis' => ['table' => ['disable' => true]],
        'textOffset' => ['table' => ['disable' => true]],
        'pseudoStates' => ['table' => ['disable' => true]],
    ],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<div style="display: flex; flex-direction: column; gap: 8rem; margin: 2rem; overflow: visible; white-space: nowrap;">
    @foreach ($states as $state)
        <div>
            <div style="transform: translateY({{ $textOffset }});">{{ $state }}</div>
            <tedi:floating-button :axis="$axis" id="{{ $state }}">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" id="{{ $state }}" icon-end="arrow_upward">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" id="{{ $state }}" icon-only icon-start="arrow_upward" aria-label="Üles" />

            <tedi:floating-button :axis="$axis" id="{{ $state }}" variant="secondary">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" id="{{ $state }}" variant="secondary" icon-end="arrow_upward">Floating Button</tedi:floating-button>
            <tedi:floating-button :axis="$axis" id="{{ $state }}" variant="secondary" icon-only icon-start="arrow_upward" aria-label="Üles" />
        </div>
    @endforeach
</div>
