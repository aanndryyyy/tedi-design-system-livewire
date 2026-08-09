{{--
    Angular's argTypes offer four `variant` options (primary, secondary,
    neutral, success), but FloatingButtonVariant is `"primary" | "secondary"`
    and the vendored SCSS defines colour vars for those two only — the other two
    render as an unstyled base button upstream. The control lists the real union
    (storybook/CONTRACT.md §7: the component source is the authority).
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.15.23?node-id=4515-65391&t=PIbEsGEGsONqRIrN-0',
    'args' => [
        'label' => 'floating button',
        'variant' => 'primary',
        'axis' => 'horizontal',
        'size' => 'default',
        'iconEnd' => '',
        'disabled' => false,
        'pseudoStates' => true,
    ],
    'argTypes' => [
        'label' => [
            'control' => 'text',
            'description' => 'Button text. Angular projects this as ng-content.',
        ],
        'variant' => [
            'control' => 'select',
            'options' => ['primary', 'secondary'],
            'description' => 'Specifies the color theme of the button.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'FloatingButtonVariant'],
            ],
        ],
        'axis' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Button axis, changes the orientation of the button.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'horizontal'],
                'type' => ['summary' => 'FloatingButtonAxis'],
            ],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => 'Button size.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'FloatingButtonSize'],
            ],
        ],
        'iconEnd' => [
            'control' => 'text',
            'description' => 'Material Symbols name rendered after the label. Replaces the projected tedi-icon, which Blade cannot introspect.',
        ],
        'disabled' => [
            'control' => 'boolean',
        ],
        'pseudoStates' => ['table' => ['disable' => true]],
    ],
])

@php
    $states = ['Default', 'Hover', 'Active', 'Focus'];
@endphp

<div style="margin: 2rem; display: flex; flex-direction: column; gap: 2rem;">
    @foreach ($states as $state)
        <div style="display: flex; align-items: center; gap: 1rem;">
            <tedi:text as="p" style="min-width: 5rem;">{{ $state }}</tedi:text>
            <tedi:floating-button
                id="{{ $state }}"
                :variant="$variant"
                :axis="$axis"
                :size="$size"
                :icon-end="$iconEnd ?: null"
                :disabled="(bool) $disabled"
            >{{ $label }}</tedi:floating-button>
        </div>
    @endforeach
</div>
