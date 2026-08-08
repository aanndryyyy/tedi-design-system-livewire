@storybook([
    'name' => 'Accordion Behavior',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:text as="h4">Single-expand accordion</tedi:text>
    <tedi:accordion style="margin-bottom: 1rem;">
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri 1</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri 2</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:text as="h4">Multi-expand accordion</tedi:text>
    <tedi:accordion :allow-multiple="true">
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri 1</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item>
            <tedi:accordion-item-header>
                <x-slot:title>Pealkiri 2</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>
</div>
