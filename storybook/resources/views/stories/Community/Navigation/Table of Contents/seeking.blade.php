{{--
    Angular's Seeking story: real headings to scroll through, so the
    IntersectionObserver in `Alpine.data('tediTableOfContents')` has something to
    track and clicking an item smooth-scrolls to it.
--}}
@storybook([
    'name' => 'Seeking',
    'order' => 2,
    'status' => 'subset',
    'args' => [
        'items' => ['Introduction', 'Getting Started', 'Components', 'API Reference'],
        'heading' => 'Table of Contents',
        'position' => 'sticky',
        'scrollOnClick' => true,
        'ariaLabel' => 'Table of contents',
        'modalBreakpoint' => 'mobile',
    ],
    'argTypes' => [
        'items' => ['control' => 'object', 'description' => 'Story-only: the item labels rendered into the list.'],
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
    $idFor = fn ($item) => \Illuminate\Support\Str::slug($item).'_seeking';
@endphp

<div style="display: flex;">
    <div style="margin-bottom: 1000px;">
        @foreach ($items as $item)
            <div style="margin-top: 100px;">
                <h2 id="{{ $idFor($item) }}">{{ $item }}</h2>
                <p style="max-width: 40rem;">{{ $lorem }}</p>
            </div>
        @endforeach
    </div>

    <div>
        <tedi:table-of-contents
            :heading="$heading"
            :position="$position"
            :scroll-on-click="(bool) $scrollOnClick"
            :aria-label="$ariaLabel"
            :modal-breakpoint="$modalBreakpoint"
        >
            @foreach ($items as $item)
                <tedi:table-of-contents-item :id-to="$idFor($item)">{{ $item }}</tedi:table-of-contents-item>
            @endforeach
        </tedi:table-of-contents>
    </div>
</div>
