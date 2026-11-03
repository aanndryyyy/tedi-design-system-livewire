@storybook([
    'name' => 'Card stepper button states',
    'order' => 13,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev',
    'args' => ['pseudoStates' => [
        'hover' => ['[data-pseudo="Hover"]'],
        'active' => ['[data-pseudo="Active"]'],
        'focusVisible' => ['[data-pseudo="Focus"]'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $indicator = 'display: flex; flex-shrink: 0; align-items: center; justify-content: center; width: 1.5rem; height: 1.5rem; border-radius: 50%; font-size: var(--body-small-regular-size); line-height: 1;';
    $semantic = [
        'default' => 'color: var(--general-text-secondary); background-color: var(--stepper-step-default-bg); border: var(--tedi-borders-01) solid var(--stepper-step-default-border);',
        'error' => 'color: var(--general-text-white); background-color: var(--stepper-step-danger-bg); border: var(--tedi-borders-01) solid var(--stepper-step-danger-bg);',
        'success' => 'color: var(--general-text-white); background-color: var(--stepper-step-completed-bg); border: var(--tedi-borders-01) solid var(--stepper-step-completed-bg);',
    ];
    $disabledStyle = 'color: var(--stepper-item-vertical-text-disabled); background-color: var(--stepper-step-disabled-background); border: var(--tedi-borders-01) solid var(--stepper-step-disabled-border);';
    $columns = ['default' => 'Default', 'error' => 'Has error', 'success' => 'Success'];
    $rows = ['Default', 'Hover', 'Active', 'Focus', 'Disabled'];
@endphp

{{-- Upstream's fullWidth parameter: no 360px decorator. Its Col xs=12 md=4
     is a breakpoint prop (CONVENTIONS.md §7 item 1), so the base width is
     used: three columns side by side. --}}
<tedi:row :cols="3" :gap-y="5">
    @foreach ($columns as $key => $label)
        <tedi:col>
            <tedi:vertical-spacing :size="1">
                <tedi:text as="p" modifiers="bold">{{ $label }}</tedi:text>
                @foreach ($rows as $row)
                    <tedi:vertical-spacing :size="0.5">
                        <tedi:text as="p" :modifiers="['small', 'bold']" color="secondary">{{ $row }}</tedi:text>
                        <tedi:card-button data-pseudo="{{ $row }}" :disabled="$row === 'Disabled'">
                            <tedi:card>
                                <tedi:card-content>
                                    <div style="display: flex; align-items: center; gap: var(--layout-grid-gutters-08)">
                                        <span aria-hidden="true" style="{{ $indicator }} {{ $row === 'Disabled' ? $disabledStyle : $semantic[$key] }}">1</span>
                                        <div style="display: flex; flex: 1; align-items: center; gap: var(--layout-grid-gutters-04); min-width: 0">
                                            <tedi:text>Minu andmed</tedi:text>
                                            @if ($row !== 'Disabled' && $key === 'error')
                                                <tedi:icon name="error" color="danger" :size="18" />
                                            @elseif ($row !== 'Disabled' && $key === 'success')
                                                <tedi:icon name="check" color="success" :size="18" />
                                            @endif
                                        </div>
                                        <tedi:icon name="arrow_right_alt" color="secondary" />
                                    </div>
                                </tedi:card-content>
                            </tedi:card>
                        </tedi:card-button>
                    </tedi:vertical-spacing>
                @endforeach
            </tedi:vertical-spacing>
        </tedi:col>
    @endforeach
</tedi:row>
