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
</div>
