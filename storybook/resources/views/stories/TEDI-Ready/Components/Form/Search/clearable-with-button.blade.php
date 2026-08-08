@storybook([
    'name' => 'Clearable with button',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

<tedi:search
    input-id="search-clearable-button"
    label="Otsing"
    :clearable="true"
    value="Lorem ipsum"
    :button="['text' => 'Otsi']"
/>
