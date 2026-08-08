@storybook([
    'name' => 'With Hint',
    'order' => 6,
    'status' => 'stable',
])

<tedi:progress-bar :value="60">
    <tedi:feedback-text text="Üleslaadimine" type="hint" />
</tedi:progress-bar>
