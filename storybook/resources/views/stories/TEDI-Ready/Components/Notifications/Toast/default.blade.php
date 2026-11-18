@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.30.43?node-id=4511-78722',
    'args' => [
        'title' => 'Teade',
        'content' => 'Toimingu tulemus.',
        'type' => 'info',
        'icon' => '',
        'duration' => 6000,
        'showProgressBar' => false,
        'pauseOnHover' => true,
        'role' => 'status',
    ],
    'argTypes' => [
        'title' => [
            'control' => 'text',
            'description' => 'Title of the toast notification.',
        ],
        'content' => [
            'control' => 'text',
            'description' => 'Toast text content.',
        ],
        'type' => [
            'control' => 'radio',
            'options' => ['info', 'success', 'warning', 'danger'],
            'description' => 'Type of the toast notification determining its color scheme.',
            'table' => ['defaultValue' => ['summary' => 'info']],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Specifies an optional icon to display in the toast notification. See the icon component for more details.',
        ],
        'duration' => [
            'control' => 'number',
            'description' => 'Toast duration in milliseconds. Set to 0 for persistent toast.',
            'table' => ['defaultValue' => ['summary' => '6000']],
        ],
        'showProgressBar' => [
            'control' => 'boolean',
            'description' => 'Whether to show the progress bar for timed toasts.',
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
        'pauseOnHover' => [
            'control' => 'boolean',
            'description' => 'Whether to pause the auto-close timer when hovering over the toast.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
        'role' => [
            'control' => 'select',
            'options' => ['alert', 'status', 'none'],
            'description' => "Politeness of the screen reader announcement. Options: \n - <b>alert</b> for high-priority messages that demand immediate attention. \n - <b>status</b> for less urgent messages providing feedback or updates.\n - <b>none</b> for no announcement.",
            'table' => ['defaultValue' => ['summary' => 'status']],
        ],
    ],
])

{{--
    Upstream's Default renders four buttons that call ToastService; here they
    call `TediToast`, its port (resources/js/src/toast-container.js), and the
    page's `tedi:toast-container` shows the toasts. As upstream, the story's
    args only document the toast's inputs; the buttons do not read them.
--}}
<tedi:toast-container />

<tedi:vertical-spacing :size="0.5" x-data>
    <tedi:row>
        <tedi:col>
            <tedi:button x-on:click="TediToast.success('Notice', 'Something was successful!')">
                Show success toast
            </tedi:button>
        </tedi:col>
    </tedi:row>
    <tedi:row>
        <tedi:col>
            <tedi:button x-on:click="TediToast.warning('Notice', 'Warning!')">
                Show warning toast
            </tedi:button>
        </tedi:col>
    </tedi:row>
    <tedi:row>
        <tedi:col>
            <tedi:button x-on:click="TediToast.danger('Notice', 'Something went wrong!')">Show danger toast</tedi:button>
        </tedi:col>
    </tedi:row>
    <tedi:row>
        <tedi:col>
            <tedi:button x-on:click="TediToast.info('Notice', 'Some info text that can usually be very long!')">Show info toast</tedi:button>
        </tedi:col>
    </tedi:row>
</tedi:vertical-spacing>
