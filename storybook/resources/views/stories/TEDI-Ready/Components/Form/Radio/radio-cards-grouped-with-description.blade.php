@storybook([
    'name' => 'Radio Cards Grouped With Description',
    'order' => 12,
    'status' => 'stable',
    'args' => [],
])

{{-- Grouped radio cards with description text. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-desc-primary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-desc-primary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-desc-primary" :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-desc-secondary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-desc-secondary" :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-desc-secondary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
