@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.8.9--work-in-progress-?node-id=6367-171750&m=dev',
    'layout' => 'fullscreen',
    'args' => [
        'dividers' => true,
        'size' => 'large',
        'collapsible' => false,
    ],
    'argTypes' => [
        'dividers' => [
            'control' => 'boolean',
            'description' => 'Show dividers between items',
            'table' => ['category' => 'sidenav', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['small', 'medium', 'large'],
            'description' => 'Size of navigation item',
            'table' => ['category' => 'sidenav', 'type' => ['summary' => 'SideNavItemSize'], 'defaultValue' => ['summary' => 'large']],
        ],
        'collapsible' => [
            'control' => 'boolean',
            'description' => 'Is navigation collapsible in desktop?',
            'table' => ['category' => 'sidenav', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'itemSelected' => [
            'name' => 'selected',
            'control' => 'boolean',
            'description' => 'Is navigation item selected',
            'table' => ['category' => 'sidenav-item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'itemIcon' => [
            'name' => 'icon',
            'control' => 'text',
            'description' => 'Name of the item icon',
            'table' => ['category' => 'sidenav-item', 'type' => ['summary' => 'string']],
        ],
        'itemHref' => [
            'name' => 'href',
            'control' => 'text',
            'description' => 'External link',
            'table' => ['category' => 'sidenav-item', 'type' => ['summary' => 'string']],
        ],
        'itemRoute' => [
            'name' => 'route',
            'control' => 'text',
            'description' => 'Router link',
            'table' => ['category' => 'sidenav-item', 'type' => ['summary' => 'string']],
        ],
        'dropdownItemSelected' => [
            'name' => 'selected',
            'control' => 'boolean',
            'description' => 'Is dropdown item selected',
            'table' => ['category' => 'sidenav-dropdown-item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'dropdownItemHref' => [
            'name' => 'href',
            'control' => 'text',
            'description' => 'External link',
            'table' => ['category' => 'sidenav-dropdown-item', 'type' => ['summary' => 'string']],
        ],
        'dropdownItemRoute' => [
            'name' => 'route',
            'control' => 'text',
            'description' => 'Router link',
            'table' => ['category' => 'sidenav-dropdown-item', 'type' => ['summary' => 'string']],
        ],
    ],
])

<tedi:sidenav.toggle />
<tedi:sidenav.overlay />
<div style="height: 1024px;">
    <tedi:sidenav :dividers="(bool) $dividers" :size="$size" :collapsible="(bool) $collapsible">
        <tedi:sidenav.item icon="home" href="#" label="Home">
            Home
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="account_box" href="#" label="Clients">
            Clients
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="child_care" href="#" label="Children" :selected="true">
            Children
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="edit" route="#" label="Some very long text that wraps to new line">
            Some very long text that wraps to new line
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="assignment" href="#" label="Assignments">
            Assignments
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="payments" route="#" label="Payments">
            Payments
        </tedi:sidenav.item>
    </tedi:sidenav>
</div>
