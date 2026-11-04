@storybook([
    'name' => 'Autocomplete',
    'order' => 9,
    'status' => 'stable',
    'args' => ['hideOnScroll' => false],
    'argTypes' => [
        'hideOnScroll' => [
            'description' => 'Closes the suggestion panel when the page (or a scrollable ancestor) scrolls. Scrolling the option list itself keeps the panel open.',
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

{{--
    Angular's story recomputes `suggestions` from a signal on every
    keystroke. Search never filters, so in a real app that is a server
    round-trip — `wire:model.live` on the field and a computed property,
    e.g. `tedi:search wire:model.live.debounce="query" :suggestions="$this->matches"`.

    A static Storybook render has no server to round-trip to, so this story
    renders the "Mar" query Angular starts with: focus the field to open the
    panel and use the arrow keys, Enter and Escape. Typing does not refilter
    the list here.
--}}
@php
    $people = [
        ['label' => 'Mari Maasikas', 'description' => 'Tootejuht'],
        ['label' => 'Marelle Mets', 'description' => 'Disainer'],
        ['label' => 'Marjanne Meri', 'description' => 'Arendaja'],
        ['label' => 'Mart Mesi', 'description' => 'Analüütik'],
        ['label' => 'Martin Saar', 'description' => 'Arendaja'],
        ['label' => 'Margit Mänd', 'description' => 'Testija'],
        ['label' => 'Marko Mägi', 'description' => 'Tootejuht'],
        ['label' => 'Maris Metsis', 'description' => 'Disainer'],
        ['label' => 'Marta Mölder', 'description' => 'Analüütik'],
        ['label' => 'Marek Muru', 'description' => 'Arendaja'],
    ];
@endphp

<tedi:search
    input-id="search-suggestions"
    label="Otsi"
    bind-description="description"
    placeholder="Trüki vähemalt 3 tähemärki…"
    :min-query-length="3"
    :hide-on-scroll="(bool) $hideOnScroll"
    value="Mar"
    :suggestions="$people"
/>
