@storybook([
    'name' => 'Colored Outlined',
    'order' => 7,
    'status' => 'stable',
    'args' => ['pseudoStates' => true],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    The colored/outlined toggle in every visual state. The Hover, Active and Focus
    rows are forced by storybook-addon-pseudo-states: the `pseudoStates` arg is
    what .storybook/preview.js turns into the addon's `parameters.pseudo`. This
    story uses the default `#Hover`/`#Active`/`#Focus` selector map, matching the
    Angular story's `parameters.pseudo`, so the row's state name is passed as the
    toggle's `input-id` — it lands on the <input>, which is the element the
    pseudo-class must apply to.
--}}
@php $states = ['Default', 'Hover', 'Active', 'Focus']; @endphp

<tedi:row :cols="1" :gap-y="4">
    @foreach ($states as $state)
        <tedi:row :cols="4" align-items="center">
            <b>{{ $state }}</b>
            <tedi:col :width="3" style="display: flex; flex-wrap: wrap; align-items: center; column-gap: 2rem; row-gap: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" aria-label="Vaikimisi lüliti, väljas" />
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" aria-label="Vaikimisi lüliti, sees" :checked="true" />
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" size="large" aria-label="Suur lüliti, väljas" />
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" size="large" aria-label="Suur lüliti, sees" :checked="true" />
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" size="large" :icon="true" aria-label="Suur lüliti ikooniga, väljas" />
                    <tedi:toggle variant="colored" type="outlined" :input-id="$state" size="large" :icon="true" aria-label="Suur lüliti ikooniga, sees" :checked="true" />
                </div>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
