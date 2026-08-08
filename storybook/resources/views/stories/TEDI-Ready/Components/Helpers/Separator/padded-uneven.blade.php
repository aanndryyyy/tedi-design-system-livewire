@storybook([
    'name' => 'Padded Uneven',
    'order' => 5,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col>
        <p>Some content</p>
        <tedi:separator :spacing="['top' => 2.5, 'bottom' => 0.5]" />
        <p>Other content</p>
    </tedi:col>
    <tedi:col style="display:flex;align-items:center;height:5rem">
        <p>Some content</p>
        <tedi:separator axis="vertical" :spacing="['left' => 2.5, 'right' => 0.5]" />
        <p>Other content</p>
    </tedi:col>
</tedi:row>
