@storybook([
    'name' => 'Vertical Colors',
    'order' => 3,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col>
        <h3>Full height</h3>
        <div style="display:flex;height:15rem">
            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="primary" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="primary" />

            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="secondary" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="secondary" />

            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="accent" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="accent" />
        </div>
    </tedi:col>
    <tedi:col>
        <h3>Fixed height</h3>
        <div style="display:flex">
            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="primary" size="5rem" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="primary" size="5rem" />

            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="secondary" size="5rem" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="secondary" size="5rem" />

            <tedi:separator axis="vertical" :spacing="2" :thickness="1" color="accent" size="5rem" />
            <tedi:separator axis="vertical" :spacing="2" :thickness="2" color="accent" size="5rem" />
        </div>
    </tedi:col>
</tedi:row>
