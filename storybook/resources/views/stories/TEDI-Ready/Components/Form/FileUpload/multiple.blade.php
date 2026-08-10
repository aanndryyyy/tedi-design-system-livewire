@storybook([
    'name' => 'Multiple',
    'order' => 9,
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
    id="file-upload-multiple"
    name="file-multiple"
    :label="$label"
    multiple
    :files="[['name' => 'report.pdf'], ['name' => 'report_1.pdf'], ['name' => 'report_2.pdf']]"
    :helper="['text' => $helperText]"
/>
