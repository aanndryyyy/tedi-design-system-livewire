@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=4620-82915&m=dev',
    'args' => [
        'inputId' => 'date-default',
        'label' => 'Kuupäev',
        'feedback' => 'Vali kuupäev',
        'display' => '',
        'mode' => 'single',
        'size' => 'default',
        'selectionLevel' => 'days',
        'monthYearSelectType' => 'dropdown',
        'placeholder' => '',
        'inputDisabled' => false,
        'readOnly' => false,
        'required' => false,
        'showOutsideDays' => true,
        'showWeekNumbers' => false,
        'enableCalendar' => true,
        'calendarTrigger' => 'button',
        'numberOfMonths' => 1,
        'multiRow' => true,
        'tagEllipsis' => false,
        'isTagRemovable' => true,
        'visibleTagCount' => '',
        'firstDayOfWeek' => 1,
        'open' => false,
    ],
    'argTypes' => [
        'inputId' => [
            'description' => 'Unique ID for label association and accessibility. Bind the sibling `<tedi:form.label :for>` to the same value.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'label' => [
            'table' => ['disable' => true],
        ],
        'feedback' => [
            'table' => ['disable' => true],
        ],
        'display' => [
            'description' => "Formatted text shown in the input. Stands in for Angular's `displayValue()`, which was derived from `formatDate`/`localeCode` — neither is ported, so the consumer formats the value and passes the result.",
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => '""'],
            ],
        ],
        'mode' => [
            'description' => 'Selection mode. `single` selects one date, `multiple` toggles dates in an array, `range` builds a `{ from, to }` range across two clicks.',
            'control' => ['type' => 'radio'],
            'options' => ['single', 'multiple', 'range'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DateFieldMode'],
                'defaultValue' => ['summary' => 'single'],
            ],
        ],
        'multiRow' => [
            'description' => '`multiple` mode tag layout. `true` wraps tags across rows and grows the field height; `false` keeps a single row and collapses overflow into a `+N` counter.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'tagEllipsis' => [
            'description' => "Which end a `multiple`-mode tag label truncates from when it doesn't fit. `false` never truncates; `end` → `05.06…`; `start` → `…06.2026` (keeps the year visible).",
            'control' => ['type' => 'radio'],
            'options' => [false, 'start', 'end'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'TagEllipsis'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'isTagRemovable' => [
            'description' => 'In `multiple` mode, whether tags show a remove button. `false` renders them as read-only chips.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'visibleTagCount' => [
            'description' => "How many `multiple`-mode tags fit on one row when `multiRow` is `false`. Stands in for Angular's `visibleTagsCount`, which was a live width measurement of the rendered tags. Leave empty for \"not measured\" — every tag renders and the field carries `tedi-date-input--tags-measuring`, matching Angular's first paint.",
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number | null'],
                'defaultValue' => ['summary' => 'null'],
            ],
        ],
        'size' => [
            'description' => "Field size — should match the surrounding `tedi-form-field`'s `size`. Declared for API parity only: it emits no class of its own, so set it on `<tedi:form-field>` too.",
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'DateFieldSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'selectionLevel' => [
            'description' => 'Lowest level the user can commit to. `days` shows the day grid as the final step; `months` and `years` commit at that level instead. It also seeds which grid the calendar opens on. Drilling between the grids is a runtime interaction and is not ported.',
            'control' => ['type' => 'radio'],
            'options' => ['days', 'months', 'years'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'CalendarView'],
                'defaultValue' => ['summary' => 'days'],
            ],
        ],
        'monthYearSelectType' => [
            'description' => 'How the popover header exposes month/year picking. `dropdown` shows two dropdowns; `grid` drills into a month/year grid when the header label is clicked. In this Blade port `dropdown` renders the trigger only — the listbox panel needs an overlay component this package does not ship.',
            'control' => ['type' => 'radio'],
            'options' => ['dropdown', 'grid'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => '"dropdown" | "grid"'],
                'defaultValue' => ['summary' => 'dropdown'],
            ],
        ],
        'placeholder' => [
            'description' => 'Placeholder rendered in the input when there is no value.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => '""'],
            ],
        ],
        'inputDisabled' => [
            'description' => 'Disables the field entirely — input, icon button, and calendar.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'readOnly' => [
            'description' => 'Blocks typing into the input but leaves the calendar interactive — useful for guided picking.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'required' => [
            'description' => 'Marks the input as required (sets the native `required` attribute for validation). The asterisk indicator lives on the sibling `<tedi:form.label :required>` — bind it there too, since DateField owns no label.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'showOutsideDays' => [
            'description' => "Render the trailing/leading days from the adjacent month inside the current month's grid.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'showWeekNumbers' => [
            'description' => 'Render an ISO week-number column on the left of the day grid.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'enableCalendar' => [
            'description' => 'Enables the calendar picker UI. When `false`, hides the icon button and disables the popover — the user can only type a date.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'numberOfMonths' => [
            'description' => 'Number of month grids shown side by side. Angular accepts a per-breakpoint object; this port takes the plain base (`xs`) number, since breakpoint props are not ported.',
            'control' => ['type' => 'number', 'min' => 1, 'max' => 4],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'calendarTrigger' => [
            'description' => 'What opens the calendar. `button` opens it from the icon button; `input` also opens it when the text input is focused, which makes the input read-only.',
            'control' => ['type' => 'radio'],
            'options' => ['button', 'input'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => '"input" | "button"'],
                'defaultValue' => ['summary' => 'button'],
            ],
        ],
        'firstDayOfWeek' => [
            'description' => "First column of the calendar's day grid: 0 = Sunday … 6 = Saturday. Replaces Angular's locale-derived first day of week, which needed `Intl`.",
            'control' => ['type' => 'number', 'min' => 0, 'max' => 6],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'open' => [
            'description' => "Whether the calendar panel is shown. Stands in for Angular's runtime overlay open state, which a server render cannot hold.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

{{--
    Angular's argTypes also expose `localeCode` (§0.5), `useNativePicker`,
    `modal`, `fullscreen` (breakpoint props, CONVENTIONS.md §7 item 1),
    `hideOnScroll` / `closeOnSelect` (runtime only), `formatDate` / `parseDate`
    (JS callables) and the matcher machinery (`disabledMatchers`, `minDate`,
    `maxDate`, `disablePast`, `disableFuture`, `availableDays`,
    `unavailableDays`, `shouldDisableMonth`, `shouldDisableYear`, `minYear`,
    `maxYear`, `initialMonth`). All are dropped rather than declared as inert
    controls (CONTRACT.md §5).

    `display`, `visibleTagCount` and `open` are added instead: they stand in for
    Angular signals a server render cannot hold (CONVENTIONS.md §5).
--}}
<tedi:form-field :size="$size">
    <x-slot:label>
        <tedi:form.label :for="$inputId" :size="$size" :required="(bool) $required">{{ $label }}</tedi:form.label>
    </x-slot:label>

    <tedi:date-field
        :input-id="$inputId"
        :display="$display"
        :value="$display ?: null"
        :mode="$mode"
        :size="$size"
        :selection-level="$selectionLevel"
        :month-year-select-type="$monthYearSelectType"
        :placeholder="$placeholder"
        :input-disabled="(bool) $inputDisabled"
        :read-only="(bool) $readOnly"
        :required="(bool) $required"
        :show-outside-days="(bool) $showOutsideDays"
        :show-week-numbers="(bool) $showWeekNumbers"
        :enable-calendar="(bool) $enableCalendar"
        :calendar-trigger="$calendarTrigger"
        :number-of-months="(int) $numberOfMonths"
        :multi-row="(bool) $multiRow"
        :tag-ellipsis="$tagEllipsis"
        :is-tag-removable="(bool) $isTagRemovable"
        :visible-tag-count="$visibleTagCount !== '' ? (int) $visibleTagCount : null"
        :first-day-of-week="(int) $firstDayOfWeek"
        :open="(bool) $open"
    />

    @if ($feedback)
        <x-slot:feedback>
            <tedi:feedback-text :text="$feedback" />
        </x-slot:feedback>
    @endif
</tedi:form-field>
