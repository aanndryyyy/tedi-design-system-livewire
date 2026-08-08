@storybook([
    'name' => 'With Icon',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

{{-- Pass a Material Symbol name to `icon` to show a leading file-type icon before the file name. --}}
<div style="display:flex;flex-direction:column;gap:.5rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB" icon="description">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB" icon="imagesmode">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB" icon="picture_as_pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
</div>
