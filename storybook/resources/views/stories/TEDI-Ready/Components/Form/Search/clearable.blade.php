@storybook([
    'name' => 'Clearable',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

{{--
    The clear button is rendered whenever the field has a value; emptying it is
    the consumer's job (CONVENTIONS.md §7 item 2 — output()s are not ported).
    Pass `clearAttributes` to bind e.g. wire:click="$set('query', '')".
--}}
<tedi:search input-id="search-clearable" label="Otsing" :clearable="true" value="Lorem ipsum" />
