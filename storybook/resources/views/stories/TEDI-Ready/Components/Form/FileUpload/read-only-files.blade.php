@storybook([
    'name' => 'Read Only Files',
    'order' => 10,
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
    id="file-upload-read-only"
    name="file-loading"
    :label="$label"
    read-only
    :files="[['name' => 'report.pdf'], ['name' => 'report_1.pdf'], ['name' => 'report_2.pdf']]"
/>
