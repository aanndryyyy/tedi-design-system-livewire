@storybook([
    'name' => 'Autocomplete: custom row',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular's per-item `ng-template tediSearchSuggestion` becomes the `rows`
    slot: Blade has no scoped slots, so the rows are written out, one
    `tedi:search-suggestion` per suggestion with `:custom="true"`. Its
    `label` is what the field is filled with on select. `suggestions` is
    still passed — it marks the combobox and picks the results row over the
    no-results one. Static render: the list is the one for the initial "Lau"
    query (see autocomplete.blade.php).
--}}
@php
    $registry = [
        ['name' => 'Laura Kassisaba', 'code' => '49504080254'],
        ['name' => 'Laur Lepik', 'code' => '38207120211'],
        ['name' => 'Laura Kask', 'code' => '48611230123'],
        ['name' => 'Lauri Laan', 'code' => '38905170234'],
        ['name' => 'Lauris Vaher', 'code' => '37905240312'],
        ['name' => 'Laur Kivi', 'code' => '39207310423'],
        ['name' => 'Laura Ojala', 'code' => '48802190534'],
    ];
@endphp

<tedi:search
    input-id="search-custom"
    label="Otsi"
    bind-label="name"
    placeholder="Trüki vähemalt 3 tähemärki…"
    :min-query-length="3"
    value="Lau"
    :suggestions="$registry"
>
    <x-slot:rows>
        @foreach ($registry as $i => $item)
            <tedi:search-suggestion :id="'search-custom-opt-'.$i" :label="$item['name']" :item="$item" :custom="true">
                <tedi:option-content>
                    <tedi:option-content-label>{{ $item['name'] }}</tedi:option-content-label>
                    <tedi:option-content-meta>{{ $item['code'] }}</tedi:option-content-meta>
                </tedi:option-content>
            </tedi:search-suggestion>
        @endforeach
    </x-slot:rows>
</tedi:search>
