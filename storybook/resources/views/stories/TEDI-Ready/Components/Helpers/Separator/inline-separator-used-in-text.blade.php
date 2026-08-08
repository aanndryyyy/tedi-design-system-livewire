@storybook([
    'name' => 'Inline Separator Used In Text',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :cols="1" :gap="3">
    <tedi:col>
        Lorem ipsum dolor sit, amet
        <tedi:separator axis="vertical" color="primary" :spacing="0.5" style="display:inline" />
        consectetur adipisicing elit.
    </tedi:col>
    <tedi:col>
        Lorem ipsum dolor sit, amet
        <tedi:separator axis="vertical" color="secondary" :spacing="1" style="display:inline" />
        consectetur adipisicing elit.
    </tedi:col>
    <tedi:col>
        Lorem ipsum dolor sit, amet
        <tedi:separator axis="vertical" color="accent" :spacing="1.5" style="display:inline" />
        consectetur adipisicing elit.
    </tedi:col>
    <tedi:col>
        Lorem ipsum dolor sit, amet
        <tedi:separator axis="vertical" color="secondary" variant="dot-only" dot-size="small" :spacing="0.5" />
        consectetur adipisicing elit.
    </tedi:col>
</tedi:row>
