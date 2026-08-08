@storybook([
    'name' => 'Disabled Weekends',
    'order' => 9,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

@php
    // Angular passes a { dayOfWeek: [0, 6] } matcher. Matchers are JS predicates
    // with no server-side analogue, so the port precomputes the flat list of
    // 'Y-m-d' strings the current calendar page would grey out.
    $weekends = [];
    $cursor = strtotime(date('Y-m-01').' -7 days');
    for ($i = 0; $i < 49; $i++) {
        $day = strtotime("+{$i} days", $cursor);
        if (in_array((int) date('w', $day), [0, 6], true)) {
            $weekends[] = date('Y-m-d', $day);
        }
    }
@endphp

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-weekends">Kuupäev</tedi:form.label>
    </x-slot:label>
    <tedi:date-field input-id="date-weekends" :disabled-days="$weekends" :open="true" />
    <x-slot:feedback>
        <tedi:feedback-text text="Nädalavahetused ei ole valitavad." />
    </x-slot:feedback>
</tedi:form-field>
