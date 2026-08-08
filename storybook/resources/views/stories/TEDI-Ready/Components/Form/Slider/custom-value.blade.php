@storybook([
    'name' => 'Custom Value',
    'order' => 6,
    'status' => 'subset',
    'args' => [],
])

{{--
    Angular's two-way `[(value)]` links each slider to its addon field in the
    browser; the Blade port renders both at the same static value. Bind
    `wire:model` to one Livewire property on both controls for the equivalent.
--}}
<tedi:row :cols="1" :gap-y="2">
    <tedi:col :width="6">
        <tedi:slider
            input-id="slider-custom-basic"
            label="Väärtus"
            :hide-label="true"
            :min="0"
            :max="100"
            :value="50"
            min-label="0%"
            max-label="100%"
        />
    </tedi:col>
    <tedi:col :width="6">
        <tedi:slider
            input-id="slider-custom-input"
            label="Väärtus"
            :min="0"
            :max="100"
            :value="50"
            min-label="0%"
            max-label="100%"
        >
            <x-slot:addon>
                <tedi:number-field
                    input-id="slider-custom-input-field"
                    aria-label="Väärtus"
                    :value="50"
                    :min="0"
                    :max="100"
                    suffix="%"
                />
            </x-slot:addon>
        </tedi:slider>
    </tedi:col>
    <tedi:col :width="6">
        <tedi:slider
            input-id="slider-custom-number"
            label="Väärtus"
            :min="1"
            :max="10"
            :step="1"
            :value="4"
            min-label="1"
            max-label="10"
        >
            <x-slot:addon>
                <tedi:number-field
                    input-id="slider-custom-number-field"
                    aria-label="Väärtus"
                    :value="4"
                    :min="1"
                    :max="10"
                    :step="1"
                />
            </x-slot:addon>
        </tedi:slider>
    </tedi:col>
</tedi:row>
