@storybook([
    'name' => 'Feedback Form Example',
    'order' => 10,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.76.92?node-id=15548-139124&m=dev',
])

@php
    $labels = ['Ei jäänud üldse rahule', 'Ei jäänud rahule', 'Osaliselt rahul', 'Jäin rahule', 'Jäin väga rahule'];
    $reasons = [
        ['id' => 'reason-error', 'label' => 'Näen veateadet', 'checked' => true],
        ['id' => 'reason-wrong', 'label' => 'Andmed on valed'],
        ['id' => 'reason-missing', 'label' => 'Andmed puuduvad'],
        ['id' => 'reason-no-right', 'label' => 'Õigus puudub'],
        ['id' => 'reason-not-found', 'label' => 'Ei leidnud, mida otsisin'],
        ['id' => 'reason-complex', 'label' => 'Keeruline'],
        ['id' => 'reason-other', 'label' => 'Muu põhjus'],
    ];
@endphp

{{-- Upstream's component state (`rating`, `submitted`) is an Alpine scope here; the @if blocks
     become x-show so every branch is in the server render. --}}
<tedi:card x-data="{ rating: 1, submitted: false }">
    <tedi:card-content>
        <div class="flex flex-column align-items-center gap-2" x-show="submitted" x-cloak>
            <tedi:text as="h2" modifiers="h5">Aitäh!</tedi:text>
            <tedi:text as="p" color="secondary">Sinu tagasiside aitab portaali paremaks muuta.</tedi:text>
        </div>
        <div class="flex flex-column gap-5" x-show="! submitted">
            <div class="flex flex-column align-items-center gap-2">
                <tedi:text as="h2" modifiers="h5">Kuidas jäid rahule teenuse kasutamisega?</tedi:text>
                <tedi:rating :value="1" x-model.number="rating" :item-labels="$labels" label="Kuidas jäid rahule teenuse kasutamisega?" />
            </div>
            <div class="flex flex-column gap-5" x-show="rating > 0">
                <div class="flex flex-column align-items-center gap-2">
                    <tedi:text as="h3" modifiers="h5">Mis mõjutas Sinu vastust?</tedi:text>
                    <tedi:text as="p" color="secondary">Vali sobivad märksõnad</tedi:text>
                </div>
                <tedi:checkbox-card-group class="justify-content-center">
                    @foreach ($reasons as $reason)
                        <tedi:checkbox-card variant="secondary">
                            <tedi:checkbox :id="$reason['id']" :checked="$reason['checked'] ?? false" />
                            {{ $reason['label'] }}
                        </tedi:checkbox-card>
                    @endforeach
                </tedi:checkbox-card-group>
                <tedi:form-field :character-limit="500">
                    <x-slot:label>
                        <tedi:form.label for="feedback-comment">Soovi korral lisa täpsustus</tedi:form.label>
                    </x-slot:label>

                    <tedi:textarea id="feedback-comment" />

                    <x-slot:feedback>
                        <tedi:feedback-text text="Me ei vasta selle vormi kaudu saadetud tagasisidele. Palun ära lisa siia isiklikku teavet." />
                    </x-slot:feedback>
                </tedi:form-field>
                <div class="flex justify-content-center">
                    <tedi:button x-on:click="submitted = true">Saada tagasiside</tedi:button>
                </div>
            </div>
        </div>
    </tedi:card-content>
</tedi:card>
