@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'args' => [
        'view' => 'days',
        'mode' => 'single',
        'selectionLevel' => 'days',
        'monthYearSelectType' => 'dropdown',
        'showOutsideDays' => true,
        'showWeekNumbers' => false,
        'showNavigation' => true,
        'bordered' => true,
        'numberOfMonths' => 1,
        'required' => false,
        'inputDisabled' => false,
        'firstDayOfWeek' => 1,
    ],
    'argTypes' => [
        'view' => [
            'control' => 'radio',
            'options' => ['days', 'months', 'years'],
            'description' => 'Active view (two-way). `days` shows the day grid, `months` the month grid, `years` the year grid. Driven by `selectionLevel` and header navigation, but can be set directly to open on a specific level.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'CalendarView'],
                'defaultValue' => ['summary' => 'days'],
            ],
        ],
        'mode' => [
            'control' => 'radio',
            'options' => ['single', 'multiple', 'range'],
            'description' => 'Selection mode. `single` selects one date, `multiple` toggles dates in an array, `range` builds a `{ from, to }` range across two clicks.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DateFieldMode'],
                'defaultValue' => ['summary' => 'single'],
            ],
        ],
        'selectionLevel' => [
            'control' => 'radio',
            'options' => ['days', 'months', 'years'],
            'description' => 'Lowest level the user can commit to. `days` shows the day grid as the final step; `months` and `years` commit at that level instead.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'CalendarView'],
                'defaultValue' => ['summary' => 'days'],
            ],
        ],
        'monthYearSelectType' => [
            'control' => 'radio',
            'options' => ['dropdown', 'grid', 'static'],
            'description' => 'How the header exposes month/year picking. `dropdown` shows two dropdowns; `grid` switches the body to a month or year grid when the header label is clicked; `static` renders just the label with no interactive picker — only prev/next chevrons can change the month. In this Blade port `dropdown` renders the trigger only; the listbox panel is not ported.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => '"dropdown" | "grid" | "static"'],
                'defaultValue' => ['summary' => 'dropdown'],
            ],
        ],
        'showOutsideDays' => [
            'control' => 'boolean',
            'description' => "Render the trailing/leading days from the adjacent month inside the current month's grid.",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'showWeekNumbers' => [
            'control' => 'boolean',
            'description' => 'Render the ISO week number column at the start of each row.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'showNavigation' => [
            'control' => 'boolean',
            'description' => 'Show the previous/next navigation buttons in the header.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'bordered' => [
            'control' => 'boolean',
            'description' => 'Render the calendar with its own outer border and rounded corners. Disable when embedding inside a surface that already has a border (e.g. the DateField overlay).',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'numberOfMonths' => [
            'control' => 'number',
            'description' => 'How many consecutive months to render side by side. Useful for date-range selection.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'required' => [
            'control' => 'boolean',
            'description' => "When `mode='multiple'`, prevents clearing the last selected date — at least one date must remain.",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'inputDisabled' => [
            'control' => 'boolean',
            'description' => 'Disables all interactions. Combines with the reactive-forms disabled state.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'firstDayOfWeek' => [
            'control' => 'number',
            'description' => 'First column of the day grid: 0 = Sunday … 6 = Saturday. Replaces Angular\'s locale-derived first day of week, which needed `Intl`.',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
    ],
])

<tedi:calendar
    :current-month="date('Y-m-01')"
    :view="$view"
    :mode="$mode"
    :selection-level="$selectionLevel"
    :month-year-select-type="$monthYearSelectType"
    :show-outside-days="(bool) $showOutsideDays"
    :show-week-numbers="(bool) $showWeekNumbers"
    :show-navigation="(bool) $showNavigation"
    :bordered="(bool) $bordered"
    :number-of-months="(int) $numberOfMonths"
    :required="(bool) $required"
    :input-disabled="(bool) $inputDisabled"
    :first-day-of-week="(int) $firstDayOfWeek"
/>
