{{--
    Angular's second bar overrides `labelPosition` per breakpoint via
    `[md]="{ labelPosition: 'horizontal' }"`. Breakpoint props aren't ported
    (CONTRACT.md §7) — the base `labelPosition="top"` is kept for the whole
    viewport range.
--}}
@storybook([
    'name' => 'With Label',
    'order' => 4,
    'status' => 'subset',
])

<tedi:row :cols="1" :gap-y="4">
    <tedi:progress-bar :value="40" label="Progress" :required="true" value-position="bottom">
        <tedi:feedback-text text="Üleslaadimine" type="hint" />
    </tedi:progress-bar>
    <tedi:progress-bar :value="40" label="Küsitluses osalenutest olid vanuses 15-18" label-position="top" />
</tedi:row>
