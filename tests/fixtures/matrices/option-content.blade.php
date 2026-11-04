<div class="gx-sec">
    <h2>Option content</h2>
    <p>Port of <code>helpers/option-content</code>. Rendered outside a
        dropdown here so every <code>type</code> × <code>layout</code> combination is
        visible at once; inside a dropdown it is the item's content.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: default / checkbox / radio × layout: horizontal / vertical</div>
        <div class="gx-case__demo">
            @foreach (['default', 'checkbox', 'radio'] as $type)
                @foreach (['horizontal', 'vertical'] as $layout)
                    <tedi:option-content
                        type="{{ $type }}"
                        layout="{{ $layout }}"
                        :selected="$layout === 'vertical'"
                    >
                        <tedi:option-content-label>{{ $type }} / {{ $layout }}</tedi:option-content-label>
                        <tedi:option-content-meta>Doktorid näevad teie terviseandmeid</tedi:option-content-meta>
                    </tedi:option-content>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">disabled / indeterminate checkbox, no-clip label, icon + after slots</div>
        <div class="gx-case__demo">
            <tedi:option-content type="checkbox" :selected="true" :disabled="true">
                <tedi:option-content-label>Keelatud, valitud</tedi:option-content-label>
            </tedi:option-content>

            <tedi:option-content type="checkbox" :indeterminate="true">
                <tedi:option-content-label>Osaliselt valitud</tedi:option-content-label>
            </tedi:option-content>

            <tedi:option-content type="radio" :selected="true" :disabled="true">
                <tedi:option-content-label>Keelatud raadionupp</tedi:option-content-label>
            </tedi:option-content>

            <tedi:option-content>
                <x-slot:icon><tedi:icon name="computer" :size="18" /></x-slot:icon>
                <tedi:option-content-label :clip-content="false">Lauaarvuti</tedi:option-content-label>
                <x-slot:after><tedi:icon name="chevron_right" :size="18" /></x-slot:after>
            </tedi:option-content>
        </div>
    </div>
</div>
