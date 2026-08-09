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
        'lockScroll' => false,
        'timeoutDelay' => 100,
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
            'description' => 'Should position flip to opposite direction when overflowing screen?',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'dismissible' => [
            'control' => 'boolean',
            'description' => 'Is dismissible by clicking outside of content?',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'hideOnScroll' => [
            'control' => 'boolean',
            'description' => 'Does popover content hide on scroll?',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'withBorder' => [
            'control' => 'boolean',
            'description' => 'Does popover have illustrative border on the arrow side?',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'lockScroll' => [
            'control' => 'boolean',
            'description' => 'Lock scrolling on rest of the page?',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'timeoutDelay' => [
            'control' => 'number',
            'description' => 'Delay time (in ms) for closing popover when not hovering trigger or content.',
            'table' => [
                'category' => 'popover inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '100'],
            ],
        ],
        'maxWidth' => [
            'control' => 'select',
            'options' => ['none', 'small', 'medium', 'large'],
            'description' => 'The width of the popover.',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'PopoverWidth'],
                'defaultValue' => ['summary' => 'small'],
            ],
        ],
        'title' => [
            'control' => 'text',
            'description' => 'Heading title of the content',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'string'],
            ],
        ],
        'showClose' => [
            'control' => 'boolean',
            'description' => 'Should content show close button?',
            'table' => [
                'category' => 'popover-content inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'underline' => [
            'description' => 'Should add underline class to trigger element?',
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
        :lock-scroll="(bool) $lockScroll"
        :timeout-delay="(int) $timeoutDelay"
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
