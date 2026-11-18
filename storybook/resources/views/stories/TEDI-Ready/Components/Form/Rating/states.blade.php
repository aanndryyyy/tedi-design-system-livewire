@storybook([
    'name' => 'States',
    'order' => 5,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
    'args' => ['pseudoStates' => [
        'hover' => '[id^="rating-hover"] label',
        'focusVisible' => '[id^="rating-focus"] input',
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

@php
    $types = ['star', 'number', 'icon'];
    $rows = [
        ['label' => 'Default', 'state' => null, 'disabled' => false],
        ['label' => 'Hover', 'state' => 'hover', 'disabled' => false],
        ['label' => 'Disabled', 'state' => null, 'disabled' => true],
        ['label' => 'Focus', 'state' => 'focus', 'disabled' => false],
    ];
@endphp

{{-- One table per type, stacked. Upstream's row is `[cols]="1" [md]="{ cols: 3 }"`; breakpoint
     props are not ported (CONVENTIONS.md §7), so the xs value is the base. The pseudo-state map
     is upstream's verbatim. --}}
<tedi:row :cols="1" :gap-y="5">
    @foreach ($types as $type)
        <div>
            <tedi:text as="h3" modifiers="h5">{{ ucfirst($type) }}</tedi:text>
            <table style="border-collapse: collapse">
                <thead>
                    <tr>
                        <th><span class="sr-only">State</span></th>
                        @foreach (['Default', 'Selected'] as $column)
                            <th scope="col" style="padding: 0.5rem 1rem; text-align: left">
                                <tedi:text modifiers="bold">{{ $column }}</tedi:text>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr @if ($row['state']) id="rating-{{ $row['state'] }}-{{ $type }}" @endif>
                            <th scope="row" style="padding: 0.5rem 1rem 0.5rem 0; text-align: left">
                                <tedi:text modifiers="bold">{{ $row['label'] }}</tedi:text>
                            </th>
                            @foreach ([0, 1] as $selected)
                                <td style="padding: 0.5rem 1rem; vertical-align: middle">
                                    <tedi:rating
                                        :type="$type"
                                        :count="1"
                                        :value="$selected"
                                        :disabled="$row['disabled']"
                                        :icons="$type === 'icon' ? ['sentiment_satisfied'] : null"
                                        label="Hinnang"
                                    />
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</tedi:row>
