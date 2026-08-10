@storybook([
    'name' => 'Validation Failed',
    'order' => 5,
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

{{-- Upstream reaches this state through its own validators; here the invalid
     file and the error helper are server-supplied. --}}
<tedi:file-upload
    id="file-upload-validation-failed"
    name="file-validation-failed"
    :label="$label"
    accept=".pdf,.txt"
    multiple
    :files="[['name' => 'taotlus_scan_lk_1.pdf', 'is_valid' => false]]"
    :helper="['type' => 'error', 'text' => $helperText]"
/>
