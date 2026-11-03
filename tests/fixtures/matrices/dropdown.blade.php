<div class="gx-sec">
    <h2>Dropdown</h2>
    <p>Port of <code>overlay/dropdown</code> (<code>tedi-dropdown</code>,
        <code>tedi-dropdown-trigger</code>, <code>tedi-dropdown-content</code>,
        <code>li[tedi-dropdown-item]</code>, <code>tedi-option-content</code>).
        Anchoring runs on the shared <code>tediOverlay</code> Alpine component
        (CONVENTIONS.md §11); the panel is in the DOM with its real class list even
        while closed, so the classes below are harvestable.</p>

    <div class="gx-case">
        <div class="gx-case__label">menu role, one disabled item</div>
        <div class="gx-case__demo">
            <tedi:dropdown container-id="matrix-menu">
                <tedi:dropdown-trigger><tedi:button>Tegevused</tedi:button></tedi:dropdown-trigger>
                <tedi:dropdown-content>
                    <tedi:dropdown-item>Ligipääs terviseandmetele</tedi:dropdown-item>
                    <tedi:dropdown-item :disabled="true">Tahteavaldus</tedi:dropdown-item>
                    <tedi:dropdown-item>Kontaktid</tedi:dropdown-item>
                </tedi:dropdown-content>
            </tedi:dropdown>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">listbox role, selected item, meta text (horizontal)</div>
        <div class="gx-case__demo">
            <tedi:dropdown container-id="matrix-listbox" value="tartu" position="bottom-end" :offset="8">
                <tedi:dropdown-trigger aria-haspopup="listbox"><tedi:button>Vali asukoht</tedi:button></tedi:dropdown-trigger>
                <tedi:dropdown-content dropdown-role="listbox">
                    <tedi:dropdown-item value="tallinn">
                        <x-slot:item-value>
                            <tedi:option-content>
                                <tedi:option-content-label>Tallinn</tedi:option-content-label>
                                <tedi:option-content-meta>3 vaba aega</tedi:option-content-meta>
                            </tedi:option-content>
                        </x-slot:item-value>
                    </tedi:dropdown-item>
                    <tedi:dropdown-item value="tartu" :selected="true">
                        <x-slot:item-value>
                            <tedi:option-content>
                                <tedi:option-content-label>Tartu</tedi:option-content-label>
                                <tedi:option-content-meta>5 vaba aega</tedi:option-content-meta>
                            </tedi:option-content>
                        </x-slot:item-value>
                    </tedi:dropdown-item>
                </tedi:dropdown-content>
            </tedi:dropdown>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">list role (plain links), and interactive-content items in a menu</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:dropdown container-id="matrix-list">
                <tedi:dropdown-trigger><tedi:button>Lingid</tedi:button></tedi:dropdown-trigger>
                <tedi:dropdown-content dropdown-role="list">
                    <tedi:dropdown-item><a href="#tervis">Terviseandmed</a></tedi:dropdown-item>
                    <tedi:dropdown-item><a href="#kontaktid">Kontaktid</a></tedi:dropdown-item>
                </tedi:dropdown-content>
            </tedi:dropdown>

            <tedi:dropdown container-id="matrix-interactive">
                <tedi:dropdown-trigger><tedi:button>Tegevused</tedi:button></tedi:dropdown-trigger>
                <tedi:dropdown-content>
                    <tedi:dropdown-item :interactive-content="true"><button type="button">Muuda</button></tedi:dropdown-item>
                    <tedi:dropdown-item :interactive-content="true" :disabled="true"><button type="button">Kustuta</button></tedi:dropdown-item>
                </tedi:dropdown-content>
            </tedi:dropdown>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position union + prevent-overflow / hide-on-scroll flags</div>
        <div class="gx-case__demo gx-case__demo--row">
            @foreach (['auto', 'auto-start', 'auto-end', 'top', 'top-start', 'top-end', 'bottom', 'bottom-start', 'bottom-end', 'right', 'right-start', 'right-end', 'left', 'left-start', 'left-end'] as $position)
                <tedi:dropdown
                    position="{{ $position }}"
                    :prevent-overflow="$position !== 'top'"
                    :hide-on-scroll="$position === 'bottom'"
                >
                    <tedi:dropdown-trigger><tedi:button size="small">{{ $position }}</tedi:button></tedi:dropdown-trigger>
                    <tedi:dropdown-content>
                        <tedi:dropdown-item>Kontaktid</tedi:dropdown-item>
                    </tedi:dropdown-content>
                </tedi:dropdown>
            @endforeach
        </div>
    </div>
</div>
