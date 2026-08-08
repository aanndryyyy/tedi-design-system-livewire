@storybook([
    'name' => 'All Variants',
    'order' => 2,
    'status' => 'stable',
])

<div style="background: var(--general-surface-tertiary); padding: 16px; width: 100%;">
    <tedi:row :cols="2" :gap-y="3" align-items="center">
        <tedi:col><tedi:text as="p" modifiers="bold">Small</tedi:text></tedi:col>
        <tedi:col class="flex gap-3 align-items-center">
            <tedi:status-indicator type="success" />
            <tedi:status-indicator type="danger" />
            <tedi:status-indicator type="warning" />
            <tedi:status-indicator type="inactive" />
        </tedi:col>

        <tedi:col><tedi:text as="p" modifiers="bold">Large</tedi:text></tedi:col>
        <tedi:col class="flex gap-3 align-items-center">
            <tedi:status-indicator type="success" size="lg" />
            <tedi:status-indicator type="danger" size="lg" />
            <tedi:status-indicator type="warning" size="lg" />
            <tedi:status-indicator type="inactive" size="lg" />
        </tedi:col>

        <tedi:col><tedi:text as="p" modifiers="bold">Small bordered</tedi:text></tedi:col>
        <tedi:col class="flex gap-3 align-items-center">
            <tedi:status-indicator type="success" :has-border="true" />
            <tedi:status-indicator type="danger" :has-border="true" />
            <tedi:status-indicator type="warning" :has-border="true" />
            <tedi:status-indicator type="inactive" :has-border="true" />
        </tedi:col>

        <tedi:col><tedi:text as="p" modifiers="bold">Large bordered</tedi:text></tedi:col>
        <tedi:col class="flex gap-3 align-items-center">
            <tedi:status-indicator type="success" size="lg" :has-border="true" />
            <tedi:status-indicator type="danger" size="lg" :has-border="true" />
            <tedi:status-indicator type="warning" size="lg" :has-border="true" />
            <tedi:status-indicator type="inactive" size="lg" :has-border="true" />
        </tedi:col>
    </tedi:row>
</div>
