@storybook([
    'name' => 'Pdf And Txt Only',
    'order' => 11,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'label' => 'Laadi fail üles',
    ],
    'argTypes' => [
        'label' => ['control' => 'text'],
    ],
])

{{-- `accept` still does its real job: it sets the native attribute, so the
     file picker filters. What is not ported is upstream rejecting a file that
     slipped through — validate that on the server. --}}
<tedi:file-upload
    id="file-upload-accepts"
    name="file-accepts"
    :label="$label"
    accept=".pdf,.txt"
/>
