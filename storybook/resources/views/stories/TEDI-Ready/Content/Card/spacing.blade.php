@storybook([
    'name' => 'Spacing',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $cabbageText = 'Kapsas (Brassica oleracea) on rohelise, punase (lilla) või valge (kahvaturohelise) lehestikuga kaheaastane taim, mida kasvatatakse üheaastase köögiviljana selle tihedate lehtpeade saamiseks.';
@endphp

<tedi:row cols="1" gap="4">
    <tedi:col>
        <tedi:card>
            <tedi:card-content :padding="0.25">
                <tedi:text as="p">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card>
            <tedi:card-content :padding="0.5">
                <tedi:text as="p">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card>
            <tedi:card-content :padding="0.75">
                <tedi:text as="p">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card>
            <tedi:card-content :padding="1">
                <tedi:text as="p">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
    <tedi:col>
        <tedi:card>
            <tedi:card-content :padding="1.5">
                <tedi:text as="p">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    </tedi:col>
</tedi:row>
