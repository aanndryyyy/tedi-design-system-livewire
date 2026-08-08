{{--
    Project a `x-slot:action` element to fill the right-side slot — for
    example a CTA button that takes the user somewhere relevant. When set,
    alert.component.scss's `:has()` rules hide the default close button and
    switch to a row layout automatically.
--}}
@storybook([
    'name' => 'With Action Button',
    'order' => 12,
    'status' => 'stable',
])

<tedi:alert type="warning" icon="warning">
    Teie profiililt puudub foto — lisage see, et kolleegid saaksid teid jagatud dokumentides ära tunda.
    <x-slot:action>
        <tedi:button variant="secondary" icon-end="arrow_forward">Ava profiil</tedi:button>
    </x-slot:action>
</tedi:alert>
