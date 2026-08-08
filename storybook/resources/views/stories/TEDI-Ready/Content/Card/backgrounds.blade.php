@storybook([
    'name' => 'Backgrounds',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $cabbageText = 'Kapsas (Brassica oleracea) on rohelise, punase (lilla) või valge (kahvaturohelise) lehestikuga kaheaastane taim, mida kasvatatakse üheaastase köögiviljana selle tihedate lehtpeade saamiseks.';
    $backgrounds = [
        'primary', 'secondary', 'tertiary', 'accent',
        'brand-primary', 'brand-secondary', 'brand-tertiary', 'brand-quaternary',
        'danger-primary', 'danger-secondary', 'success-primary', 'success-secondary',
        'info-primary', 'info-secondary', 'warning-primary', 'warning-secondary',
        'neutral-primary', 'neutral-secondary',
    ];
    $lightBackgrounds = ['brand-primary', 'brand-secondary', 'info-secondary', 'success-secondary', 'danger-secondary'];
@endphp

<tedi:row cols="1" gap="4">
    @foreach ($backgrounds as $bg)
        <tedi:card :background="$bg">
            <tedi:card-content>
                <tedi:text as="p" :color="in_array($bg, $lightBackgrounds, true) ? 'white' : 'primary'">{{ $cabbageText }}</tedi:text>
            </tedi:card-content>
        </tedi:card>
    @endforeach
</tedi:row>
