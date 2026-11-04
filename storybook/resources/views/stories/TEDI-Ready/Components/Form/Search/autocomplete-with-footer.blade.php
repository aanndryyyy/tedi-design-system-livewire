@storybook([
    'name' => 'Autocomplete: with footer actions',
    'order' => 14,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular's `tediSearchSuggestion` template becomes the `rows` slot and its
    `tediSearchFooter` template the `footer` slot (see
    autocomplete-custom-row.blade.php). Static render: the list for the
    initial "4950" query, which matches a personal code.
--}}
@php
    $matches = [
        ['name' => 'Laura Kassisaba', 'code' => '49504080254'],
    ];
@endphp

<tedi:search
    input-id="search-result"
    label="Otsi"
    bind-label="name"
    :clearable="true"
    :min-query-length="3"
    value="4950"
    :suggestions="$matches"
>
    <x-slot:rows>
        @foreach ($matches as $i => $item)
            <tedi:search-suggestion :id="'search-result-opt-'.$i" :label="$item['name']" :item="$item" :custom="true">
                <tedi:text modifiers="bold">{{ $item['name'] }}</tedi:text>
                <tedi:separator axis="vertical" variant="dot-only" dot-size="extra-small" color="secondary" />
                <tedi:text color="tertiary">{{ $item['code'] }}</tedi:text>
            </tedi:search-suggestion>
        @endforeach
    </x-slot:rows>

    <x-slot:footer>
        <tedi:search-footer-actions>
            <tedi:button variant="secondary">Isik teadmata</tedi:button>
            <tedi:button variant="secondary">Puudub Eesti isikukood</tedi:button>
        </tedi:search-footer-actions>
        <tedi:text as="p" color="tertiary" :modifiers="['small', 'center']">
            Rahvastikuregistri andmete päringuks sisesta isikukood täismahus
        </tedi:text>
    </x-slot:footer>
</tedi:search>
