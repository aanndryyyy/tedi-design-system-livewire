{{--
    Angular's `heading: null` (headless) is `''` here — CONTRACT.md §3 forbids
    null args, and `heading=""` is the component's headless escape hatch.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.66.83?node-id=8469-72329&m=dev',
    'args' => [
        'heading' => 'Sisukord',
        'headingLevel' => 'h3',
        'variant' => 'default',
        'activeId' => 'section-3',
        'defaultOpen' => true,
        'numbered' => false,
        'sticky' => false,
        'separator' => false,
        'bordered' => false,
    ],
    'argTypes' => [
        'heading' => [
            'description' => 'Heading rendered above the list. Defaults to the localised title; pass `null` to render it headless.',
            'control' => 'text',
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'string | null'],
                'defaultValue' => ['summary' => 'table-of-contents.title'],
            ],
        ],
        'headingLevel' => [
            'description' => "Semantic level of the heading element. The visual style stays H4; match it to the page's heading outline.",
            'control' => 'select',
            'options' => ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'],
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'TableOfContentsHeadingLevel'],
                'defaultValue' => ['summary' => 'h3'],
            ],
        ],
        'variant' => [
            'description' => 'Visual variant of the container.',
            'control' => 'radio',
            'options' => ['default', 'transparent'],
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'TableOfContentsVariant'],
                'defaultValue' => ['summary' => 'default'],
            ],
        ],
        'activeId' => [
            'description' => 'Id of the currently active item. It gets the accent bar and active colour.',
            'control' => 'text',
            'table' => ['category' => 'Table of Contents', 'type' => ['summary' => 'string']],
        ],
        'defaultOpen' => [
            'description' => 'Whether nested items are expanded by default. When `false`, a branch reveals its sub-items only while it is on the active trail.',
            'control' => 'boolean',
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'numbered' => [
            'description' => 'Show auto-generated hierarchical numbers (`1.`, `2.`, `2.1`, …).',
            'control' => 'boolean',
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'bordered' => [
            'description' => 'Draw a divider under each item except the last (sub-items included) so the list reads as separated rows.',
            'control' => 'boolean',
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
        'sticky' => [
            'description' => 'Stick to the viewport while scrolling — on `tedi-table-of-contents` this sticks the card; on `tedi-table-of-contents-collapsible` it pins the bar to the bottom of the viewport. Set `false` to render inline.',
            'control' => 'boolean',
            'table' => [
                'category' => 'Table of Contents',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'true'],
            ],
        ],
        'separator' => [
            'description' => 'Render a separator below the item.',
            'control' => 'boolean',
            'table' => [
                'category' => 'Table of Contents Item',
                'type' => ['summary' => 'boolean'],
                'defaultValue' => ['summary' => 'false'],
            ],
        ],
    ],
])

@php
    $sections = ['Sissejuhatus', 'Taust', 'Meetodid', 'Tulemused', 'Arutelu', 'Kokkuvõte'];
@endphp

<div style="max-width: 390px; padding: 1.5rem;">
<tedi:table-of-contents
    :heading="$heading"
    :heading-level="$headingLevel"
    :variant="$variant"
    :active-id="$activeId"
    :default-open="(bool) $defaultOpen"
    :numbered="(bool) $numbered"
    :sticky="(bool) $sticky"
    :bordered="(bool) $bordered"
>
    @foreach ($sections as $index => $label)
        @php $id = 'section-'.($index + 1); @endphp
        <tedi:table-of-contents-item :item-id="$id" :separator="(bool) $separator">
            <tedi:link :href="'#'.$id" :underline="false">{{ $label }}</tedi:link>
            @if ($label === 'Meetodid')
                <x-slot:sub-items>
                    <tedi:table-of-contents-item :item-id="$id.'-1'" :separator="(bool) $separator">
                        <tedi:link :href="'#'.$id.'-1'" :underline="false">Andmete kogumine</tedi:link>
                    </tedi:table-of-contents-item>
                    <tedi:table-of-contents-item :item-id="$id.'-2'" :separator="(bool) $separator">
                        <tedi:link :href="'#'.$id.'-2'" :underline="false">Analüüs</tedi:link>
                    </tedi:table-of-contents-item>
                </x-slot:sub-items>
            @endif
        </tedi:table-of-contents-item>
    @endforeach
</tedi:table-of-contents>
</div>
