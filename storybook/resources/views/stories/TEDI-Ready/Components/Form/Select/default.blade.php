@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.38.59?node-id=4449-69807&m=dev',
    'args' => [
        'inputId' => 'select-1',
        'name' => '',
        'label' => 'Label',
        'required' => false,
        'placeholder' => 'Vali...',
        'state' => 'default',
        'size' => 'default',
        'disabled' => false,
        'options' => [
            ['value' => 'tallinn', 'label' => 'Tallinn'],
            ['value' => 'narva', 'label' => 'Narva'],
            ['value' => 'tartu', 'label' => 'Tartu', 'disabled' => true],
            ['value' => 'elva', 'label' => 'Elva'],
            ['value' => 'rakvere', 'label' => 'Rakvere'],
            ['value' => 'haapsalu', 'label' => 'Haapsalu'],
        ],
    ],
    'argTypes' => [
        'inputId' => [
            'control' => 'text',
            'description' => 'Unique identifier for the select input element. Used for label association and accessibility.',
        ],
        'name' => [
            'control' => 'text',
            'description' => 'Name the selection is submitted under in a native form, one entry per selected value. With object options, set `bindValue` to pick what is sent.',
        ],
        'label' => [
            'control' => 'text',
            'description' => 'Label text displayed above the select.',
        ],
        'required' => [
            'control' => 'boolean',
            'description' => 'Whether the field is required.',
        ],
        'placeholder' => [
            'control' => 'text',
            'description' => 'Placeholder text shown when no value is selected.',
        ],
        'state' => [
            'control' => 'radio',
            'options' => ['error', 'valid', 'default'],
            'description' => 'Visual state of the input.',
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['small', 'default'],
            'description' => 'Size variant of the select.',
        ],
        'disabled' => [
            'control' => 'boolean',
            'description' => 'Disables the select.',
        ],
        'options' => [
            'control' => 'object',
            'description' => 'Array of options to display in the dropdown.',
        ],
    ],
])

{{--
    NATIVE-<select> SUBSET — see select.blade.php's header comment and
    CONVENTIONS.md §7 item 4. Angular's Default story exposes many combobox-
    only args (searchable, clearable, allowMultiple, showSelectAll,
    selectableGroups, isTagRemovable, multiRow, tagEllipsis, ellipsis,
    clearSearchOnSelect, dropdownType, maxDropdownHeight, virtualScroll,
    hideOnScroll, tooltip, output()s) that this native-<select> port drops
    entirely — dropped, not declared as args here. `clearable` and
    `showClearOnInteraction` are declared on the component for API parity but
    inert (a native select has no clear button), so they are not args either.
--}}
<tedi:select
    :input-id="$inputId"
    :name="$name ?: null"
    :label="$label"
    :required="(bool) $required"
    :placeholder="$placeholder"
    :state="$state"
    :size="$size"
    :disabled="(bool) $disabled"
    :options="$options"
    bind-label="label"
    bind-value="value"
/>
