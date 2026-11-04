@storybook([
    'name' => 'Content navigation',
    'order' => 13,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.102.102?node-id=65376-125297&m=dev',
    'args' => [],
])

{{-- Upstream filters the sender list live as the search is typed and
     counts the selection in React state; that sample logic is not
     reproduced — the search field and selection are static markup. The
     navigation between the two views, which is what the story is about,
     is a local Alpine `step`. --}}
<style>
    .sheet-example-row {
        display: flex;
        gap: var(--layout-grid-gutters-08);
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: var(--dropdown-item-padding-y) var(--tedi-sheet-padding-x);
        font: inherit;
        color: var(--dropdown-item-default-text);
        text-align: left;
        cursor: pointer;
        background: none;
        border: none;
    }

    .sheet-example-row:hover {
        color: var(--dropdown-item-hover-text);
        background: var(--dropdown-item-hover-background);
    }

    .sheet-example-row--bleed {
        width: calc(100% + 2 * var(--tedi-sheet-padding-x));
        margin-inline: calc(-1 * var(--tedi-sheet-padding-x));
    }
</style>

<tedi:sheet>
    <tedi:sheet-trigger><tedi:button variant="neutral" icon-start="tune">Filtreeri</tedi:button></tedi:sheet-trigger>
    {{-- A nested scope: it still reaches the sheet's open / show() / hide(). --}}
    <div x-data="{ step: 'filters' }" x-effect="if (open) step = 'filters'">
    <tedi:sheet-content>
        <tedi:sheet-header title="Filtreerimine" variant="default" x-show="step === 'filters'" />
        <tedi:sheet-header title="Saatja" variant="default" x-show="step === 'senders'" x-cloak>
            <x-slot:start><tedi:button variant="neutral" size="small" icon-start="arrow_back" icon-only aria-label="Tagasi" title="Tagasi" x-on:click="step = 'filters'" /></x-slot:start>
        </tedi:sheet-header>
        <tedi:sheet-body padding="none">
            <div x-bind:hidden="step !== 'filters'">
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <button type="button" class="sheet-example-row sheet-example-row--bleed" x-on:click="step = 'senders'">
                        <tedi:text as="p">Saatja</tedi:text>
                        <span style="display: flex; align-items: center; gap: 0.5rem">
                            <tedi:tag>2</tedi:tag>
                            <tedi:icon name="arrow_forward" color="brand" />
                        </span>
                    </button>
                </div>
                <tedi:separator :spacing="0" />
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <tedi:radio-group name="sheet-filter-status" direction="vertical" aria-label="Kirjade olek">
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-filter-status-all" name="sheet-filter-status" value="all" checked /> Kõik</tedi:form.label>
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-filter-status-unread" name="sheet-filter-status" value="unread" /> Ainult lugemata</tedi:form.label>
                    </tedi:radio-group>
                </div>
                <tedi:separator :spacing="0" />
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <tedi:radio-group name="sheet-filter-sort" direction="vertical" aria-label="Järjestus">
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-filter-sort-unread" name="sheet-filter-sort" value="unread-top" checked /> Lugemata kõige üleval</tedi:form.label>
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:radio id="sheet-filter-sort-date" name="sheet-filter-sort" value="date" /> Kuupäeva järgi</tedi:form.label>
                    </tedi:radio-group>
                </div>
            </div>
            <div hidden x-bind:hidden="step !== 'senders'">
                <div style="padding: var(--tedi-sheet-padding-x)">
                    <tedi:vertical-spacing :size="1">
                        <tedi:search input-id="sheet-filter-senders-search" placeholder="Otsi" aria-label="Otsi saatjat" />
                        <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-all" name="sheet-filter-senders-all" value="all" /> Kõik saatjad</tedi:form.label>
                        <tedi:separator :spacing="0" />
                        <tedi:vertical-spacing :size="0.5">
                            <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-0" name="sheet-filter-senders-senders" value="SK ID Solutions AS" checked /> SK ID Solutions AS</tedi:form.label>
                            <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-1" name="sheet-filter-senders-senders" value="Politsei- ja Piirivalveamet" checked /> Politsei- ja Piirivalveamet</tedi:form.label>
                            <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-2" name="sheet-filter-senders-senders" value="Sihtasutus Kutsekoda" /> Sihtasutus Kutsekoda</tedi:form.label>
                            <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-3" name="sheet-filter-senders-senders" value="Siseministeerium" /> Siseministeerium</tedi:form.label>
                            <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-4" name="sheet-filter-senders-senders" value="Riigi Infosüsteemide Amet" /> Riigi Infosüsteemide Amet</tedi:form.label>
                        </tedi:vertical-spacing>
                        <tedi:accordion>
                        <tedi:accordion-item>
                            <tedi:accordion-item-header :show-expand-label="false" expand-action-position="end">
                                <x-slot:title>Maksu- ja Tolliamet</x-slot:title>
                                <x-slot:after-title><tedi:tag>3</tedi:tag></x-slot:after-title>
                            </tedi:accordion-item-header>
                            <tedi:accordion-item-content>
                                <tedi:vertical-spacing :size="0.5">
                                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-5" name="sheet-filter-senders-senders" value="Maksu- ja Tolliamet" /> Maksu- ja Tolliamet</tedi:form.label>
                                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-6" name="sheet-filter-senders-senders" value="Maksu- ja Tolliamet registrid" /> Maksu- ja Tolliamet registrid</tedi:form.label>
                                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-7" name="sheet-filter-senders-senders" value="Tolliosakond" /> Tolliosakond</tedi:form.label>
                                </tedi:vertical-spacing>
                            </tedi:accordion-item-content>
                        </tedi:accordion-item>
                        <tedi:accordion-item default-expanded>
                            <tedi:accordion-item-header :show-expand-label="false" expand-action-position="end">
                                <x-slot:title>Tartu Maakohus</x-slot:title>
                                <x-slot:after-title><tedi:tag>2</tedi:tag></x-slot:after-title>
                            </tedi:accordion-item-header>
                            <tedi:accordion-item-content>
                                <tedi:vertical-spacing :size="0.5">
                                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-8" name="sheet-filter-senders-senders" value="Tartu Maakohus registriosakond" /> Tartu Maakohus registriosakond</tedi:form.label>
                                    <tedi:form.label color="primary" class="flex align-items-center gap-2"><tedi:checkbox id="sheet-filter-senders-9" name="sheet-filter-senders-senders" value="Tartu Maakohus kinnistusosakond" /> Tartu Maakohus kinnistusosakond</tedi:form.label>
                                </tedi:vertical-spacing>
                            </tedi:accordion-item-content>
                        </tedi:accordion-item>
                        </tedi:accordion>
                    </tedi:vertical-spacing>
                </div>
            </div>
        </tedi:sheet-body>
        <tedi:sheet-footer full-width x-show="step === 'filters'">
            <tedi:sheet-closer><tedi:button variant="secondary">Tühista</tedi:button></tedi:sheet-closer>
            <tedi:button>Rakenda</tedi:button>
        </tedi:sheet-footer>
        <tedi:sheet-footer full-width x-show="step === 'senders'" x-cloak>
            <tedi:button variant="secondary" x-on:click="step = 'filters'">Tagasi</tedi:button>
            <tedi:button x-on:click="step = 'filters'">Rakenda</tedi:button>
        </tedi:sheet-footer>
    </tedi:sheet-content>
    </div>
</tedi:sheet>
