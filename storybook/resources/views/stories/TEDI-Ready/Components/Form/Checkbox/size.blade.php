@storybook([
    'name' => 'Size',
    'order' => 2,
    'status' => 'stable',
    'args' => [],
])

{{-- Upstream's columns are `width="9|3" [md]="{ width: 6 }"`. Breakpoint props
     are not ported (CONVENTIONS.md §7), so the base width is used. --}}
<tedi:row :cols="12" :gap-y="3">
    <tedi:col :width="9">
        <tedi:text as="p" modifiers="bold">Default</tedi:text>
    </tedi:col>
    <tedi:col :width="3">
        <tedi:checkbox aria-label="Vaikimisi" />
    </tedi:col>
    <tedi:col :width="9">
        <div class="flex flex-column gap-3">
            <tedi:text as="p" modifiers="bold">Large</tedi:text>
            <tedi:alert type="warning" :show-close="false">
                <tedi:text as="p">Applied automatically on mobile screen sizes.</tedi:text>
                <tedi:text as="p" modifiers="bold">Otherwise, prefer using default size.</tedi:text>
            </tedi:alert>
        </div>
    </tedi:col>
    <tedi:col :width="3">
        <tedi:checkbox size="large" aria-label="Suur" />
    </tedi:col>
</tedi:row>
