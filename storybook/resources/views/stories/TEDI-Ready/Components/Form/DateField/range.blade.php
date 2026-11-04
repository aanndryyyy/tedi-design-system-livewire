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
    §4.1), so the "disabled past" field lists the days explicitly. Angular's
    "Range with min/max limits" field is not ported: its limits are open-ended
    matchers (two months ago … today) that a flat day list cannot express.
    The start-only value is Angular's fixed reference date, 15.06.2026.
--}}
@php
    $pastDays = [];
    for ($i = 1; $i <= 7; $i++) {
        $pastDays[] = date('Y-m-d', strtotime("-{$i} days"));
    }
@endphp

<tedi:row :gap="3" :cols="2">
    <tedi:col>
        <tedi:text as="p">Default range</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-default">Vahemik</tedi:form.label>
            </x-slot:label>
            <tedi:date-field input-id="range-default" mode="range" />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Start date only (no end selected)</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-start-only">Vahemik</tedi:form.label>
            </x-slot:label>
            <tedi:date-field
                input-id="range-start-only"
                mode="range"
                :value="['from' => '2026-06-15', 'to' => null]"
                display="15.06.2026"
            />
        </tedi:form-field>
    </tedi:col>
    <tedi:col>
        <tedi:text as="p">Past dates disabled</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-disabled-past">Vahemik</tedi:form.label>
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
        <tedi:text as="p">Range with multiple months</tedi:text>
        <tedi:form-field>
            <x-slot:label>
                <tedi:form.label for="range-multiple-months">Vahemik</tedi:form.label>
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
