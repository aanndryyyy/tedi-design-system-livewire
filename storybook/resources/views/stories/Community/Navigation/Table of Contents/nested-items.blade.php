{{--
    Angular's NestedItems story. Its `tedi-table-of-contents-nested-wrapper` is
    an Angular-only workaround (angular/angular#57345) and is not ported — the
    nested items go straight into the item's `sub-items` slot.

    `items` is a list of `label`/`subItems` records rather than a label-keyed
    map: Blast serialises story args into a JS object with unquoted keys, so a
    key like "Getting Started" would break the generated CSF module.
--}}
@storybook([
    'name' => 'Nested Items',
    'order' => 3,
    'status' => 'subset',
    'args' => [
        'items' => [
            ['label' => 'Introduction', 'subItems' => []],
            ['label' => 'Getting Started', 'subItems' => ['Installation', 'Quick Start']],
            ['label' => 'Components', 'subItems' => ['Buttons', 'Cards', 'Modals']],
            ['label' => 'API Reference', 'subItems' => []],
        ],
        'heading' => 'Table of Contents',
        'position' => 'sticky',
        'scrollOnClick' => true,
        'ariaLabel' => 'Table of contents',
        'modalBreakpoint' => 'mobile',
    ],
    'argTypes' => [
        'items' => ['control' => 'object', 'description' => 'Story-only: a list of items, each with a `label` and its `subItems` labels.'],
        'heading' => ['control' => 'text', 'description' => 'Heading of the table of contents'],
        'position' => [
            'control' => 'radio',
            'options' => ['default', 'fixed', 'sticky'],
            'description' => 'Position strategy of the table of contents',
        ],
        'scrollOnClick' => [
            'control' => 'boolean',
            'description' => 'Should child table of contents elements when clicked scroll',
        ],
        'ariaLabel' => ['control' => 'text', 'description' => 'ARIA label for the nav element'],
        'modalBreakpoint' => [
            'control' => 'radio',
            'options' => ['mobile', 'tablet', 'desktop', 'never'],
            'description' => 'Breakpoint from which on the table of contents will be shown in a modal',
        ],
    ],
])

@php
    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed euismod, nunc ut aliquam aliquam, '
        .'nunc nisl aliquet nunc, euismod aliquam nisl nunc euismod nunc.';

    $anchor = fn (string $label) => \Illuminate\Support\Str::slug($label).'_nested';
@endphp

<div style="display: flex;">
    <div style="margin-bottom: 1000px;">
        @foreach ($items as $item)
            <div style="margin-top: 200px;">
                <h2 id="{{ $anchor($item['label']) }}">{{ $item['label'] }}</h2>
                <p style="max-width: 40rem;">{{ $lorem }}</p>

                @foreach ($item['subItems'] as $subItem)
                    <div style="margin-top: 200px; margin-left: 2rem;">
                        <h4 id="{{ $anchor($subItem) }}">{{ $subItem }}</h4>
                        <p style="max-width: 40rem;">{{ $lorem }}</p>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <tedi:table-of-contents
        :heading="$heading"
        :position="$position"
        :scroll-on-click="(bool) $scrollOnClick"
        :aria-label="$ariaLabel"
        :modal-breakpoint="$modalBreakpoint"
    >
        @foreach ($items as $item)
            <tedi:table-of-contents-item :id-to="$anchor($item['label'])">
                <div>{{ $item['label'] }}</div>

                @if (count($item['subItems']))
                    <x-slot:sub-items>
                        @foreach ($item['subItems'] as $subItem)
                            <tedi:table-of-contents-item :id-to="$anchor($subItem)">{{ $subItem }}</tedi:table-of-contents-item>
                        @endforeach
                    </x-slot:sub-items>
                @endif
            </tedi:table-of-contents-item>
        @endforeach
    </tedi:table-of-contents>
</div>
