@storybook([
    'name' => 'Vertical',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
])

{{--
    Set `direction="vertical"` to stack the name, size and progress in a column
    with the actions pinned top-right. Useful on mobile or in narrow containers
    such as a sidebar.

    Angular's last row also projects a `tedi-dropdown` "more options" menu
    beside the download action — dropdown overlay positioning is not ported
    (CONTRACT.md §5), so that row is omitted here.
--}}
<div style="display:flex;flex-direction:column;gap:.75rem;max-width:350px">
    <tedi:attachment direction="vertical" name="Kodukülastusakt_Triin_natuke_pikema_pealkirjaga.pdf" file-size="0,9 MB">
        <x-slot:progress>
            <tedi:progress-bar :value="34" value-position="bottom">
                <tedi:feedback-text text="Üleslaadimine" type="hint" />
            </tedi:progress-bar>
        </x-slot:progress>
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment direction="vertical" name="Kodukülastusakt_Triin_natuke_pikema_pealkirjaga.pdf" file-size="0,9 MB">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment direction="vertical" name="Kodukülastusakt.pdf" file-size="0,9 MB">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment direction="vertical" name="Kodukülastusakt.pdf" file-size="0,9 MB">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
        </x-slot:actions>
    </tedi:attachment>
    <tedi:attachment direction="vertical" name="Kodukülastusakt.pdf" file-size="0,9 MB">
        <x-slot:actions>
            <tedi:button variant="neutral" icon-only aria-label="Laadi alla" icon-start="download" />
            <tedi:button variant="neutral" icon-only aria-label="Kustuta" icon-start="delete" />
        </x-slot:actions>
    </tedi:attachment>
</div>
