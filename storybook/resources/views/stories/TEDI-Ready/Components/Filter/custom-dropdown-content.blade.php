@storybook([
    'name' => 'Custom Dropdown Content',
    'order' => 7,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    The `content` slot is Angular's tediFilterContent projection. Angular's story
    drives `selectedPeriod` from the radio group and binds the `cleared` output;
    outputs are not re-emitted here (CONVENTIONS.md §7.2), so the clear button
    carries whatever the consumer passes in `clear-attributes` instead.
--}}
@php
    $periods = [
        ['value' => 'day', 'label' => 'Päev'],
        ['value' => 'week', 'label' => 'Nädal'],
        ['value' => 'month', 'label' => 'Kuu'],
        ['value' => 'year', 'label' => 'Aasta'],
    ];
@endphp

<div style="background: var(--general-surface-primary); padding: 24px;">
    <tedi:row cols="1" :gap-y="3">
        <tedi:col class="flex gap-2">
            <tedi:filter text="Periood" :show-clear="true">
                <x-slot:content>
                    <tedi:radio-group label="Periood" direction="vertical" name="period">
                        @foreach ($periods as $period)
                            <tedi:form.label color="primary" class="flex align-items-center gap-2">
                                <tedi:radio name="period" :value="$period['value']" />
                                {{ $period['label'] }}
                            </tedi:form.label>
                        @endforeach
                    </tedi:radio-group>
                </x-slot:content>
            </tedi:filter>
        </tedi:col>
    </tedi:row>
</div>
