{{--
    Angular disables the component through its form control's disabled state;
    here `disabled` is a plain prop, so it is set directly.
--}}
@storybook([
    'name' => 'Disabled',
    'order' => 4,
    'status' => 'subset',
    'args' => [
        'inputId' => 'file-dropzone-disabled',
        'name' => 'file-loading',
        'disabled' => true,
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'name' => ['control' => 'text'],
        'disabled' => ['control' => 'boolean'],
    ],
])

<tedi:file-dropzone :input-id="$inputId" :name="$name" :disabled="(bool) $disabled" />
