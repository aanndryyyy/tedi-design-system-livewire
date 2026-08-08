@storybook([
    'name' => 'With File Size',
    'order' => 4,
    'status' => 'stable',
    'args' => [
        'name' => 'Kodukülastusakt_Triin.pdf',
        'fileSize' => '0,9 MB',
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
    ],
])

<tedi:attachment :name="$name" :file-size="$fileSize ?: null">
    <x-slot:actions>
        <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
    </x-slot:actions>
</tedi:attachment>
