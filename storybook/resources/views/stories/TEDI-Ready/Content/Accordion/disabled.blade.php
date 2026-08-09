@storybook([
    'name' => 'Disabled',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    Disabled items keep their current expanded state but reject user
    interaction. The header trigger renders as a native <button disabled>
    (or with aria-disabled for the non-clickable-header variant).
--}}
@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<tedi:accordion>
    <tedi:accordion-item :default-expanded="true">
        <tedi:accordion-item-header open-text="Ava" close-text="Sulge">
            <x-slot:before-title>
                <span style="display: inline-flex; align-items: center; justify-content: center; width: var(--button-sm-height); height: var(--button-sm-height); border: 1px solid var(--stepper-step-default-border); border-radius: 100px; background: var(--stepper-step-default-bg);">
                    <tedi:text :modifiers="['small', 'bold']" color="secondary">1</tedi:text>
                </span>
            </x-slot:before-title>
            <x-slot:title>Minu andmed</x-slot:title>
        </tedi:accordion-item-header>
        <tedi:accordion-item-content>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 400px;">
                    <tedi:form-field>
                        <x-slot:label>
                            <tedi:form.label :required="true" for="first-name">Eesnimi</tedi:form.label>
                        </x-slot:label>

                        <tedi:text-field id="first-name" />
                    </tedi:form-field>
                    <tedi:form-field>
                        <x-slot:label>
                            <tedi:form.label :required="true" for="last-name">Perenimi</tedi:form.label>
                        </x-slot:label>

                        <tedi:text-field id="last-name" />
                    </tedi:form-field>
                    <tedi:form-field>
                        <x-slot:label>
                            <tedi:form.label :required="true" for="id-code">Isikukood</tedi:form.label>
                        </x-slot:label>

                        <tedi:text-field id="id-code" />
                    </tedi:form-field>
                </div>
                <tedi:separator />
                <div style="display: flex; gap: 0.5rem;">
                    <tedi:button variant="secondary">Tühista</tedi:button>
                    <tedi:button variant="primary">Jätka</tedi:button>
                </div>
            </div>
        </tedi:accordion-item-content>
    </tedi:accordion-item>

    <tedi:accordion-item :disabled="true">
        <tedi:accordion-item-header open-text="Ava" close-text="Sulge">
            <x-slot:before-title>
                <span style="display: inline-flex; align-items: center; justify-content: center; width: var(--button-sm-height); height: var(--button-sm-height); border: 1px solid var(--stepper-step-disabled-border); border-radius: 100px; background: var(--stepper-step-disabled-bg);">
                    <tedi:text :modifiers="['small', 'bold']" color="disabled">2</tedi:text>
                </span>
            </x-slot:before-title>
            <x-slot:title>Taotlus</x-slot:title>
        </tedi:accordion-item-header>
        <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
    </tedi:accordion-item>

    <tedi:accordion-item :disabled="true">
        <tedi:accordion-item-header open-text="Ava" close-text="Sulge">
            <x-slot:before-title>
                <span style="display: inline-flex; align-items: center; justify-content: center; width: var(--button-sm-height); height: var(--button-sm-height); border: 1px solid var(--stepper-step-disabled-border); border-radius: 100px; background: var(--stepper-step-disabled-bg);">
                    <tedi:text :modifiers="['small', 'bold']" color="disabled">3</tedi:text>
                </span>
            </x-slot:before-title>
            <x-slot:title>Dokumendid</x-slot:title>
        </tedi:accordion-item-header>
        <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
    </tedi:accordion-item>
</tedi:accordion>
