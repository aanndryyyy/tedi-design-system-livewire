@storybook([
    'name' => 'Checkbox Cards With Icons',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
])

{{-- Checkbox cards with icons before the label text. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="stethoscope" :size="18" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="home" :size="18" />
                Text
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="stethoscope" :size="18" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="home" :size="18" />
                Text
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary with description</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="stethoscope" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="home" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary with description</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="apartment" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="stethoscope" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="home" :size="18" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
</tedi:row>
