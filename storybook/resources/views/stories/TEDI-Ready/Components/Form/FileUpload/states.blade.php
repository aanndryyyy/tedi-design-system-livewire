@storybook([
    'name' => 'States',
    'order' => 3,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.77.99?node-id=4612-87581&m=dev',
])

@php
    $states = [
        ['label' => 'Default'],
        ['label' => 'Error', 'feedback' => 'error'],
        ['label' => 'Success', 'feedback' => 'valid'],
        ['label' => 'Disabled', 'disabled' => true],
    ];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($states as $state)
        <tedi:row :cols="6" align-items="center">
            <tedi:col :width="1">
                <tedi:text as="p" modifiers="bold">{{ $state['label'] }}</tedi:text>
            </tedi:col>
            <tedi:col :width="5">
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label :for="$state['label']">Label</tedi:form.label>
                    </x-slot:label>
                    <tedi:file-upload
                        :input-id="$state['label']"
                        name="file"
                        :disabled="! empty($state['disabled'])"
                        :error="($state['feedback'] ?? null) === 'error' ? 'Feedback text' : null"
                        :success="($state['feedback'] ?? null) === 'valid' ? 'Feedback text' : null"
                    />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
