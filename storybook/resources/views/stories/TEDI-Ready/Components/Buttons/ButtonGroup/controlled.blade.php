{{--
    Angular's Controlled story two-way binds [(value)] and reads it back into a
    live label. Output events aren't re-emitted (CONVENTIONS.md §7.2), so this
    port renders the initial value statically; a consumer wires wire:click /
    x-on:click on the items and re-renders with the new `value`.
--}}
@storybook([
    'name' => 'Controlled',
    'order' => 11,
    'status' => 'subset',
])

@php $selected = '2'; @endphp

<div class="flex flex-column gap-2">
    <tedi:button-group aria-label="Vaate valik" :value="$selected" :items="[
        ['value' => '1', 'label' => 'Tabel'],
        ['value' => '2', 'label' => 'Loend'],
        ['value' => '3', 'label' => 'Kalender'],
    ]" />
    <p style="margin-top: 8px;">Valitud: {{ $selected }}</p>
</div>
