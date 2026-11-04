@storybook([
    'name' => 'Cards With Label',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
])

<div class="flex flex-wrap gap-4">
    <tedi:radio-group label="Juurvili">
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label" :checked="true" />
                Kartul
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label" />
                Peet
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label" />
                Kapsas
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:radio-group>
    <tedi:radio-group label="Juurvili">
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label-error" />
                Kartul
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label-error" />
                Peet
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="cards-label-error" />
                Kapsas
            </tedi:radio-card>
        </tedi:radio-card-group>
        <x-slot:subtexts>
            <tedi:feedback-text text="Valik on kohustuslik" type="error" />
        </x-slot:subtexts>
    </tedi:radio-group>
</div>
