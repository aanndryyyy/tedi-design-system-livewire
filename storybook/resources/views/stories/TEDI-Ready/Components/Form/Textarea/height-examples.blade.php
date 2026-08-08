@storybook([
    'name' => 'Height Examples',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular lays this out with <tedi-row [sm]="{ cols: 2 }">. Breakpoint props
    are not ported (CONVENTIONS.md §7 item 1), so the row uses the base `cols`.
--}}
@php
    $heightExamples = [
        [
            'label' => 'Fixed Height (7.5rem default)',
            'id' => 'fixed-height-default',
            'height' => '7.5rem',
            'resizable' => false,
            'placeholder' => 'This textarea has a fixed height of 7.5rem',
        ],
        [
            'label' => 'Custom Fixed Height',
            'id' => 'custom-height',
            'height' => '4rem',
            'resizable' => false,
            'placeholder' => 'This textarea has a fixed height of 4rem',
        ],
        [
            'label' => 'Auto Grow (minRows: 3, maxRows: 12)',
            'id' => 'auto-grow',
            'autoGrow' => true,
            'minRows' => 3,
            'maxRows' => 12,
            'placeholder' => 'Type multiple lines to see it grow automatically',
        ],
        [
            'label' => 'Auto Grow with Custom Rows',
            'id' => 'auto-grow-custom',
            'autoGrow' => true,
            'minRows' => 5,
            'maxRows' => 8,
            'placeholder' => 'This will grow from 5 to 8 rows maximum',
        ],
        [
            'label' => 'Auto Grow with Max Height',
            'id' => 'auto-grow-max-height',
            'autoGrow' => true,
            'minRows' => 3,
            'maxRows' => 12,
            'maxHeight' => '200px',
            'placeholder' => 'This will grow but max height is limited to 200px',
        ],
    ];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($heightExamples as $example)
        <tedi:row :cols="2" align-items="start">
            <tedi:col :width="1">
                <tedi:text modifiers="bold">{{ $example['label'] }}</tedi:text>
            </tedi:col>
            <tedi:col :width="1">
                <tedi:form-field :textarea="true">
                    <x-slot:label>
                        <tedi:form.label :for="$example['id']">Label</tedi:form.label>
                    </x-slot:label>
                    <tedi:textarea
                        :id="$example['id']"
                        :resizable="$example['resizable'] ?? true"
                        :auto-grow="$example['autoGrow'] ?? false"
                        :min-rows="$example['minRows'] ?? 3"
                        :max-rows="$example['maxRows'] ?? 12"
                        :height="$example['height'] ?? ''"
                        :max-height="$example['maxHeight'] ?? null"
                        :placeholder="$example['placeholder']"
                    />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
