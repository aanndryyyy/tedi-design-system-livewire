@storybook([
    'name' => 'Multiple With Individual Validation',
    'order' => 7,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Laadi failid üles',
        'helperText' => 'Sobimatu fail. Lubatud on ainult .pdf ja .txt failid suurusega kuni 1 KB.',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
        'helperText' => ['control' => 'text'],
    ],
])

@php
    $files = [
        ['name' => 'taotlus_scan_lk_1.pdf'],
        ['name' => 'taotlus_scan_lk_2.pdf'],
        ['name' => 'taotlus_scan_lk_3.pdf'],
        ['name' => 'taotlus_scan_lk_4.pdf'],
        ['name' => 'taotlus_scan_lk_5.pdf', 'is_valid' => false],
    ];
@endphp

{{-- validateIndividually is upstream's client-side flag; the equivalent here is
     the is_valid key each file carries, decided on the server. --}}
<tedi:file-upload
    id="file-upload-multiple-individual-validation"
    name="file-multiple-individual-validation"
    :label="$label"
    accept=".pdf,.txt"
    multiple
    :files="$files"
    :helper="['type' => 'error', 'text' => $helperText]"
/>
