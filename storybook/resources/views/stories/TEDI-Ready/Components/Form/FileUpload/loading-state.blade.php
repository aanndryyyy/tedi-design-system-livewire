@storybook([
    'name' => 'Loading State',
    'order' => 8,
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
    id="file-upload-loading"
    name="file-loading"
    :label="$label"
    :files="[['name' => 'report.pdf', 'is_loading' => true], ['name' => 'report_1.pdf']]"
/>
