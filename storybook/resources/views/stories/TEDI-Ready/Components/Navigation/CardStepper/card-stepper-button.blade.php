@storybook([
    'name' => 'Card stepper button',
    'order' => 12,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => [],
])

{{-- Composed in the story, as upstream does — there is no built-in component
     for it. Upstream's useState is a local Alpine `active`. --}}
@php
    $buttonSteps = [
        ['title' => 'Minu andmed'],
        ['title' => 'Tervise ajalugu'],
        ['title' => 'Analüüside tulemused', 'description' => 'Täidab meditsiini töötaja', 'trailing' => '15p'],
        ['title' => 'Harjumused'],
        ['title' => 'Praegune terviseseisund'],
        ['title' => 'Ülevaade'],
    ];
    $indicator = 'display: flex; flex-shrink: 0; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 50%; font-size: var(--body-small-regular-size); line-height: 1;';
    $styles = [
        'completed' => 'color: var(--general-text-white); background-color: var(--stepper-step-completed-bg); border: var(--tedi-borders-01) solid var(--stepper-step-completed-bg);',
        'current' => 'color: var(--stepper-item-vertical-text-selected); background-color: var(--stepper-step-selected-bg); border: var(--tedi-borders-02) solid var(--stepper-step-selected-border);',
        'upcoming' => 'color: var(--stepper-item-vertical-text-disabled); background-color: var(--stepper-step-disabled-background); border: var(--tedi-borders-01) solid var(--stepper-step-disabled-border);',
    ];
@endphp

<div style="max-width: 420px" x-data="{ active: 2 }">
    <tedi:vertical-spacing :size="0.5">
        @foreach ($buttonSteps as $index => $step)
            <tedi:card-button
                x-bind:disabled="{{ $index }} > active"
                x-bind:aria-current="{{ $index }} === active ? 'step' : null"
                x-on:click="active = {{ $index }}"
                :disabled="$index > 2"
                :aria-current="$index === 2 ? 'step' : null"
            >
                <tedi:card>
                    <tedi:card-content>
                        <div style="display: flex; align-items: center; gap: var(--layout-grid-gutters-08)">
                            <span
                                aria-hidden="true"
                                style="{{ $indicator }} {{ $styles[$index < 2 ? 'completed' : ($index === 2 ? 'current' : 'upcoming')] }} align-self: flex-start"
                                x-bind:style="@js($indicator) + ' ' + ({{ $index }} < active ? @js($styles['completed']) : ({{ $index }} === active ? @js($styles['current']) : @js($styles['upcoming']))) + ' align-self: flex-start'"
                            >{{ $index + 1 }}</span>
                            <div style="display: flex; flex: 1; flex-direction: column; min-width: 0">
                                <div style="display: flex; align-items: center; gap: var(--layout-grid-gutters-04)">
                                    <tedi:text>{{ $step['title'] }}</tedi:text>
                                    <span x-show="{{ $index }} < active" @if ($index >= 2) x-cloak @endif><tedi:icon name="check" color="success" :size="18" /></span>
                                </div>
                                @if (isset($step['description']))
                                    <tedi:text modifiers="small" color="tertiary">{{ $step['description'] }}</tedi:text>
                                @endif
                            </div>
                            @if (isset($step['trailing']))
                                <tedi:text modifiers="small" color="tertiary">{{ $step['trailing'] }}</tedi:text>
                            @endif
                            <tedi:icon name="arrow_right_alt" color="secondary" />
                        </div>
                    </tedi:card-content>
                </tedi:card>
            </tedi:card-button>
        @endforeach
    </tedi:vertical-spacing>
</div>
