@storybook([
    'name' => 'Dropdown',
    'order' => 8,
    'status' => 'subset',
])

{{--
    Angular's story also sets [closeOnSelect]="true"; that prop is runtime-only
    with no markup effect and is dropped (CONVENTIONS.md §7 item 2).
--}}
@php
    $slots = ['12:30', '13:00', '13:30', '14:00', '14:30'];
@endphp

<tedi:row :cols="2" :gap="3">
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Button trigger</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="dropdown-picker-button">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field
                input-id="dropdown-picker-button"
                value="13:30"
                picker-variant="dropdown"
                :time-slots="$slots"
                picker-trigger="button"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Input trigger (recommended for dropdown)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="dropdown-picker-input">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field
                input-id="dropdown-picker-input"
                value="13:30"
                picker-variant="dropdown"
                :time-slots="$slots"
                picker-trigger="input"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
