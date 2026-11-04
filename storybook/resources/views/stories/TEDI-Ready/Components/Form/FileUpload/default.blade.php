@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4536-78765&m=dev',
    'args' => [
        'id' => 'file-upload',
        'name' => 'file',
        'label' => 'Laadi fail üles',
        'accept' => '',
        'maxSize' => 0,
        'showRestrictions' => true,
        'multiple' => false,
        'disabled' => false,
        'readOnly' => false,
        'size' => 'default',
        'hasClearButton' => true,
    ],
    'argTypes' => [
        'id' => [
            'control' => 'text',
            'description' => 'Unique HTML id for the file input, also used to associate the label and helper text. Generated when omitted.',
        ],
        'name' => [
            'control' => 'text',
            'description' => 'The name of the file input field, used for form submission and accessibility.',
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Visible label for the field.',
        ],
        'accept' => [
            'control' => 'text',
            'description' => 'Specifies the allowed file types, e.g. "image/png, image/jpeg". Sets the native accept attribute.',
        ],
        'maxSize' => [
            'control' => 'number',
            'description' => 'Maximum allowed file size in bytes (e.g. `5 * 1024 ** 2` for 5 MB). Shown in the restrictions hint; validate it on the server. 0 means not set.',
        ],
        'showRestrictions' => [
            'control' => 'boolean',
            'description' => 'Whether to show the auto-generated restrictions hint (allowed types / max size) below the field. Turn it off when the same info is shown elsewhere to avoid a duplicate.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
        'multiple' => [
            'control' => 'boolean',
            'description' => 'Allows multiple file selection.',
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disables the file upload field, preventing interactions.',
        ],
        'readOnly' => [
            'control' => 'boolean',
            'description' => 'Renders the file list only — no input and no buttons.',
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Determines the visual size of the field.',
            'table' => ['defaultValue' => ['summary' => 'default']],
        ],
        'hasClearButton' => [
            'control' => 'boolean',
            'description' => 'Determines whether a clear control is shown to remove all files.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
    ],
])

{{-- The client-side validation pipeline is not ported: the file list arrives
     through :files from the server. See the component's header comment. --}}
<tedi:file-upload
    :id="$id"
    :name="$name"
    :label="$label"
    :accept="$accept ?: null"
    :max-size="(int) $maxSize ?: null"
    :show-restrictions="(bool) $showRestrictions"
    :multiple="(bool) $multiple"
    :disabled="(bool) $disabled"
    :read-only="(bool) $readOnly"
    :size="$size"
    :has-clear-button="(bool) $hasClearButton"
/>
