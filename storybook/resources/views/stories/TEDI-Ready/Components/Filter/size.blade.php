@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row :cols="2" :gap-y="3" align-items="center">
        <tedi:col><tedi:text as="p" modifiers="bold">Default</tedi:text></tedi:col>
        <tedi:col class="flex flex-wrap gap-2">
            <tedi:filter text="Text" :selected="true" />
            <tedi:filter text="Text" />
            <tedi:filter text="Text" />
            <tedi:filter text="Text" />
        </tedi:col>

        <tedi:col><tedi:text as="p" modifiers="bold">Large</tedi:text></tedi:col>
        <tedi:col class="flex flex-wrap gap-2">
            <tedi:filter text="Text" size="large" :selected="true" />
            <tedi:filter text="Text" size="large" />
            <tedi:filter text="Text" size="large" />
            <tedi:filter text="Text" size="large" />
        </tedi:col>
    </tedi:row>
</div>
