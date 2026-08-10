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
    :multiple="(bool) $multiple"
    :disabled="(bool) $disabled"
    :read-only="(bool) $readOnly"
    :size="$size"
    :has-clear-button="(bool) $hasClearButton"
/>
