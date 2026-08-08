@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
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
            'description' => 'Type of the toast notification determining its color scheme. Angular calls the error variant "error"; the Blade component takes the class-name value `danger`, which is what Angular maps it to.',
            'table' => ['defaultValue' => ['summary' => 'info']],
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Specifies an optional icon to display in the toast notification. See the icon component for more details.',
        ],
        'duration' => [
            'control' => 'number',
            'description' => 'Toast duration in milliseconds. Set to 0 for persistent toast. Only drives whether the progress bar renders — there is no JS timer.',
            'table' => ['defaultValue' => ['summary' => '6000']],
        ],
        'showProgressBar' => [
            'control' => 'boolean',
            'description' => 'Whether to show the progress bar for timed toasts.',
            'table' => ['defaultValue' => ['summary' => 'false']],
        ],
        'pauseOnHover' => [
            'control' => 'boolean',
            'description' => 'Accepted for API parity but inert: it only mattered to the JS timer, which is not ported.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
        'role' => [
            'control' => 'select',
            'options' => ['alert', 'status', 'none'],
            'description' => "The ARIA role of the toast, informing screen readers about the notification's priority.",
            'table' => ['defaultValue' => ['summary' => 'status']],
        ],
    ],
])

{{--
    Angular's Default story renders four buttons that push toasts through
    ToastService, which an overlay-positioned container then places and
    animates. Neither the service nor the overlay is ported (README
    divergences), so the toast is shown here as the static markup it is —
    the consumer places it themselves.
--}}
<tedi:toast
    :title="$title ?: null"
    :type="$type"
    :icon="$icon ?: null"
    :duration="(int) $duration"
    :show-progress-bar="(bool) $showProgressBar"
    :pause-on-hover="(bool) $pauseOnHover"
    :role="$role"
>{{ $content }}</tedi:toast>
