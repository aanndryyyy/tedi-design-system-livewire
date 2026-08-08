@storybook([
    'name' => 'Read Only',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{-- Read-only attachments expose only a download action — no delete button. --}}
<div style="display:flex;flex-direction:column;gap:.5rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Lisa_5.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Graafik_2025.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
        </x-slot:actions>
    </tedi:attachment>
</div>
