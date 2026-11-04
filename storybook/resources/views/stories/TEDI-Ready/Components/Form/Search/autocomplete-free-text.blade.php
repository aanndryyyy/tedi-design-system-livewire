@storybook([
    'name' => 'Autocomplete: free-text search',
    'order' => 13,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular echoes the submitted value from its `searchEvent` output. Here
    that output is the `tedi-search:search` DOM event dispatched on the
    input, so the echo is a plain Alpine listener on a wrapper. Angular starts
    with an empty value and an empty match list; so does this static render,
    which has no server to refilter on (see autocomplete.blade.php).
--}}
<div x-data="{ submitted: null }" x-on:tedi-search:search="submitted = $event.detail.value">
    <tedi:row :cols="1" :gap="2">
        <tedi:col>
            <tedi:search
                input-id="search-free-text"
                label="Otsi"
                bind-description="description"
                placeholder="Otsi ja vajuta Enter…"
                :suggestions="[]"
            />
        </tedi:col>
        <tedi:col x-show="submitted !== null" x-cloak>
            <tedi:text color="tertiary" modifiers="small">Otsisid: „<span x-text="submitted"></span>”</tedi:text>
        </tedi:col>
    </tedi:row>
</div>
