@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
])

<div class="flex flex-column gap-4">
    <tedi:select
        input-id="size-default"
        label="Default"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
        size="default"
    />
    <tedi:select
        input-id="size-small"
        label="Small"
        :options="['tallinn' => 'Tallinn', 'narva' => 'Narva', 'tartu' => 'Tartu']"
        size="small"
    />
</div>
