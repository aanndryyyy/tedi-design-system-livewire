@storybook([
    'name' => 'Validation Success',
    'order' => 6,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Laadi fail üles',
        'helperText' => 'Tagasiside tekst',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'helperText' => ['control' => 'text'],
    ],
])

<tedi:file-upload
    id="file-upload-validation-success"
    name="file-validation-success"
    :label="$label"
    accept=".pdf,.txt"
    multiple
    :files="[['name' => 'taotlus_scan_lk_1.pdf']]"
    :helper="['type' => 'valid', 'text' => $helperText]"
/>
