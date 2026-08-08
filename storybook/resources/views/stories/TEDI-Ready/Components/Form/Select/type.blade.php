@storybook([
    'name' => 'Type',
    'order' => 3,
    'status' => 'subset',
    'args' => [],
])

<div class="flex flex-column gap-4">
    <tedi:select
        input-id="type-default"
        label="Default"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
    <tedi:select
        input-id="type-hint"
        label="With hint"
        :feedback-text="['type' => 'hint', 'text' => 'Vihjetekst', 'position' => 'left']"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
    />
</div>
