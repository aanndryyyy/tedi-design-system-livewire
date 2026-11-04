@storybook([
    'name' => 'Height Examples',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular lays each row out with <tedi-row [sm]="{ cols: 2 }">. Breakpoint
    props are not ported (CONVENTIONS.md §7 item 1), so the rows use the base
    `cols`.
--}}
@php
    $heightExamples = [
        ['label' => 'Default (minRows 3)', 'id' => 'sizing-default', 'placeholder' => 'Rests at three rows, drag to resize'],
        ['label' => 'Taller (minRows 6)', 'id' => 'sizing-tall', 'minRows' => 6, 'placeholder' => 'Rests at six rows'],
        ['label' => 'Bounded drag (minRows 3, maxRows 5)', 'id' => 'sizing-bounded', 'maxRows' => 5, 'placeholder' => 'The grip stops at five rows'],
        ['label' => 'Exact height (10rem, not resizable)', 'id' => 'sizing-exact', 'height' => '10rem', 'resizable' => false, 'placeholder' => 'Rests at 10rem'],
        ['label' => 'Auto grow (defaults: minRows 3, no row limit)', 'id' => 'sizing-auto-grow', 'autoGrow' => true, 'placeholder' => 'Type multiple lines to see it grow'],
        ['label' => 'Auto grow (minRows 3, maxRows 8)', 'id' => 'sizing-auto-grow-bounded', 'autoGrow' => true, 'maxRows' => 8, 'placeholder' => 'Grows up to eight rows, then scrolls'],
        ['label' => 'Auto grow, capped at 200px', 'id' => 'sizing-auto-grow-capped', 'autoGrow' => true, 'maxHeight' => '200px', 'placeholder' => 'Grows until 200px, then scrolls'],
    ];
@endphp

<tedi:row :cols="1" :gap-y="3">
    @foreach ($heightExamples as $example)
        <tedi:row :cols="2" align-items="start">
            <tedi:col :width="1">
                <tedi:text modifiers="bold">{{ $example['label'] }}</tedi:text>
            </tedi:col>
            <tedi:col :width="1">
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label :for="$example['id']">Label</tedi:form.label>
                    </x-slot:label>
                    <tedi:textarea
                        :id="$example['id']"
                        :resizable="$example['resizable'] ?? true"
                        :auto-grow="$example['autoGrow'] ?? false"
                        :min-rows="$example['minRows'] ?? 3"
                        :max-rows="$example['maxRows'] ?? null"
                        :height="$example['height'] ?? null"
                        :max-height="$example['maxHeight'] ?? null"
                        :placeholder="$example['placeholder']"
                    />
                </tedi:form-field>
            </tedi:col>
        </tedi:row>
    @endforeach
</tedi:row>
