@storybook([
    'name' => 'Radio Cards With Icons',
    'order' => 13,
    'status' => 'stable',
    'args' => [],
])

{{-- Radio cards with icons before the label text. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-primary" :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-primary" />
                <tedi:icon name="stethoscope" :size="18" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-primary" />
                <tedi:icon name="home" :size="18" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-secondary" :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-secondary" />
                <tedi:icon name="stethoscope" :size="18" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-secondary" />
                <tedi:icon name="home" :size="18" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary with description</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" />
                <tedi:icon name="stethoscope" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" />
                <tedi:icon name="home" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary with description</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" />
                <tedi:icon name="stethoscope" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" />
                <tedi:icon name="home" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
