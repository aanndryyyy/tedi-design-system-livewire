@storybook([
    'name' => 'WCAG Compliance',
    'order' => 7,
    'status' => 'stable',
])

<tedi:toast-container />

<tedi:row :gap="2" x-data>
    <tedi:col>
        <tedi:button x-on:click="TediToast.success('Success', 'Screen reader announces politely')">
            Success (role=status)
        </tedi:button>
    </tedi:col>
    <tedi:col>
        <tedi:button x-on:click="TediToast.danger('Error', 'Screen reader announces immediately')">Error (role=alert)</tedi:button>
    </tedi:col>
    <tedi:col>
        <tedi:button x-on:click="TediToast.info('Info', 'No screen reader announcement', { role: 'none' })">Info (role=none)</tedi:button>
    </tedi:col>
</tedi:row>
