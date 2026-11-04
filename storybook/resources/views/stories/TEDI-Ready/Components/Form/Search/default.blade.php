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
        'showClearOnInteraction' => false,
        'searchIcon' => 'search',
        'disabled' => false,
        'button' => '',
        'feedbackText' => '',
        'ariaLabel' => '',
        'suggestions' => '',
        'bindLabel' => 'label',
        'bindDescription' => '',
        'bindDisabled' => 'disabled',
        'noResultsText' => '',
        'loadingText' => '',
        'minQueryLength' => 0,
        'loading' => false,
        'panelOpen' => false,
        'hideOnScroll' => false,
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
        'showClearOnInteraction' => [
            'description' => 'Show the clear button only on hover or focus when the search field has a value. Requires clearable.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
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
        'suggestions' => [
            'description' => 'Suggestions to show in the panel, already filtered by the consumer. Bind an empty array to keep combobox behaviour while nothing matches; leave unbound for a plain search field.',
            'control' => ['type' => 'object'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'array | null']],
        ],
        'bindLabel' => [
            'description' => 'Property holding the display label when suggestions are objects.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => 'label'],
            ],
        ],
        'bindDescription' => [
            'description' => 'Property holding a secondary line shown under the label. Unset by default, so rows are a single line.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'bindDisabled' => [
            'description' => 'Property marking a suggestion as unselectable. Disabled rows are greyed out and skipped by keyboard navigation.',
            'control' => ['type' => 'text'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => 'disabled'],
            ],
        ],
        'noResultsText' => [
            'description' => 'Overrides the translated "no results" row.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'loadingText' => [
            'description' => 'Overrides the translated loading row.',
            'control' => ['type' => 'text'],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'resultsCountText' => [
            'description' => 'Overrides the translated screen-reader result count: a Closure taking the count, or a string with a :count placeholder.',
            'control' => ['type' => 'text', 'disable' => true],
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'Closure | string']],
        ],
        'minQueryLength' => [
            'description' => 'Characters required before the panel opens. Below it nothing is shown, not even the no-results row.',
            'control' => ['type' => 'number'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '0'],
            ],
        ],
        'loading' => [
            'description' => 'Shows a loading row instead of results.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'panelOpen' => [
            'description' => 'Whether the suggestion panel is open. The initial state only: afterwards it lives in the browser.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'hideOnScroll' => [
            'description' => 'Closes the suggestion panel when the page (or a scrollable ancestor) scrolls. Scrolling the option list itself keeps the panel open.',
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
    Angular's argTypes also expose the `searchEvent`, `suggestionSelect` and
    `clear` output()s. They are not Storybook actions here (CONVENTIONS.md §7
    item 2, CONTRACT.md §5): `clear` is wired through `clearAttributes`; in
    autocomplete mode `searchEvent` and `suggestionSelect` arrive as the DOM
    events `tedi-search:search` and `tedi-search:select` on the input (see
    search.blade.php). `resultsCountText` takes a Closure, which a Storybook
    control cannot supply, so it is documented but has no control.
--}}
<tedi:search
    :input-id="$inputId"
    :label="$label ?: null"
    :value="$value"
    :placeholder="$placeholder"
    :size="$size"
    :clearable="(bool) $clearable"
    :show-clear-on-interaction="(bool) $showClearOnInteraction"
    :search-icon="$searchIcon"
    :disabled="(bool) $disabled"
    :button="is_array($button) ? $button : ($button ?: null)"
    :feedback-text="$feedbackText ?: null"
    :aria-label="$ariaLabel ?: null"
    :suggestions="is_array($suggestions) ? $suggestions : null"
    :bind-label="$bindLabel ?: 'label'"
    :bind-description="$bindDescription ?: null"
    :bind-disabled="$bindDisabled ?: 'disabled'"
    :no-results-text="$noResultsText ?: null"
    :loading-text="$loadingText ?: null"
    :min-query-length="(int) $minQueryLength"
    :loading="(bool) $loading"
    :panel-open="(bool) $panelOpen"
    :hide-on-scroll="(bool) $hideOnScroll"
/>
