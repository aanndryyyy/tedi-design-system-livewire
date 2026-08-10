@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'args' => [
        'label' => 'This text is Sticky in its container!',
        'position' => 'sticky',
        'top' => '1.5',
        'bottom' => '',
        'left' => '',
        'right' => '',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'position' => [
            'control' => 'radio',
            'options' => ['sticky', 'fixed'],
            'description' => 'Position of Affix. Note that sticky emits no modifier class — the stylesheet defines a rule for fixed only.',
            'table' => ['defaultValue' => ['summary' => 'sticky']],
        ],
        'top' => [
            'control' => 'select',
            'options' => ['0', '0.5', '1', '1.5', '2', 'unset'],
            'description' => 'Spacing from the top of the container. The modifier class is emitted in fixed mode only, which is upstream behaviour.',
            'table' => ['defaultValue' => ['summary' => '1.5']],
        ],
        'bottom' => [
            'control' => 'select',
            'options' => ['', '0', '0.5', '1', '1.5', '2', 'unset'],
            'description' => 'Spacing from the bottom of the container. Empty means not set.',
        ],
        'left' => [
            'control' => 'select',
            'options' => ['', '0', '0.5', '1', '1.5', '2', 'unset'],
            'description' => 'Spacing from the left of the container. Empty means not set.',
        ],
        'right' => [
            'control' => 'select',
            'options' => ['', '0', '0.5', '1', '1.5', '2', 'unset'],
            'description' => 'Spacing from the right of the container. Empty means not set.',
        ],
    ],
])

<div style="height: 1500px">
    <div style="height: 600px; margin-top: 100px; border: 1px solid red">
        <tedi:affix
            :position="$position"
            :top="$top"
            :bottom="$bottom ?: null"
            :left="$left ?: null"
            :right="$right ?: null"
        >{{ $label }}</tedi:affix>
    </div>
</div>
