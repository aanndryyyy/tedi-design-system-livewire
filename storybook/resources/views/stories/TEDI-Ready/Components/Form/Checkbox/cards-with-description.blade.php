@storybook([
    'name' => 'Cards With Description',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

<style>
    .cards-with-description__stretch {
        display: flex;
        align-items: stretch;
    }

    .cards-with-description__stretch label[tedi-checkbox-card] {
        flex: 1 1 12rem;
    }
</style>

{{-- Angular projects each <tedi-feedback-text> outside the card's __content
     through <ng-content select="tedi-feedback-text" />; in Blade that is the
     named `feedback` slot. --}}
<div class="flex flex-column gap-4">
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary</tedi:text>
        <tedi:checkbox-card-group class="cards-with-description__stretch">
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
        <tedi:checkbox-card-group class="cards-with-description__stretch">
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </div>
</div>
