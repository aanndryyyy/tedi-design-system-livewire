@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
])

<div>
    <tedi:row :cols="2" :gap="3" align-items="center" class="border-bottom" style="padding: 14px 16px;">
        <tedi:col><tedi:text as="p">Default</tedi:text></tedi:col>
        <tedi:col style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            <tedi:status-badge text="Draft" />
            <tedi:status-badge text="Draft" status="success" />
        </tedi:col>
    </tedi:row>
    <tedi:row :cols="2" :gap="3" align-items="center" style="padding: 14px 16px;">
        <tedi:col><tedi:text as="p">Large</tedi:text></tedi:col>
        <tedi:col style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
            <tedi:status-badge text="Draft" size="large" />
            <tedi:status-badge text="Draft" status="success" size="large" />
        </tedi:col>
    </tedi:row>
</div>
