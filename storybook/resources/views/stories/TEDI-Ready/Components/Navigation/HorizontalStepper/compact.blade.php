{{--
    Angular's Compact story wraps a demo component whose items listen to
    `stepSelect` and move the active step on click. Output events aren't
    re-emitted (CONVENTIONS.md §7.2), so this port renders the same compact
    composition with a fixed active step — the collapsed layout itself is
    fully ported, only the click-to-move-the-step wiring is the consumer's.
--}}
@storybook([
    'name' => 'Compact',
    'order' => 7,
    'status' => 'subset',
])

<div style="max-width: 480px;">
    <tedi:horizontal-stepper aria-label="Vormi edenemine" :compact="true">
        <tedi:horizontal-stepper-item label="Kutse" :step-number="1" description="Ametnik täidab" :completed="true" />
        <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" description="Ametnik täidab" :selected="true" />
        <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" description="Ametnik täidab" />
        <tedi:horizontal-stepper-item label="Vastus" :step-number="4" description="Ametnik täidab" />
    </tedi:horizontal-stepper>
</div>
