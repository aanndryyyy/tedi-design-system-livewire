@php
    $people = [
        ['label' => 'Mari Maasikas', 'description' => 'Tootejuht'],
        ['label' => 'Marelle Mets', 'description' => 'Disainer', 'disabled' => true],
        ['label' => 'Marjanne Meri', 'description' => 'Arendaja'],
    ];
@endphp

<div class="gx-sec">
    <h2>Search</h2>
    <p>Port of <code>form/search</code>. Composes <code>form-field</code> + <code>text-field</code> with an optional trailing <code>button</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: small | default | large — plain, icon-only button, button with text</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            @foreach (['small', 'default', 'large'] as $size)
                <tedi:search :input-id="'search-'.$size.'-plain'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size" />
                <tedi:search :input-id="'search-'.$size.'-icon'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size.', nupp ikooniga'" :button="['ariaLabel' => 'Otsi']" />
                <tedi:search :input-id="'search-'.$size.'-button'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size.', nupp tekstiga'" :button="['text' => 'Otsi']" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">button variant: primary (default) | secondary | neutral | danger</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['primary', 'secondary', 'neutral', 'danger'] as $variant)
                <tedi:search :input-id="'search-variant-'.$variant" label="Otsing" :button="['text' => 'Otsi', 'variant' => $variant]" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: clearable with value | clearable off | clear on interaction | disabled | hint | valid | error</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:search input-id="search-clearable" label="Otsing" value="Lorem ipsum" />
            <tedi:search input-id="search-not-clearable" label="Otsing" value="Lorem ipsum" :clearable="false" />
            <tedi:search input-id="search-clear-on-interaction" label="Otsing" value="Lorem ipsum" :show-clear-on-interaction="true" />
            <tedi:search input-id="search-disabled" label="Otsing" value="Lorem ipsum" :disabled="true" />
            <tedi:search input-id="search-hint" label="Otsing" :feedback-text="['text' => 'Vihjetekst']" />
            <tedi:search input-id="search-valid" label="Otsing" :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'valid']" />
            <tedi:search input-id="search-error" label="Otsing" :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'error']" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">no visible label (ariaLabel only) | custom searchIcon | wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:search input-id="search-no-label" placeholder="Otsi tooteid või teenuseid..." aria-label="Otsi tooteid või teenuseid" />
            <tedi:search input-id="search-custom-icon" label="Otsing" search-icon="person" />
            <tedi:search input-id="search-wire" label="Otsing" wire:model.live="query" :button="['text' => 'Otsi']" :button-attributes="['wire:click' => 'search']" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">autocomplete panel (panel-open): results with highlight + description + disabled row | custom rows + footer | loading | no results</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:search input-id="search-ac-results" label="Otsi" value="Mar" bind-description="description" :panel-open="true" :suggestions="$people" />

            <tedi:search input-id="search-ac-custom" label="Otsi" value="Mar" :panel-open="true" :suggestions="$people" :button="['text' => 'Otsi']">
                <x-slot:rows>
                    @foreach ($people as $i => $person)
                        <tedi:search-suggestion :id="'search-ac-custom-opt-'.$i" :label="$person['label']" :custom="true" :disabled="$person['disabled'] ?? false">
                            <tedi:option-content>
                                <tedi:option-content-label>{{ $person['label'] }}</tedi:option-content-label>
                                <tedi:option-content-meta>{{ $person['description'] }}</tedi:option-content-meta>
                            </tedi:option-content>
                        </tedi:search-suggestion>
                    @endforeach
                </x-slot:rows>

                <x-slot:footer>
                    <tedi:search-footer-actions>
                        <tedi:button variant="secondary">Isik teadmata</tedi:button>
                    </tedi:search-footer-actions>
                </x-slot:footer>
            </tedi:search>

            <tedi:search input-id="search-ac-loading" label="Otsi" value="Mar" :loading="true" :panel-open="true" :suggestions="[]" />
            <tedi:search input-id="search-ac-empty" label="Otsi" value="Xyz" :panel-open="true" :suggestions="[]" />
        </div>
    </div>
</div>
