@storybook([
    'name' => 'With Progress',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

{{--
    Project a <tedi:progress-bar> to show upload progress. Use a projected
    <tedi:feedback-text type="hint"> for the status label; the percentage is
    rendered automatically.
--}}
<div style="display:flex;flex-direction:column;gap:.5rem">
    <tedi:attachment name="Kodukülastusakt_Triin.pdf">
        <x-slot:progress>
            <tedi:progress-bar :value="34" value-position="bottom">
                <tedi:feedback-text text="Üleslaadimine" type="hint" />
            </tedi:progress-bar>
        </x-slot:progress>
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment name="Kodukülastusakt_Triin.pdf" file-size="0,9 MB">
        <x-slot:progress>
            <tedi:progress-bar :value="34" value-position="bottom" />
        </x-slot:progress>
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
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
</div>
