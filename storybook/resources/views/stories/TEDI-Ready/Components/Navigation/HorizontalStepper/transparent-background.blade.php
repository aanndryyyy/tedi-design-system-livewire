@storybook([
    'name' => 'Transparent Background',
    'order' => 6,
    'status' => 'stable',
])

<tedi:horizontal-stepper aria-label="Vormi edenemine" background="transparent">
    <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
    <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
    <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" />
    <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
</tedi:horizontal-stepper>
