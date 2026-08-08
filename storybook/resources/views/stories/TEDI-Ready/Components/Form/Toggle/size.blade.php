@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="2" :gap-y="3">
    <b>Default</b>
    <div>
        <label for="example-toggle-2.1" class="sr-only">Default size</label>
        <tedi:toggle input-id="example-toggle-2.1" />
    </div>
    <b>Large</b>
    <div>
        <label for="example-toggle-2.2" class="sr-only">Large size</label>
        <tedi:toggle input-id="example-toggle-2.2" size="large" />
    </div>
</tedi:row>
