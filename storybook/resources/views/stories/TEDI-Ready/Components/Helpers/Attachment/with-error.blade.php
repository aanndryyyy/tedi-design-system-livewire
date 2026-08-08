@storybook([
    'name' => 'With Error',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

<div style="display:flex;flex-direction:column;gap:.75rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" error="Feedback text">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
</div>
