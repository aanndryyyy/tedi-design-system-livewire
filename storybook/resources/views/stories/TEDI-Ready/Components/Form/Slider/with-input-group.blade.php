@storybook([
    'name' => 'With Input Group',
    'order' => 2,
    'status' => 'subset',
    'args' => [],
])

{{--
    Angular puts `style="width: 100%"` on <tedi-slider> itself. Here $attributes
    lands on the range <input> (so wire:model binds), which means an inline
    style would size the input rather than the wrapper — the width goes on a
    plain wrapping <div> instead.

    Angular's two-way `[(value)]` between the slider and the number input is
    client-side state; the Blade port renders both at the same static value.
    Bind `wire:model` to the same Livewire property on both controls to get the
    equivalent behaviour in an app.
--}}
<div style="width: 100%">
    <tedi:slider
        input-id="slider-input-group"
        label="Väärtus"
        :min="0"
        :max="100"
        :step="1"
        :value="20"
        min-label="0%"
        max-label="100%"
    >
        <x-slot:addon>
            <tedi:input-group style="width: 100px">
                <tedi:form-field>
                    <tedi:text-field type="number" aria-label="Väärtus" value="20" />
                </tedi:form-field>
                <x-slot:suffix>%</x-slot:suffix>
            </tedi:input-group>
        </x-slot:addon>
    </tedi:slider>
</div>
