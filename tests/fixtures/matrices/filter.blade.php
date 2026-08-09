@php
    $options = [
        ['label' => 'Fertilitas', 'value' => 'fertilitas'],
        ['label' => 'Ida-Tallinna Keskhaigla', 'value' => 'itk'],
        ['label' => 'Lääne-Tallinna Keskhaigla', 'value' => 'ltk', 'disabled' => true],
    ];
@endphp

<div class="gx-sec">
    <h2>Filter</h2>
    <p>Port of <code>filter</code> (<code>tedi-filter</code>). The host is a
        <code>&lt;div class="tedi-filter"&gt;</code> — TEDI ships no
        <code>tedi-filter</code> element rule. Selection, search and the option
        keyboard layer run on the <code>tediFilter</code> Alpine component, which
        composes the shared <code>tediOverlay</code> engine (CONVENTIONS.md §11);
        the panel and every option are in the DOM with their real class lists
        even while closed, so all of it is harvestable.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant × size × selected</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach (['primary', 'secondary'] as $variant)
                @foreach (['default', 'large'] as $size)
                    @foreach ([false, true] as $selected)
                        <tedi:filter
                            text="{{ $variant }}/{{ $size }}"
                            variant="{{ $variant }}"
                            size="{{ $size }}"
                            :selected="$selected"
                        />
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">disabled, prepend (hidden and kept when selected), append</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:filter text="Keelatud" :disabled="true" />
            <tedi:filter text="Keelatud, valitud" variant="secondary" :selected="true" :disabled="true" />

            <tedi:filter text="Lugemata" variant="secondary" size="large" :selected="true">
                <x-slot:prepend><tedi:status-indicator type="danger" /></x-slot:prepend>
            </tedi:filter>

            <tedi:filter
                text="Esitatud"
                variant="secondary"
                size="large"
                :selected="true"
                :hide-prepend-when-selected="false"
            >
                <x-slot:prepend><tedi:status-badge text="5" color="brand" /></x-slot:prepend>
            </tedi:filter>

            <tedi:filter text="Vajab tähelepanu" variant="secondary" size="large">
                <x-slot:append><tedi:status-badge text="7" color="danger" /></x-slot:append>
            </tedi:filter>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">single-select dropdown: selected item, disabled item, preserve label, clear</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:filter text="Raviasutus" :options="$options" value="itk" :show-clear="true" />
            <tedi:filter
                text="Raviasutus"
                variant="secondary"
                :options="$options"
                value="itk"
                :preserve-label="true"
                :show-search="true"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">multi-select dropdown: search, select all (mixed), count badge, clear</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:filter
                text="Raviasutus"
                :allow-multiple="true"
                :options="$options"
                :value="['fertilitas']"
                :show-search="true"
                :show-select-all="true"
                :show-clear="true"
            />
            <tedi:filter
                text="Raviasutus"
                variant="secondary"
                :allow-multiple="true"
                :options="$options"
                :value="['fertilitas', 'itk']"
                :show-select-all="true"
                :search-clearable="false"
                :clear-search-on-select="true"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">custom dropdown content + clear</div>
        <div class="gx-case__demo">
            <tedi:filter text="Periood" variant="secondary" :show-clear="true">
                <x-slot:content>
                    <tedi:radio-group label="Periood" direction="vertical" name="matrix-period">
                        <tedi:radio value="day" />
                        <tedi:radio value="week" />
                    </tedi:radio-group>
                </x-slot:content>
            </tedi:filter>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Filter group</h2>
    <p>Port of <code>filter-group</code>. <code>managed</code> is the explicit
        stand-in for Angular's runtime <code>isManaged</code> signal and is what
        turns on <code>role="radiogroup"</code> / <code>role="group"</code> — and
        with it each child's <code>role="radio"</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">unmanaged / managed single-select / managed multi-select</div>
        <div class="gx-case__demo">
            <tedi:filter-group>
                <tedi:filter text="Kooskõlastatud" :selected="true" />
                <tedi:filter text="Tagasilükatud" />
            </tedi:filter-group>

            <tedi:filter-group :managed="true" label="Tüüp">
                <tedi:filter text="Kõik" value="all" variant="secondary" :selected="true" />
                <tedi:filter text="Aktiivsed" value="active" variant="secondary" />
                <tedi:filter text="Lõpetatud" value="done" variant="secondary" />
            </tedi:filter-group>

            <tedi:filter-group :managed="true" :allow-multiple="true" label="Kategooria">
                <tedi:filter text="Vastuvõtud" value="vastuvotud" variant="secondary" :selected="true" :group-allow-multiple="true" />
                <tedi:filter text="Analüüsid" value="analuusid" variant="secondary" :group-allow-multiple="true" />
            </tedi:filter-group>

            <tedi:filter-group :disabled="true" label="Keelatud">
                <tedi:filter text="Kõik" value="all" />
                <tedi:filter text="Aktiivsed" value="active" />
            </tedi:filter-group>
        </div>
    </div>
</div>
