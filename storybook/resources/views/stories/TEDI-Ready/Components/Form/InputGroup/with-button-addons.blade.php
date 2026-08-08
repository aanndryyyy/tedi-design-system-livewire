@storybook([
    'name' => 'With Button Addons',
    'order' => 6,
    'status' => 'stable',
    'args' => [
        'addons' => false,
    ],
])

{{-- When an addon is a standalone action (e.g. a button), disable `addons` so it keeps its own styling and does not visually merge into the control. --}}
<div class="flex flex-column gap-4">
    <tedi:input-group :addons="(bool) $addons">
        <tedi:form.label for="promo">Sooduskood</tedi:form.label>
        <tedi:form-field>
            <input type="text" id="promo" placeholder="Sisesta sooduskood" />
        </tedi:form-field>
        <x-slot:suffix>
            <tedi:button>Rakenda</tedi:button>
        </x-slot:suffix>
    </tedi:input-group>

    <tedi:input-group :addons="false" :disabled="true">
        <tedi:form.label for="promo-disabled">Sooduskood (keelatud)</tedi:form.label>
        <tedi:form-field>
            <input type="text" id="promo-disabled" placeholder="Sisesta sooduskood" />
        </tedi:form-field>
        <x-slot:suffix>
            <tedi:button :disabled="true">Rakenda</tedi:button>
        </x-slot:suffix>
    </tedi:input-group>
</div>
