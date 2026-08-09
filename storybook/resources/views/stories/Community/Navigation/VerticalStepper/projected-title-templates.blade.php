{{--
    Angular's ProjectedTitleTemplates story. `<a item-title>` projection becomes
    the `item-title` slot; the anchors inherit the item's title styles from
    `.tedi-vertical-stepper-item__title a`, so they need no classes of their own.
--}}
@storybook([
    'name' => 'Projected Title Templates',
    'order' => 5,
    'status' => 'stable',
    'args' => [
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Aria label for stepper',
        ],
    ],
])

@php
    $links = ['Link 1', 'Link 2', 'Link 3', 'Link 4', 'Link 5'];
@endphp

<tedi:vertical-stepper :aria-label="$ariaLabel ?: null">
    @foreach ($links as $index => $link)
        <tedi:vertical-stepper-item :title="$link" :selected="$index === 0">
            <x-slot:item-title>
                <a href="#link{{ $index + 1 }}">{{ $link }}</a>
            </x-slot:item-title>
        </tedi:vertical-stepper-item>
    @endforeach
</tedi:vertical-stepper>
