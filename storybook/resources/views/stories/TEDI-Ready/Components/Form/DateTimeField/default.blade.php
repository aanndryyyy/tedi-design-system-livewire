@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=7895-221619&m=dev',
    'args' => [
        'display' => '01.01.2026 10:00',
        'placeholder' => 'pp.kk.aaaa hh:mm',
        'layout' => 'side-by-side',
        'mode' => 'single',
        'open' => true,
        'disabled' => false,
        'required' => false,
    ],
    'argTypes' => [
        'display' => [
            'control' => 'text',
            'description' => "Formatted text shown in the input. Stands in for upstream's parsed inputText — format the value however your application wants and pass the result.",
        ],
        'placeholder' => ['control' => 'text'],
        'layout' => [
            'control' => 'radio',
            'options' => ['side-by-side', 'multi-step'],
            'description' => 'Controls how the calendar and time picker are arranged in the popup. Ignored in range mode, which has its own layout.',
            'table' => ['defaultValue' => ['summary' => 'side-by-side']],
        ],
        'mode' => [
            'control' => 'radio',
            'options' => ['single', 'range'],
            'description' => 'single picks one date and time; range picks a from/to pair, each with its own time picker.',
            'table' => ['defaultValue' => ['summary' => 'single']],
        ],
        'open' => [
            'control' => 'boolean',
            'description' => "Whether the popup is rendered. It stands in for upstream's open state — the panel renders inline rather than in a floating portal.",
        ],
        'disabled' => ['control' => 'boolean'],
        'required' => ['control' => 'boolean'],
    ],
])

{{-- Selection state is the consumer's: the panel renders what you pass, and
     nothing here writes back. See the component's header comment. --}}
<tedi:date-time-field
    :display="$display"
    :placeholder="$placeholder"
    :layout="$layout"
    :mode="$mode"
    :open="(bool) $open"
    :disabled="(bool) $disabled"
    :required="(bool) $required"
/>
