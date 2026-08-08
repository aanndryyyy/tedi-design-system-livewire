{{--
    tedi:pagination always renders the inline desktop pager — the mobile
    page-jump/page-size modal isn't ported (see pagination.blade.php header
    comment and README's divergence table). `page-url` is a closure so it
    isn't a control; every story builds it inline instead.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.45.70?node-id=8478-72385&m=dev',
    'args' => [
        'pageCount' => 10,
        'page' => 3,
        'boundaryCount' => 1,
        'siblingCount' => 1,
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
        'boundaryCount' => [
            'control' => 'number',
            'description' => 'Pages always shown at the start and end of the range.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
        'siblingCount' => [
            'control' => 'number',
            'description' => 'Pages shown on either side of the current page.',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number'], 'defaultValue' => ['summary' => '1']],
        ],
    ],
])

<tedi:pagination
    :page-count="$pageCount"
    :page="$page"
    :boundary-count="$boundaryCount"
    :sibling-count="$siblingCount"
    :page-url="fn ($p) => '?page='.$p"
/>
