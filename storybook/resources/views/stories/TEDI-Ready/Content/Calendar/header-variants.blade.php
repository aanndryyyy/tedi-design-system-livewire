@storybook([
    'name' => 'Header Variants',
    'order' => 10,
    'status' => 'subset',
    'args' => [],
])

<div style="display: flex; flex-wrap: wrap; gap: 1rem;">
    {{-- `dropdown` renders the trigger only: the listbox panel is a
         <tedi-dropdown-content> overlay, which this package does not ship. --}}
    <tedi:calendar :current-month="date('Y-m-01')" month-year-select-type="dropdown" />
    <tedi:calendar :current-month="date('Y-m-01')" month-year-select-type="grid" />
    <tedi:calendar :current-month="date('Y-m-01')" month-year-select-type="static" />
</div>
