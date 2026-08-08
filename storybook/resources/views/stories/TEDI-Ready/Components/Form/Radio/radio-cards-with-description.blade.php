@storybook([
    'name' => 'Radio Cards With Description',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
])

{{-- Radio cards with a description below the label text. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
