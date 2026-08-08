@storybook([
    'name' => 'Radio Cards Grouped',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

{{-- Radio cards in a grouped layout, joined like a button group with shared borders and no gap. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" :checked="true" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" :checked="true" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
