@storybook([
    'name' => 'Semantic Headings',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    headingLevel wraps the header trigger in a semantic <h1>-<h6> element per
    the WAI-ARIA Accordion Pattern, with display:contents so it adds no
    visual change — inspect the DOM to confirm each header is a real <h3>.
--}}
@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<section>
    <tedi:text as="h2" style="margin-bottom: 1rem;">Sinu kehtivad retseptid</tedi:text>

    <tedi:accordion :allow-multiple="true">
        <tedi:accordion-item>
            <tedi:accordion-item-header :heading-level="3">
                <x-slot:title>HJERTEMAGNYL TBL 150MG+21MG N100</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item>
            <tedi:accordion-item-header :heading-level="3">
                <x-slot:title>AMLODIPINE ACTAVIS</x-slot:title>
                <x-slot:start-description>
                    <tedi:text color="tertiary">Amlodipiin 5mg</tedi:text>
                </x-slot:start-description>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item>
            <tedi:accordion-item-header :heading-level="3">
                <x-slot:title>ATORVASTATIN KRKA</x-slot:title>
                <x-slot:start-description>
                    <tedi:text color="tertiary">Atorvastatiin 20mg</tedi:text>
                </x-slot:start-description>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>
</section>
