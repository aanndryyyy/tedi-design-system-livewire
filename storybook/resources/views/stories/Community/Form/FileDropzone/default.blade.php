{{--
    Angular's Default wraps the component in a reactive form and logs the
    control's value. The Blade equivalent of that binding is `wire:model` on the
    component, which lands on the native <input> — there is nothing to
    demonstrate in a static story, so the form wrapper is dropped and the
    controls below cover the component's own inputs.

    `mode`, `validators` and `validateIndividually` are not declared by the Blade
    component (see its header comment), so they get no controls here.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.13.19?node-id=12457-128384&m=dev',
    'args' => [
        'accept' => '',
        'maxSize' => 0,
        'sizeDisplayStandard' => 'IEC',
        'multiple' => false,
        'uploadFolder' => false,
        'inputId' => 'file-dropzone-form-control',
        'name' => 'file-form-control',
        'label' => '',
        'state' => 'none',
        'hasError' => false,
        'disabled' => false,
        'error' => '',
    ],
    'argTypes' => [
        'accept' => [
            'control' => 'text',
            'description' => 'Specifies the allowed file types (e.g., "image/png, image/jpeg"). Does not validate the contents of the file, only the file extension. txt will not work, use .txt instead, the . is required.',
        ],
        'maxSize' => [
            'control' => 'number',
            'description' => 'The maximum file size allowed for upload, in bytes. Shown in the hint; not enforced client-side.',
        ],
        'sizeDisplayStandard' => [
            'control' => 'radio',
            'options' => ['SI', 'IEC'],
            'description' => 'Specifies the standard to use when displaying file sizes or maximum file size. SI units are in multiples of 1000 (e.g., 1 kB = 1000 bytes). IEC units are in multiples of 1024 (e.g., 1 KiB = 1024 bytes).',
        ],
        'multiple' => [
            'control' => 'boolean',
            'description' => 'Determines if multiple files can be uploaded at once via the file picker.',
        ],
        'uploadFolder' => [
            'control' => 'boolean',
            'description' => 'If true, allows uploading folders instead of just files. This enables the user to select a folder and upload all its contents. Default file browser behaviour will prevent upload of files in this state.',
        ],
        'inputId' => [
            'control' => 'text',
            'description' => 'The unique identifier for the file dropzone component. Used for form control binding and accessibility.',
        ],
        'name' => [
            'control' => 'text',
            'description' => 'The name of the file input, used for form submission and identification.',
        ],
        'label' => [
            'control' => 'text',
            'description' => 'The text label displayed for the file dropzone, providing context for users',
        ],
        'state' => [
            'control' => 'radio',
            'options' => ['none', 'valid', 'invalid'],
            'description' => 'Validated state border. Replaces Angular\'s internal uploadState, which its async validators wrote.',
        ],
        'hasError' => [
            'control' => 'boolean',
            'description' => 'If true, shows the file dropzone as in a erroring state with red border. Overrides default validation state.',
        ],
        'disabled' => [
            'control' => 'boolean',
        ],
        'error' => [
            'control' => 'text',
            'description' => 'Aggregated error message under the list. Replaces Angular\'s uploadError.',
        ],
    ],
])

<tedi:file-dropzone
    :accept="$accept"
    :max-size="(int) $maxSize"
    :size-display-standard="$sizeDisplayStandard"
    :multiple="(bool) $multiple"
    :upload-folder="(bool) $uploadFolder"
    :input-id="$inputId ?: null"
    :name="$name ?: null"
    :label="$label ?: null"
    :state="$state"
    :has-error="(bool) $hasError"
    :disabled="(bool) $disabled"
    :error="$error ?: null"
/>
