@php
    // A fixed month keeps the harvested class list stable whenever the suite runs.
    $month = '2030-05-01';
@endphp

<div class="gx-sec">
    <h2>Calendar</h2>
    <p>Port of <code>content/calendar</code>. Documented subset: <code>monthYearSelectType="dropdown"</code> renders the header trigger only — the dropdown listbox panel needs an overlay component this package does not ship.</p>

    <div class="gx-case">
        <div class="gx-case__label">view: days | months | years</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:calendar view="days" :current-month="$month" />
            <tedi:calendar view="months" :current-month="$month" value="2030-05-16" />
            <tedi:calendar view="years" :current-month="$month" value="2030-05-16" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">bordered: true | false &middot; inputDisabled &middot; showWeekNumbers</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:calendar :current-month="$month" :bordered="true" />
            <tedi:calendar :current-month="$month" :bordered="false" />
            <tedi:calendar :current-month="$month" :input-disabled="true" />
            <tedi:calendar :current-month="$month" :show-week-numbers="true" />
        </div>
    </div>

    <div class="gx-case">
        {{-- numberOfMonths > 1 must NOT emit tedi-calendar--multi-month: TEDI ships
             no rule for it (CALENDAR-SPEC §0.3), so the class is dropped. --}}
        <div class="gx-case__label">numberOfMonths: 2 (no --multi-month class; --_tedi-calendar-month-count instead)</div>
        <div class="gx-case__demo">
            <tedi:calendar :current-month="$month" :number-of-months="2" :show-navigation="false" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">footer slot (the :empty rule hides it when nothing is projected)</div>
        <div class="gx-case__demo">
            <tedi:calendar :current-month="$month">
                <x-slot:footer>
                    <tedi:button variant="neutral" size="small" icon-start="schedule">Vali kellaaeg</tedi:button>
                </x-slot:footer>
            </tedi:calendar>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Calendar Header</h2>
    <p>Port of <code>content/calendar/calendar-header</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">monthYearSelectType: dropdown | grid | static (view: days)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-header view="days" :current-month="$month" month-year-select-type="dropdown" />
            <tedi:calendar-header view="days" :current-month="$month" month-year-select-type="grid" />
            <tedi:calendar-header view="days" :current-month="$month" month-year-select-type="static" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">view: months | years &middot; showNavigation: false &middot; inputDisabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-header view="months" :current-month="$month" />
            <tedi:calendar-header view="years" :current-month="$month" />
            <tedi:calendar-header view="days" :current-month="$month" :show-navigation="false" />
            <tedi:calendar-header view="days" :current-month="$month" :input-disabled="true" />
            <tedi:calendar-header view="days" :current-month="$month" :prev-disabled="true" :next-disabled="true" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Calendar Day Grid</h2>
    <p>Port of <code>content/calendar/calendar-day-grid</code>. All twelve <code>__day--*</code> modifiers are exercised below.</p>

    <div class="gx-case">
        <div class="gx-case__label">mode: single &middot; outside / today / selected</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-day-grid :month="$month" mode="single" value="2030-05-10" />
            <tedi:calendar-day-grid :month="date('Y-m-01')" mode="single" :value="date('Y-m-d')" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: multiple &middot; showOutsideDays: false &middot; showWeekNumbers</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-day-grid :month="$month" mode="multiple" :value="['2030-05-10', '2030-05-12']" />
            <tedi:calendar-day-grid :month="$month" :show-outside-days="false" />
            <tedi:calendar-day-grid :month="$month" :show-week-numbers="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">mode: range &mdash; committed (start/end/middle) and preview (both directions)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-day-grid :month="$month" mode="range" :value="['from' => '2030-05-10', 'to' => '2030-05-15']" />
            <tedi:calendar-day-grid :month="$month" mode="range" :value="['from' => '2030-05-10', 'to' => null]" hovered-date="2030-05-15" />
            <tedi:calendar-day-grid :month="$month" mode="range" :value="['from' => '2030-05-15', 'to' => null]" hovered-date="2030-05-10" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">availableDays / unavailableDays / disabledDays / inputDisabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-day-grid :month="$month" :available-days="['2030-05-10', '2030-05-11', '2030-05-12']" />
            <tedi:calendar-day-grid :month="$month" :unavailable-days="['2030-05-10', '2030-05-11']" />
            <tedi:calendar-day-grid :month="$month" :disabled-days="['2030-05-10']" />
            <tedi:calendar-day-grid :month="$month" :input-disabled="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">dayStatus (status indicator overlay) &middot; firstDayOfWeek: 0</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-day-grid
                :month="$month"
                :day-status="[
                    '2030-05-08' => ['type' => 'success', 'label' => 'Kinnitatud'],
                    '2030-05-14' => ['type' => 'warning', 'label' => 'Ootel'],
                    '2030-05-20' => ['type' => 'danger', 'label' => 'Tühistatud'],
                    '2030-05-22' => ['type' => 'inactive', 'label' => 'Möödunud'],
                ]"
            />
            <tedi:calendar-day-grid :month="$month" :first-day-of-week="0" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Calendar Month Grid</h2>
    <p>Port of <code>content/calendar/calendar-month-grid</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">monthNameFormat: short | long &middot; selected / current / disabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-month-grid :year="2030" month-name-format="short" selected-month="2030-05-01" :disabled-months="[0, 1]" />
            <tedi:calendar-month-grid :year="2030" month-name-format="long" />
            <tedi:calendar-month-grid :year="(int) date('Y')" />
            <tedi:calendar-month-grid :year="2030" :input-disabled="true" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Calendar Year Grid</h2>
    <p>Port of <code>content/calendar/calendar-year-grid</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">pageStart / pageSize &middot; selected / current / disabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:calendar-year-grid :page-start="2030" :selected-year="2032" :disabled-years="[2031]" />
            <tedi:calendar-year-grid :page-start="(int) date('Y')" :page-size="6" />
            <tedi:calendar-year-grid :page-start="2030" :input-disabled="true" />
        </div>
    </div>
</div>
