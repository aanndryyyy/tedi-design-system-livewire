@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [
        'position' => 'top',
        'preventOverflow' => false,
        'dismissible' => true,
        'hideOnScroll' => false,
        'withBorder' => false,
        'withArrow' => true,
        'lockScroll' => false,
        'timeoutDelay' => 100,
        'offset' => 8,
        'maxWidth' => 'small',
        'title' => 'Pealkiri',
        'showClose' => true,
        'interactive' => true,
    ],
    'argTypes' => [
        'position' => [
            'control' => 'select',
            'description' => 'The position of the popover relative to the trigger element.',
            'options' => [
                'auto', 'auto-start', 'auto-end',
                'top', 'top-start', 'top-end',
                'bottom', 'bottom-start', 'bottom-end',
                'right', 'right-start', 'right-end',
                'left', 'left-start', 'left-end',
            ],
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'PopoverPosition'],
                'defaultValue' => ['summary' => 'top'],
            ],
        ],
        'preventOverflow' => [
            'control' => 'boolean',
            'description' => 'Flip to the opposite side when the popover would overflow the screen.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'dismissible' => [
            'control' => 'boolean',
            'description' => 'Close the popover when clicking outside its content.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'hideOnScroll' => [
            'control' => 'boolean',
            'description' => 'Close the popover when the page scrolls.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'withBorder' => [
            'control' => 'boolean',
            'description' => 'Show an illustrative border on the arrow side.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'withArrow' => [
            'control' => 'boolean',
            'description' => 'Show the arrow pointing to the trigger.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'lockScroll' => [
            'control' => 'boolean',
            'description' => 'Lock scrolling on the rest of the page while the popover is open.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'timeoutDelay' => [
            'control' => 'number',
            'description' => 'Delay in ms before the popover closes when neither the trigger nor the content is hovered.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '100'],
            ],
        ],
        'offset' => [
            'control' => 'number',
            'description' => 'Gap in px between the trigger and the popover. The arrow\'s gap is added on top when `withArrow` is `true`.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '8'],
            ],
        ],
        'maxWidth' => [
            'control' => 'text',
            'description' => 'The width of the popover: a preset, or any CSS length for a panel the presets don\'t cover.',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'PopoverWidth'],
                'defaultValue' => ['summary' => 'small'],
            ],
        ],
        'title' => [
            'control' => 'text',
            'description' => 'Title shown at the top of the content.',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'showClose' => [
            'control' => 'boolean',
            'description' => 'Show a close button in the content.',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'underline' => [
            'description' => 'Underline the trigger element.',
            'table' => [
                'category' => 'popover-trigger inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'interactive' => [
            'description' => 'When `false`, the trigger drops its `button` role and dialog ARIA (`aria-haspopup`, `aria-expanded`, `aria-controls`). Use this when the element is only a positioning anchor and an inner control is the real, labelled trigger.',
            'table' => [
                'category' => 'popover-trigger inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
    ],
])

<div style="display: flex; justify-content: center; padding: 6rem 0;">
    <tedi:popover
        container-id="popover-default"
        :position="$position"
        :prevent-overflow="(bool) $preventOverflow"
        :dismissible="(bool) $dismissible"
        :hide-on-scroll="(bool) $hideOnScroll"
        :with-border="(bool) $withBorder"
        :with-arrow="(bool) $withArrow"
        :lock-scroll="(bool) $lockScroll"
        :timeout-delay="(int) $timeoutDelay"
        :offset="(int) $offset"
        :labelled-by="$title ? 'popover-default_title' : null"
    >
        <x-slot:trigger>
            <tedi:popover-trigger
                tag="button"
                :interactive="(bool) $interactive"
                class="tedi-button tedi-button--primary tedi-button--default tedi-button--pl tedi-button--pr"
            >Popover Trigger</tedi:popover-trigger>
        </x-slot:trigger>

        <tedi:popover-content
            :max-width="$maxWidth"
            :title="$title"
            :show-close="(bool) $showClose"
        >Jääkaru (Ursus maritimus) on suur karu, kes elab Arktikas ja selle lähialadel.</tedi:popover-content>
    </tedi:popover>
</div>
