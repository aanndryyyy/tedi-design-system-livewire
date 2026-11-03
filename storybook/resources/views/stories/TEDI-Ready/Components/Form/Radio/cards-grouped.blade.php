@storybook([
    'name' => 'Cards Grouped',
    'order' => 10,
    'status' => 'stable',
    'args' => [],
])

{{-- Upstream's row is `[xs]="{cols: 1}" [md]="{cols: 2}"`. Breakpoint props are
     not ported (CONVENTIONS.md §7), so the xs value is the base. --}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Kartul
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Kapsas
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" :checked="true" />
                Peet
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-group-primary" />
                Kaalikas
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
        <tedi:radio-card-group :grouped="true">
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Kartul
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" :checked="true" />
                Kapsas
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Peet
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-group-secondary" />
                Kaalikas
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
