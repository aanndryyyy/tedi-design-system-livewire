@storybook([
    'name' => 'Nested Cards',
    'order' => 11,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $medicationRow = [
        'label' => 'Silt',
        'name' => 'HJERTEMAGNYL TBL 150MG+21MG N100',
        'dose' => '150 MG+21 MG',
        'plan' => 'Pidev ravi: 1 tk 1 kord nädalas',
    ];
@endphp

<tedi:card>
    <tedi:card-header background="brand-primary">
        <tedi:text as="h3" color="white">Pealkiri</tedi:text>
    </tedi:card-header>
    <tedi:card-content>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <tedi:text as="h4" color="brand">Püsiravi</tedi:text>
            <tedi:text as="p">Sinu püsiravimid ja meditsiiniseadmed, mis on väljastatud viimase 6 kuu jooksul.</tedi:text>
            <tedi:text as="h5">Ravimid</tedi:text>
            @for ($i = 0; $i < 3; $i++)
                <tedi:card :borderless="true">
                    <tedi:card-content background="brand-tertiary">
                        <tedi:row cols="3" gap="4">
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['name'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['dose'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['plan'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                        </tedi:row>
                    </tedi:card-content>
                </tedi:card>
            @endfor
        </div>
    </tedi:card-content>
    <tedi:separator />
    <tedi:card-content>
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <tedi:text as="h4" color="brand">Ajutine ravi</tedi:text>
            <tedi:text as="p">Sinu ravimid ja meditsiiniseadmed, mida kasutatakse vajadusel või teatud perioodil.</tedi:text>
            <tedi:text as="h5">Ravimid</tedi:text>
            @for ($i = 0; $i < 2; $i++)
                <tedi:card :borderless="true">
                    <tedi:card-content background="brand-tertiary">
                        <tedi:row cols="3" gap="4">
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['name'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['dose'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                            <tedi:col>
                                <tedi:text-group type="vertical">
                                    <x-slot:label>{{ $medicationRow['label'] }}</x-slot:label>
                                    <x-slot:value>{{ $medicationRow['plan'] }}</x-slot:value>
                                </tedi:text-group>
                            </tedi:col>
                        </tedi:row>
                    </tedi:card-content>
                </tedi:card>
            @endfor
        </div>
    </tedi:card-content>
</tedi:card>
