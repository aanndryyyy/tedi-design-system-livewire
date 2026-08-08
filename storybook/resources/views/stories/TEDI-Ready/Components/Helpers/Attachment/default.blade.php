@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=30427-154342&m=dev',
    'args' => [
        'name' => 'Kodukülastusakt_Triin.pdf',
        'fileSize' => '',
        'icon' => '',
        'error' => '',
        'invalid' => false,
        'direction' => 'horizontal',
    ],
    'argTypes' => [
        'name' => [
            'control' => 'text',
            'description' => 'File name to display.',
        ],
        'fileSize' => [
            'control' => 'text',
            'description' => 'Pre-formatted file size string (e.g. "0.9 MB").',
        ],
        'icon' => [
            'control' => 'text',
            'description' => 'Leading file-type icon shown before the file name (Material Symbol name).',
        ],
        'error' => [
            'control' => 'text',
            'description' => 'Error feedback message. Switches the visual to the error state (red card, error icon next to the name, feedback text below).',
        ],
        'invalid' => [
            'control' => 'boolean',
            'description' => 'Apply the error visual without rendering feedback text below the card.',
        ],
        'direction' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => 'Content layout direction. `horizontal` keeps name/progress on one row beside the actions; `vertical` stacks them with actions pinned top-right.',
        ],
    ],
])

<tedi:attachment
    :name="$name"
    :file-size="$fileSize ?: null"
    :icon="$icon ?: null"
    :error="$error ?: null"
    :invalid="(bool) $invalid"
    :direction="$direction"
>
    <x-slot:actions>
        <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
    </x-slot:actions>
</tedi:attachment>
