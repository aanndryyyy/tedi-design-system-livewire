@storybook([
    'name' => 'With Legend',
    'order' => 12,
    'status' => 'subset',
    'args' => [],
])

@php
    $day = fn (int $offset) => date('Y-m-d', mktime(0, 0, 0, (int) date('n'), (int) date('j') + $offset, (int) date('Y')));
@endphp

<tedi:calendar
    :current-month="date('Y-m-01')"
    :available-days="[$day(3), $day(5), $day(7), $day(10)]"
>
    <x-slot:footer>
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <span style="width: 18px; height: 18px; border-radius: 4px; background: var(--form-datepicker-date-selected);"></span>
                Selected
            </span>
            <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                <span style="width: 18px; height: 18px; border-radius: 4px; background: var(--form-datepicker-date-available); border: 1px solid var(--form-datepicker-date-text-available);"></span>
                Available
            </span>
        </div>
    </x-slot:footer>
</tedi:calendar>
