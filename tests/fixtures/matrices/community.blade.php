<div class="gx-sec">
    <h2>Community components</h2>
    <p>The five components that exist only in Angular's <code>community/</code> entry point
        (CONVENTIONS.md §12): <code>floating-button</code>, <code>choicegroup</code>,
        <code>file-dropzone</code>, <code>table-of-contents</code> and
        <code>vertical-stepper</code>. This matrix exists to harvest every interpolated
        modifier class they emit — see <code>tests/IntegrityTest.php</code>.</p>

    <h3>Floating button</h3>

    <div class="gx-case">
        <div class="gx-case__label">variant × size</div>
        <div class="gx-case__demo">
            @foreach (['primary', 'secondary'] as $variant)
                @foreach (['default', 'large'] as $size)
                    <tedi:floating-button :variant="$variant" :size="$size">Tagasiside</tedi:floating-button>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">axis (horizontal emits no class — see the Blade comment)</div>
        <div class="gx-case__demo">
            @foreach (['horizontal', 'vertical'] as $axis)
                <tedi:floating-button :axis="$axis">Tagasiside</tedi:floating-button>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon padding modifiers</div>
        <div class="gx-case__demo">
            <tedi:floating-button icon-start="add">Lisa</tedi:floating-button>
            <tedi:floating-button icon-end="arrow_forward">Edasi</tedi:floating-button>
            <tedi:floating-button icon-only icon-start="add" aria-label="Lisa" />
            <tedi:floating-button disabled>Tagasiside</tedi:floating-button>
        </div>
    </div>

    <h3>Choicegroup</h3>

    <div class="gx-case">
        <div class="gx-case__label">variant × spacing × indicator</div>
        <div class="gx-case__demo">
            @foreach (['primary', 'secondary'] as $variant)
                @foreach ([4, 0] as $spacing)
                    @foreach ([true, false] as $hasIndicator)
                        <tedi:choicegroup :variant="$variant" :spacing="$spacing" :has-indicator="$hasIndicator">
                            <tedi:radio name="matrix-{{ $variant }}-{{ $spacing }}-{{ (int) $hasIndicator }}" value="jah" label="Jah" />
                            <tedi:radio name="matrix-{{ $variant }}-{{ $spacing }}-{{ (int) $hasIndicator }}" value="ei" label="Ei" />
                        </tedi:choicegroup>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>

    <h3>File dropzone</h3>

    <div class="gx-case">
        <div class="gx-case__label">state × error × disabled</div>
        <div class="gx-case__demo">
            @foreach (['none', 'valid', 'invalid'] as $state)
                <tedi:file-dropzone :state="$state" name="matrix-{{ $state }}" accept=".pdf,.docx" :max-size="5242880" />
            @endforeach

            <tedi:file-dropzone name="matrix-error" has-error error="Faili ei õnnestunud laadida" />
            <tedi:file-dropzone name="matrix-disabled" disabled />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">file list</div>
        <div class="gx-case__demo">
            <tedi:file-dropzone
                name="matrix-files"
                multiple
                :files="[
                    ['name' => 'avaldus.pdf', 'size' => 943718],
                    ['name' => 'lisa.docx', 'size' => 1200, 'error' => 'Vale laiend'],
                ]"
            />
        </div>
    </div>

    <h3>Table of contents</h3>

    <div class="gx-case">
        <div class="gx-case__label">position × modal breakpoint</div>
        <div class="gx-case__demo">
            @foreach (['default', 'fixed', 'sticky'] as $position)
                @foreach (['mobile', 'tablet', 'desktop', 'never'] as $breakpoint)
                    <tedi:table-of-contents heading="Sisukord" :position="$position" :modal-breakpoint="$breakpoint">
                        <tedi:table-of-contents-item id-to="ptk-1" :selected="true">Üldsätted</tedi:table-of-contents-item>
                        <tedi:table-of-contents-item id-to="ptk-2">
                            Rakendusala
                            <x-slot:sub-items>
                                <tedi:table-of-contents-item id-to="ptk-2-1">Erisused</tedi:table-of-contents-item>
                            </x-slot:sub-items>
                        </tedi:table-of-contents-item>
                    </tedi:table-of-contents>
                @endforeach
            @endforeach
        </div>
    </div>

    <h3>Vertical stepper</h3>

    <div class="gx-case">
        <div class="gx-case__label">item states</div>
        <div class="gx-case__demo">
            <tedi:vertical-stepper aria-label="Menetluse sammud">
                <tedi:vertical-stepper-item title="Esitatud" :completed="true" href="#1" />
                <tedi:vertical-stepper-item title="Menetluses" :selected="true">
                    <x-slot:description>Tähtaeg 12.03</x-slot:description>
                </tedi:vertical-stepper-item>
                <tedi:vertical-stepper-item title="Puudustega" :error="true" />
                <tedi:vertical-stepper-item title="Ootel" :disabled="true" />
                <tedi:vertical-stepper-item title="Teadmiseks" :informative="true" />
            </tedi:vertical-stepper>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">compact + enumerated, with sub-items</div>
        <div class="gx-case__demo">
            <tedi:vertical-stepper aria-label="Kompaktne" :compact="true" :enumerated="true">
                <tedi:vertical-stepper-item title="Esitatud" :completed="true" />
                <tedi:vertical-stepper-item title="Menetluses" :selected="true" :opened="true">
                    <x-slot:sub-items>
                        <tedi:vertical-stepper-item title="Kontroll" :sub-item="true" :completed="true" />
                        <tedi:vertical-stepper-item title="Otsus" :sub-item="true" :error="true" />
                    </x-slot:sub-items>
                </tedi:vertical-stepper-item>
            </tedi:vertical-stepper>
        </div>
    </div>
</div>
