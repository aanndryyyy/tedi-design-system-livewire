<div class="gx-sec">
    <h2>Time picker</h2>
    <p>
        Port of <code>form/time-picker</code>. Note that <strong>none</strong> of the three
        <code>tedi-time-picker--&lt;variant&gt;</code> classes is emitted — TEDI ships no rule for
        any of them, so they are dropped per CONVENTIONS §4. <code>variant</code> only chooses
        which markup branch renders.
    </p>

    <div class="gx-case">
        <div class="gx-case__label">variant: scroll (default) — parks on 12:00 with no value</div>
        <div class="gx-case__demo">
            <tedi:time-picker />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: scroll — value + minute-step 15</div>
        <div class="gx-case__demo">
            <tedi:time-picker value="14:30" :minute-step="15" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: disabled | bordered | disabled + bordered</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-picker value="09:30" :disabled="true" />
            <tedi:time-picker value="09:30" :border="true" />
            <tedi:time-picker value="09:30" :disabled="true" :border="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: dropdown — selected slot, roving tabindex</div>
        <div class="gx-case__demo">
            <tedi:time-picker
                variant="dropdown"
                value="13:30"
                :time-slots="['12:30', '13:00', '13:30', '14:00', '14:30']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: dropdown — disabled</div>
        <div class="gx-case__demo">
            <tedi:time-picker
                variant="dropdown"
                value="13:30"
                :disabled="true"
                :time-slots="['12:30', '13:30']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: slots — without / with indicator</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-picker
                variant="slots"
                value="11:30"
                :columns="3"
                :border="true"
                :time-slots="['09:30', '10:00', '11:30', '15:30', '18:30', '20:30']"
            />
            <tedi:time-picker
                variant="slots"
                value="11:30"
                :columns="3"
                :border="true"
                :show-slot-indicator="true"
                :time-slots="['09:30', '10:00', '11:30', '15:30', '18:30', '20:30']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: slots | dropdown — empty state</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-picker variant="slots" />
            <tedi:time-picker variant="dropdown" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Time field</h2>
    <p>
        Port of <code>form/time-field</code>. The popover is rendered inline under an explicit
        <code>open</code> prop — overlay placement is out of scope (CONVENTIONS §7 item 3) — and
        the mobile-modal branch is not ported at all.
    </p>

    <div class="gx-case">
        <div class="gx-case__label">default (closed, button trigger)</div>
        <div class="gx-case__demo">
            <tedi:form-field>
                <tedi:form.label for="mx-time-default">Aeg</tedi:form.label>
                <tedi:time-field input-id="mx-time-default" placeholder="tt:mm" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">picker-trigger: button | input</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-field input-id="mx-time-trigger-button" value="03:03" picker-trigger="button" />
            <tedi:time-field input-id="mx-time-trigger-input" value="03:03" picker-trigger="input" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: value + clear | invalid | disabled | not clearable</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-field input-id="mx-time-value" value="12:00" />
            <tedi:time-field input-id="mx-time-invalid" value="12:00" :invalid="true" />
            <tedi:time-field input-id="mx-time-disabled" value="12:00" :disabled="true" />
            <tedi:time-field input-id="mx-time-no-clear" value="12:00" :clearable="false" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">picker-variant: none — static icon, no popover</div>
        <div class="gx-case__demo">
            <tedi:time-field input-id="mx-time-none" placeholder="tt:mm" picker-variant="none" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open: scroll | slots | dropdown</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:time-field input-id="mx-time-open-scroll" value="14:30" :open="true" :minute-step="15" />
            <tedi:time-field
                input-id="mx-time-open-slots"
                value="11:30"
                :open="true"
                picker-variant="slots"
                picker-trigger="input"
                :columns="3"
                :time-slots="['09:30', '10:00', '11:30', '15:30']"
            />
            <tedi:time-field
                input-id="mx-time-open-dropdown"
                value="13:30"
                :open="true"
                picker-variant="dropdown"
                picker-trigger="input"
                :time-slots="['12:30', '13:00', '13:30']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model binding</div>
        <div class="gx-case__demo">
            <tedi:time-field input-id="mx-time-wire" wire:model="algus" />
        </div>
    </div>
</div>
