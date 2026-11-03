@storybook([
    'name' => 'Bordered',
    'order' => 9,
    'status' => 'stable',
])

<div style="max-width: 390px; padding: 1.5rem;">
<tedi:table-of-contents heading="Sisukord" :sticky="false" active-id="methods" :bordered="true">
    <tedi:table-of-contents-item item-id="intro">
        <tedi:link href="#intro" :underline="false">Sissejuhatus</tedi:link>
    </tedi:table-of-contents-item>
    <tedi:table-of-contents-item item-id="methods">
        <tedi:link href="#methods" :underline="false">Meetodid</tedi:link>
        <x-slot:sub-items>
            <tedi:table-of-contents-item item-id="methods-1">
                <tedi:link href="#methods-1" :underline="false">Andmete kogumine</tedi:link>
            </tedi:table-of-contents-item>
            <tedi:table-of-contents-item item-id="methods-2">
                <tedi:link href="#methods-2" :underline="false">Analüüs</tedi:link>
            </tedi:table-of-contents-item>
        </x-slot:sub-items>
    </tedi:table-of-contents-item>
    <tedi:table-of-contents-item item-id="results">
        <tedi:link href="#results" :underline="false">Tulemused</tedi:link>
    </tedi:table-of-contents-item>
    <tedi:table-of-contents-item item-id="discussion">
        <tedi:link href="#discussion" :underline="false">Arutelu</tedi:link>
    </tedi:table-of-contents-item>
    <tedi:table-of-contents-item item-id="conclusion">
        <tedi:link href="#conclusion" :underline="false" icon-start="description">Kokkuvõte</tedi:link>
    </tedi:table-of-contents-item>
</tedi:table-of-contents>
</div>
