@storybook([
    'name' => 'Collapsible',
    'order' => 11,
    'status' => 'stable',
    'args' => [
        'heading' => 'Sisukord',
        'activeId' => 'methods',
        'defaultOpen' => true,
        'numbered' => false,
        'bordered' => false,
        'sticky' => false,
        'hideOnScroll' => false,
        'ariaLabel' => '',
        'separator' => false,
    ],
    'argTypes' => [
        'heading' => [
            'description' => 'Heading rendered above the list. Defaults to the localised title; pass `null` to render it headless.',
            'control' => 'text',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'string | null'], 'defaultValue' => ['summary' => 'table-of-contents.title']],
        ],
        'activeId' => [
            'description' => 'Id of the currently active item. It gets the accent bar and active colour.',
            'control' => 'text',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'string']],
        ],
        'defaultOpen' => [
            'description' => 'Whether nested items are expanded by default. When `false`, a branch reveals its sub-items only while it is on the active trail.',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'numbered' => [
            'description' => 'Show auto-generated hierarchical numbers (`1.`, `2.`, `2.1`, …).',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'bordered' => [
            'description' => 'Draw a divider under each item except the last (sub-items included) so the list reads as separated rows.',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'sticky' => [
            'description' => 'Stick to the viewport while scrolling — on `tedi-table-of-contents` this sticks the card; on `tedi-table-of-contents-collapsible` it pins the bar to the bottom of the viewport. Set `false` to render inline.',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'true']],
        ],
        'hideOnScroll' => [
            'description' => '`tedi-table-of-contents-collapsible` only: hide the bar when the user scrolls down and reveal it when they scroll up. Watches window scroll by default; set `scrollContainer` to watch a scrollable region instead.',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
        'ariaLabel' => [
            'description' => 'Accessible name for the `nav` landmark. Overrides the default (the heading, or the localised title when headless).',
            'control' => 'text',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'string']],
        ],
        'separator' => [
            'description' => 'Render a separator below the item.',
            'control' => 'boolean',
            'table' => ['category' => 'Table of Contents Item', 'type' => ['summary' => 'boolean'], 'defaultValue' => ['summary' => 'false']],
        ],
    ],
])

<div>
    <tedi:show-at breakpoint="lg">
        <div style="background: var(--general-surface-primary); padding: 2rem;">
            <tedi:text as="h2" modifiers="h1">Tervisedeklaratsioon</tedi:text>
            <tedi:text as="p" color="secondary" style="margin: 0.5rem 0 0;">
                Tervisedeklaratsioon koosneb 22 kohustuslikust küsimusest. Alusta või
                jätka selle koostamisega allpool.
            </tedi:text>
            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 1.5rem; align-items: start; margin-top: 1.5rem;">
                <div style="min-height: 35rem; background: var(--general-surface-primary); border: 1px solid var(--general-border-primary); border-radius: var(--card-radius-rounded);"></div>
                <tedi:table-of-contents :heading="$heading"
                    :sticky="false"
                    :active-id="$activeId"
                    :default-open="(bool) $defaultOpen"
                    :numbered="(bool) $numbered"
                    :bordered="(bool) $bordered"
                    :aria-label="$ariaLabel ?: null">
                <tedi:table-of-contents-item item-id="intro" :separator="(bool) $separator">
                    <tedi:link href="#intro" :underline="false">Sissejuhatus</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="methods" :separator="(bool) $separator">
                    <tedi:link href="#methods" :underline="false">Meetodid</tedi:link>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="methods-1" :separator="(bool) $separator">
                            <tedi:link href="#methods-1" :underline="false">Andmete kogumine</tedi:link>
                        </tedi:table-of-contents-item>
                        <tedi:table-of-contents-item item-id="methods-2" :separator="(bool) $separator">
                            <tedi:link href="#methods-2" :underline="false">Analüüs</tedi:link>
                        </tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="results" :separator="(bool) $separator">
                    <tedi:link href="#results" :underline="false">Tulemused</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="discussion" :separator="(bool) $separator">
                    <tedi:link href="#discussion" :underline="false">Arutelu</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="conclusion" :separator="(bool) $separator">
                    <tedi:link href="#conclusion" :underline="false">Kokkuvõte</tedi:link>
                </tedi:table-of-contents-item>
                </tedi:table-of-contents>
            </div>
        </div>
    </tedi:show-at>

    <tedi:hide-at breakpoint="lg">
        <div style="display: flex; flex-direction: column; min-height: 100vh; background: var(--general-surface-tertiary); gap: 3px;">
            <div style="display: flex; flex-direction: column; flex: 1 1 auto; padding: var(--layout-page-spacing-top) var(--layout-page-spacing-x) 0 var(--layout-page-spacing-x);">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <tedi:link href="#" :underline="false" icon-start="arrow_back">Tervisetõendid ja -deklaratsioonid</tedi:link>
                    <div>
                        <tedi:text as="h2" modifiers="h1">Tervisedeklaratsioon</tedi:text>
                        <tedi:text as="p" color="secondary">
                            Tervisedeklaratsioon koosneb 22 kohustuslikust küsimusest. Alusta või
                            jätka selle koostamisega allpool.
                        </tedi:text>
                    </div>
                </div>
                <div style="flex: 1 1 auto; margin-top: 1rem; background: var(--general-surface-primary); border: 1px solid var(--general-border-primary); border-radius: var(--card-radius-rounded);"></div>
            </div>
            <tedi:table-of-contents-collapsible
                :heading="$heading"
                :active-id="$activeId"
                :default-open="(bool) $defaultOpen"
                :numbered="(bool) $numbered"
                :bordered="(bool) $bordered"
                :sticky="(bool) $sticky"
                :hide-on-scroll="(bool) $hideOnScroll"
                :aria-label="$ariaLabel ?: null"
            >
                <tedi:table-of-contents-item item-id="intro" :separator="(bool) $separator">
                    <tedi:link href="#intro" :underline="false">Sissejuhatus</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="methods" :separator="(bool) $separator">
                    <tedi:link href="#methods" :underline="false">Meetodid</tedi:link>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="methods-1" :separator="(bool) $separator">
                            <tedi:link href="#methods-1" :underline="false">Andmete kogumine</tedi:link>
                        </tedi:table-of-contents-item>
                        <tedi:table-of-contents-item item-id="methods-2" :separator="(bool) $separator">
                            <tedi:link href="#methods-2" :underline="false">Analüüs</tedi:link>
                        </tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="results" :separator="(bool) $separator">
                    <tedi:link href="#results" :underline="false">Tulemused</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="discussion" :separator="(bool) $separator">
                    <tedi:link href="#discussion" :underline="false">Arutelu</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="conclusion" :separator="(bool) $separator">
                    <tedi:link href="#conclusion" :underline="false">Kokkuvõte</tedi:link>
                </tedi:table-of-contents-item>
            </tedi:table-of-contents-collapsible>
        </div>
    </tedi:hide-at>
</div>
