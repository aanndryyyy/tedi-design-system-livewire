{{--
    Angular switches to this branch when the viewport drops below
    `mobileBreakpoint` (BreakpointService, resolved in JS). That is not portable
    (CONVENTIONS.md §7 #1), so the branch is selected explicitly here with
    :dropdown-mode. Both the strip and the dropdown render, exactly as in
    Angular — `.tedi-button-group--dropdown-mode` is what hides the strip.
--}}
@storybook([
    'name' => 'Mobile Dropdown',
    'order' => 10,
    'status' => 'subset',
])

<div style="max-width: 320px;">
    <tedi:button-group
        aria-label="Mobiilis koondub rippmenüüks"
        :dropdown-mode="true"
        dropdown-label="Alammenüü"
        dropdown-label-mode="static"
        :items="[
            ['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table'],
            ['value' => '2', 'label' => 'Loend', 'iconLeft' => 'list'],
            ['value' => '3', 'label' => 'Kalender', 'iconLeft' => 'calendar_month'],
        ]"
    />
</div>
