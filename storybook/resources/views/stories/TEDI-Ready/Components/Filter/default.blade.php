@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=4612-83722&m=dev',
    'args' => [
        'text' => 'Teenused',
        'variant' => 'primary',
        'size' => 'default',
        'selected' => false,
        'allowMultiple' => false,
        'options' => [],
        'value' => '',
        'showSearch' => false,
        'searchClearable' => true,
        'clearSearchOnSelect' => false,
        'showSelectAll' => false,
        'showClear' => false,
        'selectAllLabel' => '',
        'clearLabel' => '',
        'preserveLabel' => false,
        'disabled' => false,
    ],
    'argTypes' => [
        'text' => [
            'description' => 'Filter label text',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => "''"],
            ],
        ],
        'variant' => [
            'description' => 'Visual variant of the filter',
            'control' => 'radio',
            'options' => ['primary', 'secondary'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'FilterVariant'],
                'defaultValue' => ['summary' => 'primary'],
            ],
        ],
        'size' => [
            'description' => 'Size of the filter',
            'control' => 'radio',
            'options' => ['default', 'large'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'FilterSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'selected' => [
            'description' => 'Whether the filter is selected (boolean toggle mode, used when no options are provided)',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'allowMultiple' => [
            'description' => 'Multi-select mode opens a dropdown with checkbox options. Value is treated as an array of strings when true',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'value' => [
            'description' => 'Selected value (a string) or values (an array of strings) depending on allowMultiple. Bound with wire:model through x-modelable on the root',
            'control' => false,
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | string array'],
                'defaultValue' => ['summary' => "''"],
            ],
        ],
        'options' => [
            'description' => 'Options for the dropdown. Enables single-select mode, or multiselect when combined with multiselect input',
            'control' => 'object',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'FilterOption array'],
                'defaultValue' => ['summary' => '[]'],
            ],
        ],
        'showSearch' => [
            'description' => 'Show the search field in the dropdown',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'searchClearable' => [
            'description' => 'Whether the dropdown search field has a clear (×) button. Only applies when showSearch is true',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'clearSearchOnSelect' => [
            'description' => 'Clear the search field after an option is selected (or toggled in multi-select)',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'showSelectAll' => [
            'description' => 'Show "Select all" option in the dropdown',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'showClear' => [
            'description' => 'Show "Clear selection" action in the dropdown',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'selectAllLabel' => [
            'description' => 'Override for the "Select all" option label. Defaults to the translated string',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | undefined'],
                'defaultValue' => ['summary' => 'translated'],
            ],
        ],
        'clearLabel' => [
            'description' => 'Override for the "Clear selection" action label. Defaults to the translated string',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | undefined'],
                'defaultValue' => ['summary' => 'translated'],
            ],
        ],
        'preserveLabel' => [
            'description' => 'Keep the filter label as a prefix once a value is selected, e.g. "Teenus: Optometristi vastuvõtt"',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'disabled' => [
            'description' => 'Whether the filter is disabled. Also inherited from a disabled filter group',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

<tedi:filter
    :text="$text"
    :variant="$variant"
    :size="$size"
    :selected="(bool) $selected"
    :allow-multiple="(bool) $allowMultiple"
    :options="$options"
    :value="$value"
    :show-search="(bool) $showSearch"
    :search-clearable="(bool) $searchClearable"
    :clear-search-on-select="(bool) $clearSearchOnSelect"
    :show-select-all="(bool) $showSelectAll"
    :show-clear="(bool) $showClear"
    :select-all-label="$selectAllLabel ?: null"
    :clear-label="$clearLabel ?: null"
    :preserve-label="(bool) $preserveLabel"
    :disabled="(bool) $disabled"
/>
