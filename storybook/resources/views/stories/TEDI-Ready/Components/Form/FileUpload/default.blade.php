@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
    'args' => [
        'inputId' => 'file-upload',
        'name' => 'file',
        'accept' => '',
        'maxSize' => 0,
        'multiple' => false,
        'showRestrictions' => true,
        'error' => '',
        'success' => '',
        'hint' => '',
        'clearable' => '',
        'readOnly' => false,
        'disabled' => false,
        'invalid' => false,
        'size' => '',
        'stacked' => false,
    ],
    'argTypes' => [
        'inputId' => [
            'description' => "Id of the file input, which the field label's `for` points to. Generated when not set.",
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'name' => [
            'description' => '`name` attribute of the file input.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'accept' => [
            'description' => 'Allowed file types as comma-separated extensions and MIME types, e.g. `".pdf,image/*"`. Shown in the hint and set on the input; validate on the server.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'maxSize' => [
            'description' => 'Largest accepted file size, in bytes. Shown in the hint; validate on the server. 0 means not set.',
            'control' => 'number',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'multiple' => [
            'description' => 'Whether more than one file can be selected. Lists even one file as a tag.',
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'showRestrictions' => [
            'description' => 'Shows the allowed types and maximum size in the hint.',
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'error' => [
            'description' => 'Error text. Marks the field invalid.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'success' => [
            'description' => 'Success text, shown while the field has no error.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'hint' => [
            'description' => 'Hint text, shown after the allowed types and maximum size. Use it instead of a projected `tedi:feedback-text`, which would add a second hint.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'clearable' => [
            'description' => "Whether the clear button shows while files are listed. Falls back to the wrapping `tedi:form-field`'s `clearable`.",
            'control' => ['type' => 'boolean'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean | undefined'], 'defaultValue' => ['summary' => 'undefined']],
        ],
        'readOnly' => [
            'description' => 'Shows only the file list, without buttons.',
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'disabled' => [
            'description' => "Disables the field. Combines with the form field's `disabled`.",
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'invalid' => [
            'description' => "Marks the field as invalid. Combines with `error` and the form field's `invalid`.",
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'size' => [
            'description' => 'Field size. Falls back to the size of a wrapping `tedi:form-field`.',
            'control' => ['type' => 'radio'],
            'options' => ['', 'default', 'small'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'FileUploadSize | undefined'], 'defaultValue' => ['summary' => 'undefined']],
        ],
        'stacked' => [
            'description' => "This port's stand-in for upstream's below-`md` layout switch, which is resolved at runtime there: the clear button spelled out as a neutral button, and a default-size add button.",
            'control' => 'boolean',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

{{--
    Angular's argTypes also list `files` (no control), `keepRejectedFiles`
    and `validator`, and the `fileRemove` / `filesChange` outputs. The
    client-side selection pipeline they belong to is not ported — the list is
    server state (see the component's docblock) — so they have no control here.
    `stacked` is added: it is how the below-`md` branch is reached.
--}}
<tedi:form-field>
    <x-slot:label>
        <tedi:form.label :for="$inputId">Label</tedi:form.label>
    </x-slot:label>

    <tedi:file-upload
        :input-id="$inputId"
        :name="$name ?: null"
        :accept="$accept ?: null"
        :max-size="(int) $maxSize ?: null"
        :multiple="(bool) $multiple"
        :show-restrictions="(bool) $showRestrictions"
        :error="$error ?: null"
        :success="$success ?: null"
        :hint="$hint ?: null"
        :clearable="$clearable === '' ? null : (bool) $clearable"
        :read-only="(bool) $readOnly"
        :disabled="(bool) $disabled"
        :invalid="(bool) $invalid"
        :size="$size ?: null"
        :stacked="(bool) $stacked"
    />
</tedi:form-field>
