{{--
    Angular's ControlledPage story demonstrates two-way `[(page)]` binding —
    this port renders the same static markup for a given page since page
    navigation isn't re-emitted (CONVENTIONS.md §3); `page-url` still makes
    the rendered page links real anchors.
--}}
@storybook([
    'name' => 'Controlled Page',
    'order' => 7,
    'status' => 'subset',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
    ],
    'argTypes' => [
        'pageCount' => [
            'control' => 'number',
            'description' => 'Total number of pages.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'page' => [
            'control' => 'number',
            'description' => 'Current page (1-based). Two-way bindable via `[(page)]` in Angular; static here.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :page-url="fn ($p) => '?page='.$p"
/>
