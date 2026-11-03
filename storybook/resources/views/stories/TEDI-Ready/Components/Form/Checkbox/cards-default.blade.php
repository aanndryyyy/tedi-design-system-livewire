@storybook([
    'name' => 'Cards Default',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
])

{{-- Upstream's row is `[xs]="{cols: 1}" [md]="{cols: 2}"`. Breakpoint props are
     not ported (CONVENTIONS.md §7), so the xs value is the base. --}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Primary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                Kartul
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Peet
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Kapsas
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p" modifiers="bold">Secondary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                Kartul
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Peet
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Kapsas
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
</tedi:row>
