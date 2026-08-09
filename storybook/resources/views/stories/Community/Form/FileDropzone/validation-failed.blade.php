{{--
    Angular's ValidationFailed: one file rejected by validateFileType. The
    rejection itself is the server's job here, so the outcome is expressed as an
    `error` on the files entry plus `state="invalid"` on the dropzone.
--}}
@storybook([
    'name' => 'Validation Failed',
    'order' => 7,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-validation-failed',
        'name' => 'file-validation-failed',
        'accept' => '.pdf,.txt',
        'maxSize' => 1000000,
        'multiple' => true,
        'state' => 'invalid',
        'files' => [
            ['name' => 'invalid_file.pdz', 'size' => 12480, 'error' => 'File invalid_file.pdz has the wrong extension. Allowed extensions: .pdf, .txt'],
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
