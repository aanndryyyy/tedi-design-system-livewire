@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=4438-86446&t=lPIIY0laoX80DnVD-4',
    'args' => [
        'title' => 'Pealkiri',
        'type' => 'info',
        'icon' => '',
        'showClose' => false,
        'closeAriaLabel' => '',
        'role' => 'alert',
        'titleElement' => 'h2',
        'size' => 'default',
        'open' => true,
    ],
    'argTypes' => [
        'title' => [
            'control' => 'text',
            'description' => "An optional title for the alert, typically used to summarize the message's purpose. Appears in top of the alert.",
        ],
        'titleElement' => [
            'control' => 'select',
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div'],
            'description' => 'The HTML tag to be used for the alert title. Useful for WCAG compliance.',
        ],
        'type' => [
            'control' => 'radio',
            'options' => ['info', 'success', 'warning', 'danger'],
            'description' => 'Defines the visual and contextual type of the alert. This determines the icon, color, and overall style, making it clear whether the alert is informational, a success message, a warning, or an error.',
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Specifies an optional icon to display in the alert. See the icon component for more details.',
        ],
        'showClose' => [
            'control' => 'boolean',
            'description' => 'If true, a close button will be displayed.',
        ],
        'closeAriaLabel' => [
            'control' => 'text',
            'description' => 'Accessible name for the close button. The title, when set, is its description.',
        ],
        'role' => [
            'control' => 'select',
            'options' => ['alert', 'status', 'none'],
            'description' => "Live-region role of the message. \n - <b>alert</b>: urgent message that appears later (error, needs action). Announced assertively. \n - <b>status</b>: non-urgent message that appears later (saved, info). Announced politely. \n - <b>none</b>: content already on the page when it loads. Some screen readers read a <code>role=\"alert\"</code> present at load immediately.",
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'small'],
            'description' => 'Alert size variant.',
            'table' => [
                'type' => ['summary' => 'AlertSize'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'open' => [
            'control' => 'boolean',
            'description' => 'Is alert open?',
            'table' => [
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
    ],
])

<tedi:alert
    :title="$title ?: null"
    :type="$type"
    :icon="$icon ?: null"
    :show-close="(bool) $showClose"
    :close-aria-label="$closeAriaLabel ?: null"
    :role="$role"
    :title-element="$titleElement"
    :size="$size"
    :open="(bool) $open"
>
    Sisu kirjeldus. <a href="#">Tekstisisene lingi näide</a>
</tedi:alert>
