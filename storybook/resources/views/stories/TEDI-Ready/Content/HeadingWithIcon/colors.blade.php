@storybook([
    'name' => 'Colors',
    'order' => 2,
    'status' => 'stable',
    'design' => 'https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=2137-19827&mode=dev',
    'args' => [
        'label' => 'My family physician',
        'name' => 'assignment_ind',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'name' => ['control' => 'text'],
    ],
])

<tedi:vertical-spacing :size="1">
    <tedi:heading-with-icon :name="$name ?: null" heading-color="brand" icon-color="brand">{{ $label }}</tedi:heading-with-icon>
    <tedi:heading-with-icon :name="$name ?: null">{{ $label }}</tedi:heading-with-icon>
</tedi:vertical-spacing>
