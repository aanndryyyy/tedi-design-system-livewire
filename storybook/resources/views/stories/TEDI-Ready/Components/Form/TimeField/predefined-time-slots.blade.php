@storybook([
    'name' => 'Predefined Time Slots',
    'order' => 7,
    'status' => 'subset',
])

@php
    $slots = ['09:30', '10:00', '11:30', '15:30', '18:30', '20:30'];
@endphp

<tedi:row :cols="3" :gap="3">
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Input trigger (recommended)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="slots-picker-input">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field
                input-id="slots-picker-input"
                value="11:30"
                picker-variant="slots"
                :time-slots="$slots"
                :columns="3"
                picker-trigger="input"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Radio buttons (showSlotIndicator)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="slots-picker-radio">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field
                input-id="slots-picker-radio"
                value="11:30"
                picker-variant="slots"
                :time-slots="$slots"
                :columns="3"
                :show-slot-indicator="true"
                picker-trigger="input"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p" modifiers="small bold">Button trigger</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="slots-picker-button">Aeg</tedi:form.label>
            </x-slot:label>
            <tedi:time-field
                input-id="slots-picker-button"
                value="11:30"
                picker-variant="slots"
                :time-slots="$slots"
                :columns="3"
                picker-trigger="button"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
