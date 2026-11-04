@storybook([
    'name' => 'Autocomplete: disabled suggestions',
    'order' => 12,
    'status' => 'stable',
    'args' => [],
])

{{-- Static render: the list for the initial "Lau" query (see autocomplete.blade.php). --}}
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

    // Angular: every third match is disabled.
    $suggestions = array_map(
        fn ($person, $index) => $person + ['disabled' => $index % 3 === 0],
        $registry,
        array_keys($registry),
    );
@endphp

<tedi:search
    input-id="search-disabled-suggestions"
    label="Otsi"
    bind-label="name"
    bind-description="code"
    placeholder="Trüki vähemalt 3 tähemärki…"
    :min-query-length="3"
    value="Lau"
    :suggestions="$suggestions"
    :feedback-text="['text' => 'Iga kolmas vaste on keelatud.']"
/>
