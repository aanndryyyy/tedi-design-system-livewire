{{--
    Angular's ExternalNavigation story wraps a demo component whose Edasi /
    Tagasi buttons move the active step and whose items listen to `stepSelect`.
    Output events aren't re-emitted (CONVENTIONS.md §7.2), so this port renders
    the initial state (step 1 active, later steps disabled) statically and the
    two buttons are decorative. What the story actually demonstrates —
    `disabled` on future steps — is fully ported.
--}}
@storybook([
    'name' => 'External Navigation',
    'order' => 9,
    'status' => 'subset',
])

<div style="display: flex; flex-direction: column; gap: 24px; align-items: flex-start;">
    <tedi:horizontal-stepper aria-label="Vormi edenemine" style="width: 100%;">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :selected="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :disabled="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" :disabled="true" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" :disabled="true" />
    </tedi:horizontal-stepper>
    <div style="display: flex; gap: 8px;">
        <tedi:button variant="secondary" :disabled="true">Tagasi</tedi:button>
        <tedi:button>Edasi</tedi:button>
    </div>
</div>
