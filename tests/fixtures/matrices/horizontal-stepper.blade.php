<div class="gx-sec">
    <h2>Horizontal stepper</h2>
    <p>Port of <code>navigation/horizontal-stepper</code>
        (<code>tedi-horizontal-stepper</code>, <code>tedi-horizontal-stepper-item</code>).
        Angular numbers its items by walking <code>contentChildren()</code>; Blade can't
        introspect its slot, so each item takes an explicit <code>step-number</code>
        (CONVENTIONS.md §5). <code>compact</code> is not a §7 breakpoint prop — the
        collapsed layout lives in CSS media queries keyed off the emitted
        <code>--compact-{bp}</code> class.</p>

    <div class="gx-case">
        <div class="gx-case__label">default (first step selected)</div>
        <div class="gx-case__demo">
            <tedi:horizontal-stepper aria-label="Vormi edenemine">
                <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :selected="true" />
                <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" />
                <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" />
                <tedi:horizontal-stepper-item label="Vastus" :step-number="4" />
            </tedi:horizontal-stepper>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">item state: completed / selected / default / error</div>
        <div class="gx-case__demo">
            <tedi:horizontal-stepper aria-label="Sammu olekud">
                <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
                <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
                <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" />
                <tedi:horizontal-stepper-item label="Vastus" :step-number="4" :error="true" />
            </tedi:horizontal-stepper>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">descriptions + disabled future step (no --disabled class, see the Blade comment)</div>
        <div class="gx-case__demo">
            <tedi:horizontal-stepper aria-label="Kirjeldustega sammud">
                <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
                <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" description="Ametnik täidab" />
                <tedi:horizontal-stepper-item label="Geenianalüüs" :step-number="3" description="Ametnik täidab" :disabled="true" />
                <tedi:horizontal-stepper-item label="Vastus" :step-number="4" :disabled="true" />
            </tedi:horizontal-stepper>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">background: default / transparent</div>
        <div class="gx-case__demo">
            <tedi:horizontal-stepper aria-label="Läbipaistev taust" background="transparent">
                <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
                <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
                <tedi:horizontal-stepper-item label="Vastus" :step-number="3" />
            </tedi:horizontal-stepper>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">compact: true / false / sm / md / lg / xl / xxl</div>
        <div class="gx-case__demo">
            @foreach ([true, false, 'sm', 'md', 'lg', 'xl', 'xxl'] as $compact)
                <tedi:horizontal-stepper aria-label="Kompaktne" :compact="$compact">
                    <tedi:horizontal-stepper-item label="Kutse" :step-number="1" :completed="true" />
                    <tedi:horizontal-stepper-item label="Tahteavaldus" :step-number="2" :selected="true" />
                    <tedi:horizontal-stepper-item label="Vastus" :step-number="3" />
                </tedi:horizontal-stepper>
            @endforeach
        </div>
    </div>
</div>
