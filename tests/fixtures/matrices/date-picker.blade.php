<div class="gx-sec">
    <h2>Date Picker</h2>
    <p>Port of <code>form/date-picker</code>. The popover wrapper is dropped (no popover component in this package), so the calendar panel renders inline under <code>:open="true"</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">input-size: default | small</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker input-size="default" input-placeholder="Vali kuupäev" />
            <tedi:date-picker input-size="small" input-placeholder="Vali kuupäev" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">input-state: default | valid | error</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker input-state="default" input-placeholder="Vali kuupäev" />
            <tedi:date-picker input-state="valid" input-placeholder="Vali kuupäev" />
            <tedi:date-picker input-state="error" input-placeholder="Vali kuupäev" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">selected (clear button) | input-disabled | allow-manual-input=false</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker selected="2024-06-15" />
            <tedi:date-picker selected="2024-06-15" :input-disabled="true" />
            <tedi:date-picker :allow-manual-input="false" input-placeholder="Vali kuupäev" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model binding</div>
        <div class="gx-case__demo">
            <tedi:date-picker wire:model="meetingDate" input-placeholder="Vali kuupäev" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">current-view: calendar-grid | month-grid | year-grid (open)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:date-picker :open="true" current-view="calendar-grid" month="2024-06-01" selected="2024-06-15" />
            <tedi:date-picker :open="true" current-view="month-grid" month="2024-06-01" />
            <tedi:date-picker :open="true" current-view="year-grid" month="2024-06-01" :start-year="2020" :end-year="2043" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">open + show-week-numbers + disabled-days</div>
        <div class="gx-case__demo">
            <tedi:date-picker
                :open="true"
                month="2024-09-01"
                :show-week-numbers="true"
                :disabled-days="['2024-09-10', '2024-09-11']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">year-page-index (second page of the year grid)</div>
        <div class="gx-case__demo">
            <tedi:date-picker :open="true" current-view="year-grid" :start-year="2000" :end-year="2043" :year-page-index="1" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Date Picker Header</h2>
    <p>Port of <code>form/date-picker/date-picker-header</code>. The dropdown panel is not ported, so <code>dropdown</code> and <code>grid</code> both render just the trigger button.</p>

    <div class="gx-case">
        <div class="gx-case__label">month-mode / year-mode: dropdown | grid | label | none</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker-header month="2024-06-01" month-mode="dropdown" year-mode="dropdown" />
            <tedi:date-picker-header month="2024-06-01" month-mode="grid" year-mode="grid" />
            <tedi:date-picker-header month="2024-06-01" month-mode="label" year-mode="label" />
            <tedi:date-picker-header month="2024-06-01" month-mode="none" year-mode="none" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">show-navigation: true | false; can-go-prev/next: false</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker-header month="2024-06-01" :show-navigation="true" />
            <tedi:date-picker-header month="2024-06-01" :show-navigation="false" />
            <tedi:date-picker-header month="2024-06-01" :can-go-prev="false" :can-go-next="false" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">current-view: calendar-grid | month-grid | year-grid</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:date-picker-header current-view="calendar-grid" month="2024-06-01" />
            <tedi:date-picker-header current-view="month-grid" month="2024-06-01" />
            <tedi:date-picker-header current-view="year-grid" :selected-year="2024" :has-prev-year-page="false" :has-next-year-page="true" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Date Picker Calendar Grid</h2>
    <p>Port of <code>form/date-picker/date-picker-calendar-grid</code>. Monday-hardcoded, variable row count, outside days always rendered.</p>

    <div class="gx-case">
        <div class="gx-case__label">4 rows (Feb 2021) | 5 rows (Jun 2024) | 6 rows (Sep 2024)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:date-picker-calendar-grid month="2021-02-01" />
            <tedi:date-picker-calendar-grid month="2024-06-01" />
            <tedi:date-picker-calendar-grid month="2024-09-01" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">show-week-numbers | selected | disabled-days | active-date</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:date-picker-calendar-grid month="2024-06-01" :show-week-numbers="true" />
            <tedi:date-picker-calendar-grid
                month="2024-06-01"
                selected="2024-06-15"
                active-date="2024-06-20"
                :disabled-days="['2024-06-10', '2024-06-11']"
            />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Date Picker Month &amp; Year Grids</h2>
    <p>Port of <code>form/date-picker/date-picker-month-grid</code> and <code>date-picker-year-grid</code>. Both share the <code>tedi-date-picker__month-year-*</code> block.</p>

    <div class="gx-case">
        <div class="gx-case__label">month grid: selected month | disabled months</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:date-picker-month-grid current-month="2024-08-01" />
            <tedi:date-picker-month-grid current-month="2024-08-01" :disabled-months="[0, 1, 2]" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">year grid: page-start / page-size / selected-year | disabled years</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:date-picker-year-grid :page-start="2020" :page-size="12" :selected-year="2024" />
            <tedi:date-picker-year-grid :page-start="2020" :page-size="12" :selected-year="2024" :disabled-years="[2020, 2021]" />
        </div>
    </div>
</div>
