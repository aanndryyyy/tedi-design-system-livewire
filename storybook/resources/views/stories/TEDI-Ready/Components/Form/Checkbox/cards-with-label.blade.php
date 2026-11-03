@storybook([
    'name' => 'Cards With Label',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

<div class="flex flex-wrap gap-4">
    <tedi:checkbox-group label="Juurvili">
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label" :checked="true" />
                Kartul
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label" />
                Peet
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label" />
                Kapsas
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:checkbox-group>
    <tedi:checkbox-group label="Juurvili">
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label-error" />
                Kartul
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label-error" />
                Peet
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox name="cards-label-error" />
                Kapsas
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
        <x-slot:subtexts>
            <tedi:feedback-text text="Valik on kohustuslik" type="error" />
        </x-slot:subtexts>
    </tedi:checkbox-group>
</div>
