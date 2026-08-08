{{-- An error row below the bar (announced via `role="alert"`). --}}
@storybook([
    'name' => 'With Error',
    'order' => 7,
    'status' => 'stable',
])

<tedi:progress-bar :value="60">
    <tedi:feedback-text text="Üleslaadimine ebaõnnestus, fail on liiga suur" type="error" />
</tedi:progress-bar>
