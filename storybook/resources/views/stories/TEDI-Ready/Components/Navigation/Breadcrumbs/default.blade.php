{{--
    Angular collects crumbs through the *tediBreadcrumbItem structural
    directive; Blade cannot introspect its own slot, so every story passes the
    trail as the :items array (CONVENTIONS.md §5). The last entry is always the
    current page.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.65.81?node-id=3486-65554&m=dev',
    'args' => [
        'variant' => 'long',
        'maxItems' => '',
        'itemsBeforeCollapse' => 1,
        'itemsAfterCollapse' => 1,
        'separator' => '',
        'ariaLabel' => '',
        'showMoreLabel' => '',
    ],
    'argTypes' => [
        'variant' => [
            'description' => '`long` shows the full trail; `short` shows only the parent crumb as a back-link.',
            'control' => 'radio',
            'options' => ['long', 'short'],
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'BreadcrumbsVariant'],
                'defaultValue' => ['summary' => 'long'],
            ],
        ],
        'maxItems' => [
            'description' => 'Max crumbs before the middle collapses into an ellipsis dropdown. Long variant only.',
            'control' => 'number',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'number']],
        ],
        'itemsBeforeCollapse' => [
            'description' => 'Crumbs kept visible at the start of the trail when collapsed.',
            'control' => 'number',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'itemsAfterCollapse' => [
            'description' => 'Crumbs kept visible at the end of the trail when collapsed.',
            'control' => 'number',
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
                'defaultValue' => ['summary' => '1'],
            ],
        ],
        'separator' => [
            'description' => 'Separator between crumbs. Defaults to a chevron icon; a `separatorTemplate` slot overrides this.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'ariaLabel' => [
            'description' => 'Accessible label for the `nav` landmark. Falls back to the `breadcrumbs` translation.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
        'showMoreLabel' => [
            'description' => 'Accessible label for the ellipsis button. Falls back to the `breadcrumbs.show-more` translation.',
            'control' => 'text',
            'table' => ['category' => 'inputs', 'type' => ['summary' => 'string']],
        ],
    ],
])

<tedi:breadcrumbs
    :variant="$variant"
    :max-items="$maxItems ?: null"
    :items-before-collapse="$itemsBeforeCollapse"
    :items-after-collapse="$itemsAfterCollapse"
    :separator="$separator ?: null"
    :aria-label="$ariaLabel ?: null"
    :show-more-label="$showMoreLabel ?: null"
    :items="[
        ['label' => 'Töölaud', 'href' => '#'],
        ['label' => 'Taotlused', 'href' => '#'],
        ['label' => 'Taotlus nr 506'],
    ]"
/>
