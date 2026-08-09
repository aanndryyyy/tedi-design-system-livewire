{{--
    Angular's MultipleWithIndividualValidation: validateIndividually=true, so each
    rejected file carries its own message. Here that is the `error` key on the
    files entry, which tedi:attachment renders as feedback text under the row.
--}}
@storybook([
    'name' => 'Multiple With Individual Validation',
    'order' => 9,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-multiple-individual-validation',
        'name' => 'file-multiple-individual-validation',
        'accept' => '.pdf,.txt',
        'maxSize' => 10,
        'multiple' => true,
        'state' => 'invalid',
        'files' => [
            ['name' => 'report.txt', 'size' => 22, 'error' => 'File report.txt is too large. Maximum size: 10 B'],
            ['name' => 'document.pdf', 'size' => 9],
            ['name' => 'taotlus_scan_lk_1.txs', 'size' => 9, 'error' => 'File taotlus_scan_lk_1.txs has the wrong extension. Allowed extensions: .pdf, .txt'],
            ['name' => 'taotlus_scan_lk_2.txt', 'size' => 5],
            ['name' => 'taotlus_scan_lk_3.pdf', 'size' => 18, 'error' => 'File taotlus_scan_lk_3.pdf is too large. Maximum size: 10 B'],
            ['name' => 'taotlus_scan_lk_4.pdf', 'size' => 9],
        ],
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'name' => ['control' => 'text'],
        'accept' => ['control' => 'text'],
        'maxSize' => ['control' => 'number'],
        'multiple' => ['control' => 'boolean'],
        'state' => ['control' => 'radio', 'options' => ['none', 'valid', 'invalid']],
        'files' => ['control' => 'object', 'description' => 'Files listed under the dropzone. Replaces Angular\'s defaultFiles plus its per-file validation result.'],
    ],
])

<tedi:file-dropzone
    :input-id="$inputId"
    :name="$name"
    :accept="$accept"
    :max-size="(int) $maxSize"
    :multiple="(bool) $multiple"
    :state="$state"
    :files="$files"
/>
