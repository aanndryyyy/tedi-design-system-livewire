@storybook([
    'name' => 'Checkbox Cards With Description',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

{{-- Checkbox cards with a description below the label text. --}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Text
                <x-slot:feedback>
                    <tedi:feedback-text text="Description" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
</tedi:row>
