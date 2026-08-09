@storybook([
    'name' => 'Third Level Menu Items',
    'order' => 4,
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
        <tedi:sidenav.item icon="dashboard" href="#" label="Dashboard">
            Dashboard
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="people" route="#" label="Patient Records">
            Patient Records
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="medical_services" label="Clinical Management" :has-dropdown="true">
            Clinical Management
            <x-slot:dropdown>
                <tedi:sidenav.dropdown parent-label="Clinical Management">
                    <tedi:sidenav.dropdown-item href="#">Vital Signs</tedi:sidenav.dropdown-item>
                    <tedi:sidenav.dropdown-item href="#">Assessments</tedi:sidenav.dropdown-item>
                    <tedi:sidenav.dropdown-group :items="[
                        ['label' => 'Treatments', 'href' => '#'],
                        ['label' => 'Active Treatments', 'href' => '#'],
                        ['label' => 'Treatment History', 'href' => '#'],
                        ['label' => 'Treatment Plans', 'href' => '#'],
                        ['label' => 'Clinical Protocols', 'href' => '#'],
                    ]" />
                    <tedi:sidenav.dropdown-group :items="[
                        ['label' => 'Documentation', 'href' => '#'],
                        ['label' => 'Clinical Notes', 'href' => '#'],
                        ['label' => 'Medical Forms', 'href' => '#'],
                        ['label' => 'Consent Forms', 'href' => '#'],
                        ['label' => 'Reports', 'href' => '#'],
                    ]" />
                </tedi:sidenav.dropdown>
            </x-slot:dropdown>
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="admin_panel_settings" label="Administration" :has-dropdown="true">
            Administration
            <x-slot:dropdown>
                <tedi:sidenav.dropdown parent-label="Administration">
                    <tedi:sidenav.dropdown-group :items="[
                        ['label' => 'Staff Management', 'route' => '#'],
                        ['label' => 'Scheduling', 'route' => '#'],
                        ['label' => 'System Settings', 'route' => '#'],
                    ]" />
                    <tedi:sidenav.dropdown-item route="#">Reports & Analytics</tedi:sidenav.dropdown-item>
                    <tedi:sidenav.dropdown-item route="#">Statistics</tedi:sidenav.dropdown-item>
                </tedi:sidenav.dropdown>
            </x-slot:dropdown>
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="inventory" href="#" label="Inventory Management">
            Inventory Management
        </tedi:sidenav.item>
        <tedi:sidenav.item icon="payments" route="#" label="Billing & Finance">
            Billing & Finance
        </tedi:sidenav.item>
    </tedi:sidenav>
</div>
