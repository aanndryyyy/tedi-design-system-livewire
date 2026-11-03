@storybook([
    'name' => 'Cards With Icons',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
])

<style>
    .cards-with-icons__stretch {
        display: flex;
        align-items: stretch;
    }

    .cards-with-icons__stretch label[tedi-checkbox-card] {
        flex: 1 1 12rem;
    }
</style>

{{-- Upstream's row is `[xs]="{cols: 1}" [md]="{cols: 2}"`. Breakpoint props are
     not ported (CONVENTIONS.md §7), so the xs value is the base. --}}
<div class="flex flex-column gap-4">
    <tedi:row :cols="1" :gap-y="3">
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="p" modifiers="bold">Primary</tedi:text>
            <tedi:checkbox-card-group>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox :checked="true" />
                    <tedi:icon name="directions_subway" :size="18" />
                    Trammiga
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox />
                    <tedi:icon name="directions_walk" :size="18" />
                    Jalgsi
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox />
                    <tedi:icon name="directions_car" :size="18" />
                    Autoga
                </tedi:checkbox-card>
            </tedi:checkbox-card-group>
        </tedi:col>
        <tedi:col class="flex flex-column gap-2">
            <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
            <tedi:checkbox-card-group>
                <tedi:checkbox-card variant="secondary">
                    <tedi:checkbox :checked="true" />
                    <tedi:icon name="directions_subway" :size="18" />
                    Trammiga
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="secondary">
                    <tedi:checkbox />
                    <tedi:icon name="directions_walk" :size="18" />
                    Jalgsi
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="secondary">
                    <tedi:checkbox />
                    <tedi:icon name="directions_car" :size="18" />
                    Autoga
                </tedi:checkbox-card>
            </tedi:checkbox-card-group>
        </tedi:col>
    </tedi:row>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary with description</tedi:text>
        <tedi:checkbox-card-group class="cards-with-icons__stretch">
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="directions_subway" :size="18" />
                Trammiga
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="directions_walk" :size="18" />
                Jalgsi
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                <tedi:icon name="directions_car" :size="18" />
                Autoga
                <x-slot:feedback>
                    <tedi:feedback-text text="Kui soovid kiiremini kohale jõuda, tekitab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </div>
    <div class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary with description</tedi:text>
        <tedi:checkbox-card-group class="cards-with-icons__stretch">
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                <tedi:icon name="directions_subway" :size="18" />
                Trammiga
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="directions_walk" :size="18" />
                Jalgsi
                <x-slot:feedback>
                    <tedi:feedback-text text="Soovitatud variant, vähendab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                <tedi:icon name="directions_car" :size="18" />
                Autoga
                <x-slot:feedback>
                    <tedi:feedback-text text="Kui soovid kiiremini kohale jõuda, tekitab ummikuid" />
                </x-slot:feedback>
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </div>
</div>
