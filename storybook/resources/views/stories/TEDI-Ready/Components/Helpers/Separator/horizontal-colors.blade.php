@storybook([
    'name' => 'Horizontal Colors',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

<tedi:row :gap="5">
    <tedi:col>
        <h3>Full width</h3>
        <tedi:separator :spacing="2" :thickness="1" color="primary" />
        <tedi:separator :spacing="2" :thickness="2" color="primary" />

        <tedi:separator :spacing="2" :thickness="1" color="secondary" />
        <tedi:separator :spacing="2" :thickness="2" color="secondary" />

        <tedi:separator :spacing="2" :thickness="1" color="accent" />
        <tedi:separator :spacing="2" :thickness="2" color="accent" />
    </tedi:col>
    <tedi:col>
        <h3>Fixed width</h3>
        <tedi:separator :spacing="2" :thickness="1" color="primary" size="5rem" />
        <tedi:separator :spacing="2" :thickness="2" color="primary" size="5rem" />

        <tedi:separator :spacing="2" :thickness="1" color="secondary" size="5rem" />
        <tedi:separator :spacing="2" :thickness="2" color="secondary" size="5rem" />

        <tedi:separator :spacing="2" :thickness="1" color="accent" size="5rem" />
        <tedi:separator :spacing="2" :thickness="2" color="accent" size="5rem" />
    </tedi:col>
</tedi:row>
