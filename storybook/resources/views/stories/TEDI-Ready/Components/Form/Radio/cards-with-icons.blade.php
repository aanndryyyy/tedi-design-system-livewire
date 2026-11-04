@storybook([
    'name' => 'Cards With Icons',
    'order' => 12,
    'status' => 'stable',
    'args' => [],
])

<style>
    .cards-with-icons__stretch {
        display: flex;
        align-items: stretch;
    }

    .cards-with-icons__stretch label[tedi-radio-card] {
        flex: 1 1 12rem;
    }
</style>

{{-- Upstream's row is `[xs]="{cols: 1}" [md]="{cols: 2}"`. Breakpoint props are
     not ported (CONVENTIONS.md §7), so the xs value is the base. --}}
<div class="flex flex-column gap-4">
    <tedi:row :cols="1" :gap-y="3">
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="p" modifiers="bold">Primary</tedi:text>
            <tedi:radio-card-group :bordered="true">
                <tedi:radio-card variant="primary">
                    <tedi:radio name="card-icon-primary" />
                    <tedi:icon name="directions_subway" :size="18" />
                    Trammiga
                </tedi:radio-card>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="card-icon-primary" :checked="true" />
                    <tedi:icon name="directions_walk" :size="18" />
                    Jalgsi
                </tedi:radio-card>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="card-icon-primary" />
                    <tedi:icon name="directions_car" :size="18" />
                    Autoga
                </tedi:radio-card>
            </tedi:radio-card-group>
        </tedi:col>
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
            <tedi:radio-card-group>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="card-icon-secondary" />
                    <tedi:icon name="directions_subway" :size="18" />
                    Trammiga
                </tedi:radio-card>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="card-icon-secondary" :checked="true" />
                    <tedi:icon name="directions_walk" :size="18" />
                    Jalgsi
                </tedi:radio-card>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="card-icon-secondary" />
                    <tedi:icon name="directions_car" :size="18" />
                    Autoga
                </tedi:radio-card>
            </tedi:radio-card-group>
        </tedi:col>
    </tedi:row>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary with description</tedi:text>
        <tedi:radio-card-group :bordered="true" class="cards-with-icons__stretch">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" />
                <tedi:icon name="directions_subway" :size="18" />
                Trammiga
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" :checked="true" />
                <tedi:icon name="directions_walk" :size="18" />
                Jalgsi
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-icon-desc-primary" />
                <tedi:icon name="directions_car" :size="18" />
                Autoga
                <x-slot:feedback>
                    <tedi:feedback-text text="Kui soovid kiiremini kohale jõuda, tekitab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary with description</tedi:text>
        <tedi:radio-card-group class="cards-with-icons__stretch">
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" />
                <tedi:icon name="directions_subway" :size="18" />
                Trammiga
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" :checked="true" />
                <tedi:icon name="directions_walk" :size="18" />
                Jalgsi
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-icon-desc-secondary" />
                <tedi:icon name="directions_car" :size="18" />
                Autoga
                <x-slot:feedback>
                    <tedi:feedback-text text="Kui soovid kiiremini kohale jõuda, tekitab ummikuid" />
                </x-slot:feedback>
            </tedi:radio-card>
        </tedi:radio-card-group>
    </div>
</div>
