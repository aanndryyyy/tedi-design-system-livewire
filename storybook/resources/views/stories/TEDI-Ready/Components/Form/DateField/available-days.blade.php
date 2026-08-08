@storybook([
    'name' => 'Available Days',
    'order' => 14,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

@php
    // Angular whitelists with `availableDays`; the whitelist/blacklist matchers
    // collapse into the flat `disabled-days` array in this port, so every day
    // of the visible page except the four highlighted ones is listed.
    $available = [
        date('Y-m-d', strtotime('-1 day')),
        date('Y-m-d', strtotime('+4 days')),
        date('Y-m-d', strtotime('+5 days')),
        date('Y-m-d', strtotime('+6 days')),
    ];

    $disabled = [];
    $monthStart = strtotime(date('Y-m-01'));
    $daysInMonth = (int) date('t', $monthStart);
    for ($i = 0; $i < $daysInMonth; $i++) {
        $day = date('Y-m-d', strtotime("+{$i} days", $monthStart));
        if (! in_array($day, $available, true)) {
            $disabled[] = $day;
        }
    }
@endphp

<tedi:form-field>
    <x-slot:label>
        <tedi:form.label for="date-available">Kuupäev</tedi:form.label>
    </x-slot:label>
    <tedi:date-field input-id="date-available" :disabled-days="$disabled" :open="true" />
    <x-slot:feedback>
        <tedi:feedback-text text="Ainult esiletõstetud päevad on valitavad." />
    </x-slot:feedback>
</tedi:form-field>
