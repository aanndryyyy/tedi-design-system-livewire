@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.38?node-id=8826-63667&m=dev',
    'args' => [
        'items' => ['Introduction', 'Getting Started', 'Components', 'API Reference'],
        'heading' => 'Table of Contents',
        'position' => 'default',
        'scrollOnClick' => true,
        'ariaLabel' => 'Table of contents',
        'modalBreakpoint' => 'mobile',
    ],
    'argTypes' => [
        'items' => [
            'control' => 'object',
            'description' => 'Story-only: the item labels rendered into the list.',
        ],
        'heading' => [
            'control' => 'text',
            'description' => 'Heading of the table of contents',
        ],
        'position' => [
            'control' => 'radio',
            'options' => ['default', 'fixed', 'sticky'],
            'description' => 'Position strategy of the table of contents',
        ],
        'scrollOnClick' => [
            'control' => 'boolean',
            'description' => 'Should child table of contents elements when clicked scroll',
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'ARIA label for the nav element',
        ],
        'modalBreakpoint' => [
            'control' => 'radio',
            'options' => ['mobile', 'tablet', 'desktop', 'never'],
            'description' => 'Breakpoint from which on the table of contents will be shown in a modal',
        ],
    ],
])

<div>
    <tedi:table-of-contents
        :heading="$heading"
        :position="$position"
        :scroll-on-click="(bool) $scrollOnClick"
        :aria-label="$ariaLabel"
        :modal-breakpoint="$modalBreakpoint"
    >
        @foreach ($items as $item)
            <tedi:table-of-contents-item :id-to="\Illuminate\Support\Str::slug($item).'_default'">
                {{ $item }}
            </tedi:table-of-contents-item>
        @endforeach
    </tedi:table-of-contents>
</div>
