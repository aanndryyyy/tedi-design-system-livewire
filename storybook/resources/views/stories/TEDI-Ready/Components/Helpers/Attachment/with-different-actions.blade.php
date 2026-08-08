@storybook([
    'name' => 'With Different Actions',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
])

{{--
    Project any combination of neutral icon buttons into the actions slot —
    view, download, delete, or a mix. The last row uses `size="small"` buttons
    alongside a progress bar.
--}}
<div style="display:flex;flex-direction:column;gap:.5rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Vaata" icon-start="visibility" />
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB">
        <x-slot:progress>
            <tedi:progress-bar :value="34" value-position="bottom">
                <tedi:feedback-text text="Üleslaadimine" type="hint" />
            </tedi:progress-bar>
        </x-slot:progress>
        <x-slot:actions>
            <tedi:button variant="neutral" size="small" icon-only aria-label="Laadi alla" icon-start="download" />
            <tedi:button variant="neutral" size="small" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
</div>
