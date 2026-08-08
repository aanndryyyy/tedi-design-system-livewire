@storybook([
    'name' => 'Hash Deep Linking',
    'order' => 8,
    'status' => 'stable',
    'args' => [],
    'argTypes' => [],
])

{{--
    Items with openOnHashMatch auto-expand when window.location.hash matches
    their itemId. Click a link below to update the URL hash; the matching
    item expands.
--}}
@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<div>
    <nav aria-label="Liigu kodanikuteenuste KKK-jaotise juurde" style="display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
        <tedi:link href="#id-card">ID-kaardi uuendamine</tedi:link>
        <tedi:link href="#tax-return">Tuludeklaratsiooni esitamine</tedi:link>
        <tedi:link href="#parental-benefits">Vanemahüvitis</tedi:link>
    </nav>

    <tedi:accordion :allow-multiple="true">
        <tedi:accordion-item item-id="id-card" :open-on-hash-match="true">
            <tedi:accordion-item-header>
                <x-slot:title>Kuidas uuendada ID-kaarti?</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item item-id="tax-return" :open-on-hash-match="true">
            <tedi:accordion-item-header>
                <x-slot:title>Kuidas esitada tuludeklaratsiooni?</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
        <tedi:accordion-item item-id="parental-benefits" :open-on-hash-match="true">
            <tedi:accordion-item-header>
                <x-slot:title>Millistele vanemahüvitistele on mul õigus?</x-slot:title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>
</div>
