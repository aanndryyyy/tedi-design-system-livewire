@php
    $tags = [];
    foreach (['08.06.2026', '09.06.2026', '10.06.2026', '11.06.2026'] as $i => $label) {
        $tags[] = ['id' => (string) $i, 'label' => $label];
    }

    $values = ['2026-06-08', '2026-06-09', '2026-06-10', '2026-06-11'];

    $disabledDays = [
        date('Y-m-d', strtotime('+2 days')),
        date('Y-m-d', strtotime('+3 days')),
    ];
@endphp

<div class="gx-sec">
    <h2>Date input</h2>
    <p>
        Port of <code>form/date-field/date-input</code>. Note that neither
        <code>tedi-date-input--disabled</code> nor <code>tedi-date-input--readonly</code> is
        emitted — TEDI ships no rule for either, so both are dropped per CONVENTIONS §4. The
        states still render as the native <code>disabled</code> / <code>readonly</code>
        attributes on the control.
    </p>

    <div class="gx-case">
        <div class="gx-case__label">default — empty, no clear button</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-default" placeholder="pp.kk.aaaa" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">value + clearable — clear button, separator, icon</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-clearable" value="08.06.2026" :clearable="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon-active — the open state of the calendar button</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-icon-active" value="08.06.2026" :icon-active="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon-disabled — calendar off, field still editable</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-icon-disabled" :icon-disabled="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: disabled | read-only (clear still shows) | required | name + described-by + invalid (no host modifier classes)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-input input-id="di-disabled" value="08.06.2026" :disabled="true" :clearable="true" />
            <tedi:date-input input-id="di-readonly" value="08.06.2026" :read-only="true" :clearable="true" />
            <tedi:date-input input-id="di-named" name="start" aria-describedby="di-named-hint" :invalid="true" />
            <tedi:date-input input-id="di-required" :required="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: single | range — tags never render outside `multiple`</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-input input-id="di-single" mode="single" :tags="$tags" value="08.06.2026" />
            <tedi:date-input input-id="di-range" mode="range" :tags="$tags" value="08.06.2026 – 11.06.2026" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: multiple, multi-row — with-tags + tags-wrap</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-tags-wrap" mode="multiple" :tags="$tags" :clearable="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: multiple, single row, unmeasured — tags-single-row + tags-measuring</div>
        <div class="gx-case__demo">
            <tedi:date-input input-id="di-tags-measuring" mode="multiple" :multi-row="false" :tags="$tags" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: multiple, single row, measured — +N counter, ellipsis start</div>
        <div class="gx-case__demo">
            <tedi:date-input
                input-id="di-tags-counter"
                mode="multiple"
                :multi-row="false"
                :visible-tag-count="2"
                ellipsis="start"
                :tags="$tags"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">ellipsis: end | removable: false — read-only chips</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-input input-id="di-tags-ellipsis-end" mode="multiple" ellipsis="end" :tags="$tags" />
            <tedi:date-input input-id="di-tags-fixed" mode="multiple" :removable="false" :tags="$tags" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Date field</h2>
    <p>
        Port of <code>form/date-field</code>. The CDK-overlay panel is dropped: the
        <code>tedi-date-field__overlay</code> renders inline under an explicit <code>open</code>
        prop (CONVENTIONS §7 item 3). Since Angular 8.0 the field paints its own
        <code>tedi-field-surface</code> unless a form-field box (one with an <code>icon</code>)
        owns it, and <code>size</code> / <code>clearable</code> / state fall back to the nearest
        form-field's.
    </p>

    <div class="gx-case">
        <div class="gx-case__label">default — closed, input only</div>
        <div class="gx-case__demo">
            <tedi:date-field input-id="df-default" placeholder="pp.kk.aaaa" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small | inherited small | inherited large</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-field input-id="df-size-default" size="default" />
            <tedi:date-field input-id="df-size-small" size="small" />
            <tedi:form-field size="small"><tedi:date-field input-id="df-size-ff-small" /></tedi:form-field>
            <tedi:form-field size="large"><tedi:date-field input-id="df-size-ff-large" /></tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: input-disabled | read-only (still clearable) | read-only, no calendar | required | invalid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-field input-id="df-disabled" value="2026-06-08" display="08.06.2026" :input-disabled="true" />
            <tedi:date-field input-id="df-readonly" value="2026-06-08" display="08.06.2026" :read-only="true" />
            <tedi:date-field input-id="df-readonly-nocal" value="2026-06-08" display="08.06.2026" :read-only="true" :enable-calendar="false" />
            <tedi:date-field input-id="df-required" :required="true" />
            <tedi:date-field input-id="df-invalid" :invalid="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">field surface from the form-field: valid | invalid | disabled | icon (box owns the surface)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :valid="true"><tedi:date-field input-id="df-ff-valid" /></tedi:form-field>
            <tedi:form-field :invalid="true"><tedi:date-field input-id="df-ff-invalid" /></tedi:form-field>
            <tedi:form-field :disabled="true"><tedi:date-field input-id="df-ff-disabled" /></tedi:form-field>
            <tedi:form-field icon="info"><tedi:date-field input-id="df-ff-icon" /></tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">clearable: form-field false | own true wins | name + aria-describedby</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :clearable="false"><tedi:date-field input-id="df-ff-noclear" value="2026-06-08" display="08.06.2026" /></tedi:form-field>
            <tedi:form-field :clearable="false"><tedi:date-field input-id="df-own-clear" value="2026-06-08" display="08.06.2026" :clearable="true" /></tedi:form-field>
            <tedi:date-field input-id="df-name" name="start" aria-describedby="df-name-hint" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">calendar-trigger: button | input — input trigger makes the control read-only</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-field input-id="df-trigger-button" calendar-trigger="button" />
            <tedi:date-field input-id="df-trigger-input" calendar-trigger="input" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">enable-calendar: false — icon button disabled, no panel</div>
        <div class="gx-case__demo">
            <tedi:date-field input-id="df-no-calendar" :enable-calendar="false" :open="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: multiple — tags forwarded into the date-input</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-field input-id="df-multiple" mode="multiple" :value="$values" :tags="$tags" />
            <tedi:date-field
                input-id="df-multiple-single-row"
                mode="multiple"
                :multi-row="false"
                :visible-tag-count="2"
                tag-ellipsis="start"
                :is-tag-removable="false"
                :value="$values"
                :tags="$tags"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open — the inline overlay, borderless calendar</div>
        <div class="gx-case__demo">
            <tedi:date-field input-id="df-open" :open="true" display="08.06.2026" value="2026-06-08" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open + range + week numbers + two months + disabled days</div>
        <div class="gx-case__demo">
            <tedi:date-field
                input-id="df-open-range"
                mode="range"
                :value="['from' => date('Y-m-05'), 'to' => date('Y-m-12')]"
                :display="date('05.m.Y').' – '.date('12.m.Y')"
                :show-week-numbers="true"
                :number-of-months="2"
                :disabled-days="$disabledDays"
                :open="true"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">selection-level: months | years — header select type grid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-field
                input-id="df-months"
                selection-level="months"
                month-year-select-type="grid"
                :open="true"
            />
            <tedi:date-field
                input-id="df-years"
                selection-level="years"
                month-year-select-type="grid"
                :open="true"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open + show-outside-days off, first-day-of-week Sunday</div>
        <div class="gx-case__demo">
            <tedi:date-field
                input-id="df-outside-days"
                :show-outside-days="false"
                :first-day-of-week="0"
                :open="true"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open + footer slot</div>
        <div class="gx-case__demo">
            <tedi:date-field input-id="df-footer" :open="true"><x-slot:footer><tedi:button variant="neutral" size="small" type="button">Vali kellaaeg</tedi:button></x-slot:footer></tedi:date-field>
        </div>
    </div>
</div>
