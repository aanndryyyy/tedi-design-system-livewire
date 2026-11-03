@storybook([
    'name' => 'Cards With Description',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
])

<style>
    .cards-with-description .tedi-radio-card-group {
        display: flex;
        align-items: stretch;
    }

    .cards-with-description label[tedi-radio-card] {
        flex: 1 1 12rem;
    }
</style>

{{-- Angular projects each <tedi-feedback-text> outside the card's __content
     through <ng-content select="tedi-feedback-text" />; in Blade that is the
     named `feedback` slot. Upstream's first style rule keys on the
     `tedi-radio-card-group` element; the Blade group renders a <div>, so the
     rule keys on its host class instead. --}}
<div class="cards-with-description flex flex-column gap-4">
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary</tedi:text>
        <tedi:radio-card-group :bordered="true">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" :checked="true" />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary" />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary grouped</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary-grouped" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary-grouped" :checked="true" />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-desc-primary-grouped" />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" :checked="true" />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary" />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary grouped</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary-grouped" />
                Kartul
                <x-slot:feedback>
                    <tedi:feedback-text text="Tärkliserikas mugulköögivili, mida kasutatakse laialt toiduks." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary-grouped" :checked="true" />
                Peet
                <x-slot:feedback>
                    <tedi:feedback-text text="Magusamaitseline juurvili, mida süüakse nii toorelt kui ka kuumtöödeldult." />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-desc-secondary-grouped" />
                Kapsas
                <x-slot:feedback>
                    <tedi:feedback-text text="Lehtköögivili, mille tihedaid lehti kasutatakse mitmesugustes roogades." />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
</div>
