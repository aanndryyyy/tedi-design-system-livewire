<div class="gx-sec">
    <h2>Number field</h2>
    <p>Port of <code>form/number-field</code>. A native <code>&lt;input type="number"&gt;</code> between a decrement and an increment <code>&lt;tedi:button&gt;</code>. <code>wire:model</code> lands on the input; the buttons are wired with <code>decrement-attributes</code> / <code>increment-attributes</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-size-default" label="Vaikimisi" size="default" />
            <tedi:number-field input-id="nf-size-small" label="Väike" size="small" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: default | invalid | disabled | disabled+invalid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-state-default" label="Vaikimisi" :value="2" />
            <tedi:number-field input-id="nf-state-invalid" label="Vigane" :value="2" :invalid="true" />
            <tedi:number-field input-id="nf-state-disabled" label="Keelatud" :value="2" :disabled="true" />
            <tedi:number-field input-id="nf-state-disabled-invalid" label="Keelatud + vigane" :value="2" :disabled="true" :invalid="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">small × invalid | small × disabled (both modifier axes at once)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-small-invalid" label="Väike + vigane" size="small" :value="2" :invalid="true" />
            <tedi:number-field input-id="nf-small-disabled" label="Väike + keelatud" size="small" :value="2" :disabled="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">min / max: at the bound each button disables, outside it the field is invalid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-at-min" label="Miinimumis" :min="1" :value="1" />
            <tedi:number-field input-id="nf-at-max" label="Maksimumis" :max="5" :value="5" />
            <tedi:number-field input-id="nf-below-min" label="Alla miinimumi" :min="3" :value="1" />
            <tedi:number-field input-id="nf-above-max" label="Üle maksimumi" :max="3" :value="9" />
            <tedi:number-field input-id="nf-in-range" label="Vahemikus" :min="1" :max="9" :value="5" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">step (drives the buttons' aria-label)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-step-1" label="Samm 1" :value="0" />
            <tedi:number-field input-id="nf-step-5" label="Samm 5" :step="5" :value="0" />
            <tedi:number-field input-id="nf-step-decimal" label="Samm 0.5" :step="0.5" :value="1.5" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">suffix | full width | both</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-suffix" label="Ühikuga" suffix="tk" :value="2" />
            <tedi:number-field input-id="nf-full-width" label="Täislaius" :full-width="true" :value="2" />
            <tedi:number-field input-id="nf-suffix-full" label="Ühik + täislaius" suffix="kg" :full-width="true" :value="2" size="small" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">feedback text: hint | error | valid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field
                input-id="nf-hint"
                label="Kogus"
                :value="1"
                :feedback-text="['text' => 'Vihjetekst', 'type' => 'hint']"
            />
            <tedi:number-field
                input-id="nf-error"
                label="Kogus"
                :value="1"
                :invalid="true"
                :feedback-text="['text' => 'Veateade', 'type' => 'error']"
            />
            <tedi:number-field
                input-id="nf-valid"
                label="Kogus"
                :value="1"
                :feedback-text="['text' => 'Korras', 'type' => 'valid', 'position' => 'right']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">required | no visible label (aria-label instead)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field input-id="nf-required" label="Kohustuslik" :required="true" :value="1" />
            <tedi:number-field input-id="nf-aria-only" aria-label="Kogus" :value="1" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model on the input + wire:click hooks on the buttons</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:number-field
                input-id="nf-wire"
                label="Kogus"
                wire:model.live="quantity"
                :decrement-attributes="['wire:click' => 'decrement']"
                :increment-attributes="['wire:click' => 'increment']"
            />
        </div>
    </div>
</div>
