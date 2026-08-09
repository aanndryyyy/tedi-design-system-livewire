<div class="gx-sec">
    <h2>Dropdown</h2>
    <p>Port of <code>overlay/dropdown</code> (<code>tedi-dropdown</code>,
        <code>tedi-dropdown-trigger</code>, <code>tedi-dropdown-content</code>,
        <code>li[tedi-dropdown-item]</code>, <code>tedi-dropdown-item-value</code>).
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
                            <tedi:dropdown-item-value>
                                <tedi:dropdown-item-value-label>Tallinn</tedi:dropdown-item-value-label>
                                <tedi:dropdown-item-value-meta>3 vaba aega</tedi:dropdown-item-value-meta>
                            </tedi:dropdown-item-value>
                        </x-slot:item-value>
                    </tedi:dropdown-item>
                    <tedi:dropdown-item value="tartu" :selected="true">
                        <x-slot:item-value>
                            <tedi:dropdown-item-value>
                                <tedi:dropdown-item-value-label>Tartu</tedi:dropdown-item-value-label>
                                <tedi:dropdown-item-value-meta>5 vaba aega</tedi:dropdown-item-value-meta>
                            </tedi:dropdown-item-value>
                        </x-slot:item-value>
                    </tedi:dropdown-item>
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

<div class="gx-sec">
    <h2>Dropdown item value</h2>
    <p>Port of <code>overlay/dropdown/dropdown-item-value</code>. Rendered outside a
        dropdown here so every <code>type</code> × <code>layout</code> combination is
        visible at once; inside a dropdown it is the item's content.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: default / checkbox / radio × layout: horizontal / vertical</div>
        <div class="gx-case__demo">
            @foreach (['default', 'checkbox', 'radio'] as $type)
                @foreach (['horizontal', 'vertical'] as $layout)
                    <tedi:dropdown-item-value
                        type="{{ $type }}"
                        layout="{{ $layout }}"
                        :selected="$layout === 'vertical'"
                    >
                        <tedi:dropdown-item-value-label>{{ $type }} / {{ $layout }}</tedi:dropdown-item-value-label>
                        <tedi:dropdown-item-value-meta>Doktorid näevad teie terviseandmeid</tedi:dropdown-item-value-meta>
                    </tedi:dropdown-item-value>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">disabled / indeterminate checkbox, no-clip label, icon + after slots</div>
        <div class="gx-case__demo">
            <tedi:dropdown-item-value type="checkbox" :selected="true" :disabled="true">
                <tedi:dropdown-item-value-label>Keelatud, valitud</tedi:dropdown-item-value-label>
            </tedi:dropdown-item-value>

            <tedi:dropdown-item-value type="checkbox" :indeterminate="true">
                <tedi:dropdown-item-value-label>Osaliselt valitud</tedi:dropdown-item-value-label>
            </tedi:dropdown-item-value>

            <tedi:dropdown-item-value type="radio" :selected="true" :disabled="true">
                <tedi:dropdown-item-value-label>Keelatud raadionupp</tedi:dropdown-item-value-label>
            </tedi:dropdown-item-value>

            <tedi:dropdown-item-value>
                <x-slot:icon><tedi:icon name="computer" :size="18" /></x-slot:icon>
                <tedi:dropdown-item-value-label :clip-content="false">Lauaarvuti</tedi:dropdown-item-value-label>
                <x-slot:after><tedi:icon name="chevron_right" :size="18" /></x-slot:after>
            </tedi:dropdown-item-value>
        </div>
    </div>
</div>
