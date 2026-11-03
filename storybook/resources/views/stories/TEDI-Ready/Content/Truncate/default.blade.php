{{--
    Angular's maxLength control is an object (number or breakpoint object);
    breakpoint objects are not ported (CONVENTIONS.md §7), so it is a number.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.75.90?node-id=10317-3250&m=dev',
    'args' => [
        'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras quam sapien, ultrices quis suscipit in, sodales vel justo. Curabitur lobortis quam dolor, vel dapibus dui pellentesque in. Maecenas auctor lacus elit, a vulputate ante cursus vitae. Proin mollis auctor ipsum nec malesuada. Aliquam turpis dui, dictum sit amet eros ac, iaculis mollis diam. Vivamus sed nulla ut justo fermentum ornare. Nam iaculis accumsan euismod. Sed efficitur risus a nisl interdum, placerat tincidunt velit sollicitudin. Donec ac tincidunt lorem, id ornare felis. Vestibulum sed lacinia diam. Donec vehicula mi maximus purus sollicitudin, at pulvinar lacus placerat.',
        'maxLength' => 200,
        'ellipsis' => '...',
        'expandable' => true,
        'expanded' => false,
    ],
    'argTypes' => [
        'text' => [
            'description' => 'Text to display and truncate when it exceeds `maxLength`.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'maxLength' => [
            'description' => 'Maximum characters shown while collapsed, excluding the ellipsis. Accepts a number or a breakpoint object.',
            'control' => 'number',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number | BreakpointObject<number>'],
                'defaultValue' => ['summary' => '200'],
            ],
        ],
        'ellipsis' => [
            'description' => 'Text appended when content is truncated.',
            'control' => 'text',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'string'],
                'defaultValue' => ['summary' => '...'],
            ],
        ],
        'expandable' => [
            'description' => 'Shows an expand/collapse button when the text exceeds `maxLength`. When false, `expanded` can still be controlled programmatically.',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'expanded' => [
            'description' => 'Whether to show the full text. Supports two-way binding with `[(expanded)]`.',
            'control' => 'boolean',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

<tedi:truncate
    :text="$text"
    :max-length="(int) $maxLength"
    :ellipsis="$ellipsis"
    :expandable="(bool) $expandable"
    :expanded="(bool) $expanded"
/>
