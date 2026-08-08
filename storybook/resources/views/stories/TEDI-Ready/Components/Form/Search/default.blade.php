@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.83?node-id=4620-82860&m=dev',
    'args' => [
        'inputId' => 'search-default',
        'label' => 'Otsing',
        'value' => '',
        'placeholder' => 'Otsi nime või märksõna järgi',
        'size' => 'default',
        'clearable' => true,
        'searchIcon' => 'search',
        'disabled' => false,
        'button' => null,
        'feedbackText' => null,
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'inputId' => [
            'description' => 'Unique identifier for the input element, used to associate the label.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'label' => [
            'description' => 'Visible label text. When omitted, provide `ariaLabel` for accessibility.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'value' => [
            'description' => 'Value of the search input. Bind it live with `wire:model`.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => ''],
            ],
        ],
        'placeholder' => [
            'description' => 'Placeholder text for the search input.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'size' => [
            'description' => 'Size of the search field.',
            'control' => ['type' => 'radio'],
            'options' => ['default', 'small', 'large'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'SearchSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'clearable' => [
            'description' => 'Whether the input shows a clear button once it has a value.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'searchIcon' => [
            'description' => 'Icon shown inside the input. Ignored when `button` is set.',
            'control' => ['type' => 'object'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string | FormFieldIcon']],
        ],
        'disabled' => [
            'description' => 'Whether the search field is disabled.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'button' => [
            'description' => 'When set, renders a trailing search button and hides the inline icon.',
            'control' => ['type' => 'object'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'SearchButton']],
        ],
        'feedbackText' => [
            'description' => 'FeedbackText component inputs (hint / validation message).',
            'control' => ['type' => 'object'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'array']],
        ],
        'ariaLabel' => [
            'description' => 'Accessible name for the search region. Falls back to `label`, then `placeholder`, then the translated "search" label.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

{{--
    Angular's argTypes also expose the `searchEvent` and `clear` output()s.
    output()s are not re-emitted by this port (CONVENTIONS.md §7 item 2,
    CONTRACT.md §5), so they are dropped rather than faked as actions — a
    consumer binds `wire:keydown.enter` on the input via $attributes,
    `buttonAttributes` on the search button and `clearAttributes` on the clear
    button instead.
--}}
<tedi:search
    :input-id="$inputId"
    :label="$label ?: null"
    :value="$value"
    :placeholder="$placeholder"
    :size="$size"
    :clearable="(bool) $clearable"
    :search-icon="$searchIcon"
    :disabled="(bool) $disabled"
    :button="$button"
    :feedback-text="$feedbackText"
    :aria-label="$ariaLabel ?: null"
/>
