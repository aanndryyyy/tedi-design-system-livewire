<?php

/**
 * Blast configuration for the TEDI storybook host.
 *
 * Only the keys that differ from Blast's own defaults are listed — the package
 * merges this file over `vendor/area17/blast/config/blast.php`, so anything
 * omitted here keeps its default value.
 */
return [
    /*
     * The CSS and JS the stories render against. Both are published out of the
     * package's dist/ directory by `php artisan vendor:publish --tag=tedi-assets`
     * (start.sh does this on every run), so they always match the local build.
     */
    'assets' => [
        'css' => [
            '/vendor/tedi/tedi.css',
        ],
        'js' => [
            // tedi.js registers its Alpine components on `alpine:init`, so it has
            // to be parsed before the script that boots Alpine. Alpine itself
            // comes from Livewire, emitted by @livewireScripts at the end of the
            // published resources/views/vendor/blast/storybook.blade.php — its
            // asset URL is generated per app, so it can't be hardcoded here.
            '/vendor/tedi/tedi.js',
        ],
    ],

    /*
     * Blast's auto-documentation and viewport features both read a Tailwind
     * config. TEDI ships its own CSS and this package deliberately does not
     * depend on Tailwind, so both are switched off.
     */
    'auto_documentation' => [],
    'tailwind_config_path' => false,
    'storybook_viewports' => false,

    /*
     * Mirrors the sidebar order of the Angular Storybook
     * (TEDI-Design-System/angular). Angular sets no `storySort`, so its sidebar
     * order is the glob order of `.storybook/main.ts` — docs, then `tedi/**`
     * (TEDI-Ready), then `community/**` — with each group ordered by the source
     * path, not by the `title` string. This list is that order, transcribed.
     *
     * Without it the sidebar falls back to the generated index, which is
     * alphabetical by story-JSON path: that happens to match Angular inside
     * every group, but puts Community *before* TEDI-Ready, and would put
     * `Button` before `ButtonGroup` (Angular has it the other way) once those
     * components are ported.
     *
     * Entries for components that aren't ported yet are harmless — Storybook
     * ignores names it doesn't find, so the list can stay complete while the
     * port catches up.
     *
     * This does NOT affect the stories inside a component: Storybook's
     * `storySort` short-circuits with `0` when two stories share a title and
     * `includeNames` is unset, so the per-story `order` in the @storybook
     * directives still decides that sequence. Don't set `includeNames`.
     *
     * Format: a flat list where a nested array applies to the entry before it —
     * a name => children map makes Storybook throw `order.indexOf is not a
     * function`.
     *
     * Two upstream title typos are deliberately not reproduced: Angular's
     * `TEDI Ready/.../Collapse` (space) and `Tedi-Ready/.../VerticalSpacing`
     * (casing) each spawn a stray top-level group there. They're listed below
     * in the group they belong to.
     */
    'storybook_sort_order' => [
        'TEDI-Ready', [
            'Base', [
                'Colors',
                'Icon',
                'Typography', ['Text'],
            ],
            'Components', [
                'Buttons', [
                    'ButtonGroup',
                    'Button',
                    'CardButton',
                    'ClosingButton',
                    'CollapseButton',
                    'Collapse',
                    'InfoButton',
                ],
                'Filter',
                'Form', [
                    'Checkbox',
                    'DateField',
                    'DatePicker',
                    'FeedbackText',
                    'InputGroup',
                    'Label',
                    'NumberField',
                    'Radio',
                    'Search',
                    'Select',
                    'Slider',
                    'TextField',
                    'Textarea',
                    'TimeField',
                    'TimePicker',
                    'Toggle',
                ],
                'Helpers', [
                    'Attachment',
                    'Ellipsis',
                    'EmptyState',
                    'Grid', ['Col', 'Row'],
                    'ScrollFade',
                    'Separator',
                    'Timeline',
                    'VerticalSpacing', ['VerticalSpacingItem', 'VerticalSpacing'],
                ],
                'Loader', ['ProgressBar', 'Spinner'],
                'Navigation', [
                    'Breadcrumbs',
                    'HorizontalStepper',
                    'Link',
                    'Pagination',
                    'Tabs',
                ],
                'Notifications', ['Alert', 'Toast'],
                'Overlay', [
                    'DropdownItemValue',
                    'Dropdown',
                    'InfoTooltip',
                    'Modal',
                    'Popover',
                    'Tooltip',
                ],
                'Tags', ['StatusBadge', 'StatusIndicator', 'Tag'],
            ],
            'Content', [
                'Accordion',
                'Calendar',
                'Card',
                'Carousel',
                'List',
                'Table',
                'TextGroup',
            ],
            'Layout', ['Footer', 'Header', 'SideNav'],
        ],
        'Community', [
            'Buttons', ['Floating Button'],
            'Cards', ['Accordion', 'Card'],
            'Form', [
                'Checkbox',
                'FileDropzone',
                'FormField',
                'InputGroup',
                'TextField',
                'Radio',
                'Search',
                'Select', ['Multiselect', 'Single Select'],
                'TextArea',
            ],
            'Helpers', ['ProgressBar'],
            'Navigation', [
                'Breadcrumbs',
                'Pagination',
                'Table of Contents',
                'Tabs',
                'VerticalStepper',
            ],
            'Overlay', ['Dropdown Item', 'Dropdown', 'Modal'],
            'Table', ['TableStyles'],
            'Tags', ['StatusBadge', 'Tag'],
        ],
    ],

    'storybook_statuses' => [
        'stable' => [
            'background' => '#1bbb3f',
            'color' => '#ffffff',
            'description' => 'Ported from the Angular component with no divergences.',
        ],
        'subset' => [
            'background' => '#f59506',
            'color' => '#ffffff',
            'description' => 'Ported as a documented subset — see the divergences table in the README.',
        ],
    ],
];
