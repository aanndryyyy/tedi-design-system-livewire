@storybook([
    'name' => 'Disabled',
    'order' => 4,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Laadi fail üles',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

<tedi:file-upload
    id="file-upload-disabled"
    name="file-loading"
    :label="$label"
    :files="[['name' => 'report.pdf']]"
    disabled
/>
