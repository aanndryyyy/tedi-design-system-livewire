<div class="gx-sec">
    <h2>Modal</h2>
    <p>Port of <code>overlay/modal</code> — the standalone <code>[(open)]</code> branch only. The <code>ModalService</code> / CDK Dialog branch (<code>tedi-modal--service</code>, <code>tedi-modal-dialog*</code>) is not ported.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo">
            @foreach (['default', 'small'] as $size)
                <tedi:modal :size="$size" :open="true">
                    <tedi:modal-header>
                        <h1>Uus toiming</h1>
                        <x-slot:description>
                            <p tedi-modal-description>Täida vorm ja kinnita.</p>
                        </x-slot:description>
                    </tedi:modal-header>
                    <tedi:modal-content>Sisu</tedi:modal-content>
                    <tedi:modal-footer>
                        <tedi:button variant="secondary">Katkesta</tedi:button>
                        <tedi:button>Lisa</tedi:button>
                    </tedi:modal-footer>
                </tedi:modal>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">width presets: xs | sm | md | lg | xl, plus a custom CSS length</div>
        <div class="gx-case__demo">
            @foreach (['xs', 'sm', 'md', 'lg', 'xl', '800px'] as $width)
                <tedi:modal :width="$width" :open="true">
                    <tedi:modal-header><h1>Laius: {{ $width }}</h1></tedi:modal-header>
                    <tedi:modal-content>Sisu</tedi:modal-content>
                </tedi:modal>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position: center | top | bottom (emits no class) | left | right</div>
        <div class="gx-case__demo">
            @foreach (['center', 'top', 'bottom', 'left', 'right'] as $position)
                <tedi:modal :position="$position" :open="true">
                    <tedi:modal-header><h1>Asukoht: {{ $position }}</h1></tedi:modal-header>
                    <tedi:modal-content>Sisu</tedi:modal-content>
                </tedi:modal>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">closed (no --open) | no close button | no backdrop close</div>
        <div class="gx-case__demo">
            <tedi:modal>
                <tedi:modal-header><h1>Suletud</h1></tedi:modal-header>
                <tedi:modal-content>Sisu</tedi:modal-content>
            </tedi:modal>
            <tedi:modal :open="true">
                <tedi:modal-header :show-close="false"><h1>Ilma sulgemisnuputa</h1></tedi:modal-header>
                <tedi:modal-content>Sisu</tedi:modal-content>
            </tedi:modal>
            <tedi:modal :open="true" :close-on-backdrop-click="false">
                <tedi:modal-header close-button-size="small"><h1>Tausta klikk ei sulge</h1></tedi:modal-header>
                <tedi:modal-content>Sisu</tedi:modal-content>
                <tedi:modal-footer style="justify-content: space-between">
                    <tedi:button variant="secondary">Katkesta</tedi:button>
                    <tedi:button>Jätka</tedi:button>
                </tedi:modal-footer>
            </tedi:modal>
        </div>
    </div>
</div>
