@storybook([
    'name' => 'With Hint',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Laadi fail üles',
        'helperText' => 'JPG, PNG, PDF suurusega kuni 0.001 MB.',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'helperText' => ['control' => 'text'],
    ],
])

<tedi:file-upload
    id="file-upload"
    name="file"
    :label="$label"
    :helper="['text' => $helperText]"
/>
