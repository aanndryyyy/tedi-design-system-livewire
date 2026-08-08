@storybook([
    'name' => 'Alert Types',
    'order' => 8,
    'status' => 'stable',
])

<tedi:row :cols="1" :gap="3">
    <tedi:alert type="info" icon="info">
        See on infoteade.
    </tedi:alert>
    <tedi:alert type="success" icon="check_circle">
        See on õnnestumisteade.
    </tedi:alert>
    <tedi:alert type="warning" icon="warning">
        See on hoiatusteade.
    </tedi:alert>
    <tedi:alert type="danger" icon="error">
        See on veateade.
    </tedi:alert>
</tedi:row>
