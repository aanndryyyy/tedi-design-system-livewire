@storybook([
    'name' => 'Range',
    'order' => 8,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular's minDate / maxDate / disablePast matchers have no server-side
    analogue; they collapse into the flat `disabled-days` array (CALENDAR-SPEC
    §4.1), so the "disabled past" field lists the days explicitly.
--}}
@php
    $pastDays = [];
    for ($i = 1; $i <= 7; $i++) {
        $pastDays[] = date('Y-m-d', strtotime("-{$i} days"));
    }
@endphp

<tedi:row :gap="3" :cols="2">
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-default">Vaikimisi vahemik</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="range-default" mode="range" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-start-only">Ainult alguskuupäev</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="range-start-only"
                mode="range"
                :value="['from' => date('Y-m-d'), 'to' => null]"
                :display="date('d.m.Y')"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-disabled-past">Vahemik keelatud minevikuga</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="range-disabled-past"
                mode="range"
                :disabled-days="$pastDays"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-multiple-months">Vahemik mitme kuuga</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="range-multiple-months"
                mode="range"
                :number-of-months="2"
                :value="['from' => date('Y-m-05'), 'to' => date('Y-m-12')]"
                :display="date('05.m.Y').' – '.date('12.m.Y')"
                :open="true"
            />
        </tedi:form-field>
    </tedi:col>
</tedi:row>
