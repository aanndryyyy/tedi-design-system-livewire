@storybook([
    'name' => 'Padded Even',
    'order' => 4,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col>
        <p>Some content</p>
        <tedi:separator :spacing="1" />
        <p>Other content</p>
    </tedi:col>
    <tedi:col style="display:flex;align-items:center;height:5rem">
        <p>Some content</p>
        <tedi:separator axis="vertical" :spacing="1" />
        <p>Other content</p>
    </tedi:col>
</tedi:row>
