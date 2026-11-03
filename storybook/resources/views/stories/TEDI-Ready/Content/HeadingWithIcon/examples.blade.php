@storybook([
    'name' => 'Examples',
    'order' => 3,
    'status' => 'stable',
])

@php
    $cards = [
        ['background' => null, 'color' => 'brand', 'textColor' => 'primary', 'heading' => 'My statement of intention', 'button' => 'View statements of intention', 'variant' => 'secondary', 'iconEnd' => null],
        ['background' => 'brand-primary', 'color' => 'white', 'textColor' => 'white', 'heading' => 'My statement of intention', 'button' => 'Book appointment', 'variant' => 'secondary-inverted', 'iconEnd' => 'arrow_forward'],
        ['background' => 'brand-primary', 'color' => 'white', 'textColor' => 'white', 'heading' => 'This is a very long heading if there’s a strong need for a longer heading', 'button' => 'Book appointment', 'variant' => 'secondary-inverted', 'iconEnd' => 'arrow_forward'],
    ];
@endphp

<div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-start">
    @foreach ($cards as $card)
        <tedi:card :background="$card['background']" style="width: 300px">
            <tedi:card-content>
                <tedi:vertical-spacing :size="0.5">
                    <tedi:heading-with-icon icon="assignment_ind" :heading-color="$card['color']" :icon-color="$card['color']">
                        {{ $card['heading'] }}
                    </tedi:heading-with-icon>
                    <div>
                        <tedi:text as="p" :color="$card['textColor']">For example organ donation and blood transfusion</tedi:text>
                        <tedi:separator :spacing="1" />
                        <div style="display: flex; justify-content: flex-end">
                            <tedi:button :variant="$card['variant']" :icon-end="$card['iconEnd']">{{ $card['button'] }}</tedi:button>
                        </div>
                    </div>
                </tedi:vertical-spacing>
            </tedi:card-content>
        </tedi:card>
    @endforeach
</div>
