{{--
    Angular's Controlled story two-way binds [(value)] and reads it back into a
    live label.

    Output events aren't re-emitted (CONVENTIONS.md §7.2), but the selection
    itself is live (CONVENTIONS.md §8): <tedi:button-group> declares the Alpine
    state, so `tediValue` on the group is the value to read back. A consumer
    wanting the server to know binds wire:click on the items instead.
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
    ]">
        <template x-teleport="#button-group-controlled-value">
            <span x-text="tediValue ?? '—'">{{ $selected }}</span>
        </template>
    </tedi:button-group>

    <p style="margin-top: 8px;">Valitud: <span id="button-group-controlled-value"></span></p>
</div>
