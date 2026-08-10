{{--
    Angular preselects through a ReactiveForms FormGroup; the Blade port takes the
    initial selection as `value` and binds further changes with wire:model.
--}}
@storybook([
    'name' => 'Multiselect Preselected Options',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'inputId' => 'multiselect-1',
        'label' => 'Custom multiselect label',
        'required' => false,
        'placeholder' => 'Select options...',
        'state' => 'default',
        'size' => 'default',
        'multiRow' => false,
        'clearableTags' => false,
        'selectAll' => false,
        'selectableGroups' => false,
        'clearable' => true,
        'disabled' => false,
        'dropdownWidth' => 'trigger',
        'options' => [
            ['value' => 'option1', 'label' => 'Option 1'],
            ['value' => 'option2', 'label' => 'Option 2'],
            ['value' => 'option3', 'label' => 'Option 3'],
            ['value' => 'option4', 'label' => 'Option 4'],
            ['value' => 'option5', 'label' => 'Option 5'],
        ],
        'value' => ['option1', 'option3'],
        'feedbackText' => ['type' => 'hint', 'text' => 'Custom hint for using the multiselect', 'position' => 'left'],
    ],
    'argTypes' => [
        'inputId' => ['control' => 'text'],
        'label' => ['control' => 'text'],
        'required' => ['control' => 'boolean'],
        'placeholder' => ['control' => 'text'],
        'state' => ['control' => 'radio', 'options' => ['error', 'valid', 'default']],
        'size' => ['control' => 'radio', 'options' => ['small', 'default']],
        'multiRow' => ['control' => 'boolean'],
        'clearableTags' => ['control' => 'boolean'],
        'selectAll' => ['control' => 'boolean'],
        'selectableGroups' => ['control' => 'boolean'],
        'clearable' => ['control' => 'boolean'],
        'feedbackText' => ['control' => 'object', 'description' => 'Feedback message configuration'],
        'disabled' => ['control' => 'boolean'],
        'options' => ['control' => 'object', 'description' => 'Story-only: Angular projects <tedi-select-option> children; the Blade port takes them as data (CONVENTIONS.md §5).'],
        'value' => ['control' => 'object', 'description' => 'Story-only: the initially selected values.'],
    ],
])

<tedi:multiselect
    :input-id="$inputId"
    :label="$label"
    :placeholder="$placeholder"
    :required="(bool) $required"
    :state="$state"
    :size="$size"
    :multi-row="(bool) $multiRow"
    :clearable-tags="(bool) $clearableTags"
    :select-all="(bool) $selectAll"
    :selectable-groups="(bool) $selectableGroups"
    :clearable="(bool) $clearable"
    :disabled="(bool) $disabled"
    :dropdown-width="$dropdownWidth"
    :options="$options"
    :value="$value"
    :feedback-text="$feedbackText ?: null"
/>
