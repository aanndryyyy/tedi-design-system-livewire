@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.39?node-id=9938-87564&m=dev',
    'args' => [
        'selected' => '',
        'month' => '',
        'showNavigation' => true,
        'monthMode' => 'dropdown',
        'yearMode' => 'dropdown',
        'startYear' => '',
        'endYear' => '',
        'inputId' => 'date-picker-id-1',
        'inputPlaceholder' => 'Enter date...',
        'inputState' => 'default',
        'inputSize' => 'default',
        'inputDisabled' => false,
        'allowManualInput' => true,
        'showWeekNumbers' => false,
        'currentView' => 'calendar-grid',
        'open' => true,
    ],
    'argTypes' => [
        'selected' => [
            'control' => 'text',
            'description' => 'Selected date. Angular takes a Date; the Blade port takes any date string.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'month' => [
            'control' => 'text',
            'description' => 'Currently shown month. Angular takes a Date; the Blade port takes any date string.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'today'],
            ],
        ],
        'showNavigation' => [
            'control' => 'boolean',
            'description' => 'Shows or hides the calendar navigation controls (previous/next month buttons).',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'monthMode' => [
            'control' => 'radio',
            'options' => ['none', 'label', 'grid', 'dropdown'],
            'description' => 'Month selector mode: none | label | grid | dropdown. The dropdown panel itself is not ported, so dropdown and grid both render only the trigger.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerSelectorMode'],
                'defaultValue' => ['summary' => 'dropdown'],
            ],
        ],
        'yearMode' => [
            'control' => 'radio',
            'options' => ['none', 'label', 'grid', 'dropdown'],
            'description' => 'Year selector mode: none | label | grid | dropdown. The dropdown panel itself is not ported, so dropdown and grid both render only the trigger.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerSelectorMode'],
                'defaultValue' => ['summary' => 'dropdown'],
            ],
        ],
        'startYear' => [
            'control' => 'text',
            'description' => 'Explicit starting year for the year list. If empty, a dynamic fallback range (current year - 100) is used.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'endYear' => [
            'control' => 'text',
            'description' => 'Explicit ending year for the year list. If empty, a dynamic fallback range (current year + 20) is used.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'inputId' => [
            'control' => 'text',
            'description' => 'Input id',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'inputPlaceholder' => [
            'control' => 'text',
            'description' => 'Input placeholder',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'inputState' => [
            'control' => 'radio',
            'options' => ['default', 'error', 'valid'],
            'description' => 'Input state',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerInputState'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'inputSize' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Input size',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerInputSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'inputDisabled' => [
            'control' => 'boolean',
            'description' => 'Is input disabled?',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'allowManualInput' => [
            'control' => 'boolean',
            'description' => 'Is manual typing into input allowed?',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'showWeekNumbers' => [
            'control' => 'boolean',
            'description' => 'Should show week numbers before calendar grid?',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'currentView' => [
            'control' => 'radio',
            'options' => ['calendar-grid', 'month-grid', 'year-grid'],
            'description' => 'Which view the open calendar shows. An Angular runtime signal, surfaced as a prop because Blade renders once on the server.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DatePickerView'],
                'defaultValue' => ['summary' => 'calendar-grid'],
            ],
        ],
        'open' => [
            'control' => 'boolean',
            'description' => 'Whether the calendar panel is open. Angular derives this from the popover; the popover is not ported, so the panel renders inline.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

<tedi:date-picker
    :selected="$selected ?: null"
    :month="$month ?: null"
    :show-navigation="(bool) $showNavigation"
    :month-mode="$monthMode"
    :year-mode="$yearMode"
    :start-year="$startYear !== '' ? (int) $startYear : null"
    :end-year="$endYear !== '' ? (int) $endYear : null"
    :input-id="$inputId ?: null"
    :input-placeholder="$inputPlaceholder ?: null"
    :input-state="$inputState"
    :input-size="$inputSize"
    :input-disabled="(bool) $inputDisabled"
    :allow-manual-input="(bool) $allowManualInput"
    :show-week-numbers="(bool) $showWeekNumbers"
    :current-view="$currentView"
    :open="(bool) $open"
/>
