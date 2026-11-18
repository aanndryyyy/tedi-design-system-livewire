@storybook([
    'name' => 'Mobile Modal',
    'order' => 16,
    'status' => 'subset',
    'args' => [
        'centeredOpen' => false,
        'fullscreenOpen' => false,
    ],
    'argTypes' => [
        'centeredOpen' => [
            'description' => "Opens the first field's modal. Stands in for Angular's runtime open state, which a server render cannot hold: set `open` from your Livewire component, and close it again on `tedi-date-field-confirm` (commit `draft`) or `tedi-modal-close` (discard it).",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Story',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'fullscreenOpen' => [
            'description' => "Opens the second field's modal.",
            'control' => ['type' => 'boolean'],
            'table' => [
                'category' => 'Story',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

{{--
    Angular sets the `mobile1` viewport global and `[useNativePicker]="false"`;
    `useNativePicker` is not ported, and `modal=true` does not depend on the
    viewport. Each field has its own `open` arg so the two modals never stack.
    Angular's row is one column below md and two from md up (`[md]`, a
    breakpoint input — CONVENTIONS.md §7 item 1); it is two columns here.
--}}
<tedi:row :cols="2" :gap="3">
    <tedi:col>
        <tedi:text as="p">Centered modal (modal=true)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-modal-centered">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-modal-centered"
                :modal="true"
                :open="(bool) $centeredOpen"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p" :modifiers="['small', 'bold']">Fullscreen modal with full-width calendar</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="date-modal-fullscreen">Kuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="date-modal-fullscreen"
                :modal="true"
                :fullscreen="true"
                :calendar-full-width="true"
                :open="(bool) $fullscreenOpen"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
