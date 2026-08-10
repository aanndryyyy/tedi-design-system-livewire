@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'direction' => 'both',
    ],
    'argTypes' => [
        'direction' => [
            'control' => 'radio',
            'options' => ['both', 'horizontal', 'vertical'],
            'description' => 'Specifies the axis along which the child element should be stretched. both stretches horizontally and vertically, horizontal stretches the width only, vertical the height only.',
            'table' => ['defaultValue' => ['summary' => 'both']],
        ],
    ],
])

<div style="width: 500px; height: 500px">
    <tedi:stretch-content :direction="$direction">
        <div class="example-box" style="background: var(--tedi-neutral-200); padding: 1rem">Element that gets stretched</div>
    </tedi:stretch-content>
</div>
