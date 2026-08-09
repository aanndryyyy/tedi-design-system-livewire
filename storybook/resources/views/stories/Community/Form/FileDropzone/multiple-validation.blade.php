{{--
    Angular's MultipleValidation: validateIndividually=false, so the messages are
    aggregated into one line under the list instead of appearing per row. Here
    that is the `error` prop.
--}}
@storybook([
    'name' => 'Multiple Validation',
    'order' => 8,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-multiple-validation',
        'name' => 'file-multiple-validation',
        'accept' => '.txt',
        'maxSize' => 10,
        'multiple' => true,
        'state' => 'invalid',
        'error' => 'File document.pdf has the wrong extension. Allowed extensions: .txt',
        'files' => [
            ['name' => 'report.txt', 'size' => 7],
            ['name' => 'document.pdf', 'size' => 9, 'invalid' => true],
            ['name' => 'taotlus_scan_lk_1.pdf', 'size' => 9, 'invalid' => true],
            ['name' => 'taotlus_scan_lk_2.pdf', 'size' => 9, 'invalid' => true],
            ['name' => 'taotlus_scan_lk_3.txt', 'size' => 23, 'invalid' => true],
            ['name' => 'taotlus_scan_lk_4.txt', 'size' => 9],
        ],
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'name' => ['control' => 'text'],
        'accept' => ['control' => 'text'],
        'maxSize' => ['control' => 'number'],
        'multiple' => ['control' => 'boolean'],
        'state' => ['control' => 'radio', 'options' => ['none', 'valid', 'invalid']],
        'error' => ['control' => 'text'],
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
    :error="$error ?: null"
    :files="$files"
/>
