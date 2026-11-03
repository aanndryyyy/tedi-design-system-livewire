{{--
    Angular's `icon` / `iconPosition` story controls project a tedi-icon into
    the button; Blade cannot introspect a slot (CONVENTIONS.md §5), so they map
    onto `icon-start` / `icon-end` / `icon-only`.
--}}
@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.75.90?node-id=4515-65391&m=dev',
    'args' => [
        'ngContent' => 'Jäta oma tagasiside',
        'icon' => '',
        'iconPosition' => 'start',
        'variant' => 'primary',
        'size' => 'default',
        'axis' => 'horizontal',
        'position' => 'absolute',
        'zIndex' => '',
    ],
    'argTypes' => [
        'ngContent' => [
            'name' => 'ng-content',
            'description' => 'Button label.',
            'control' => 'text',
        ],
        'icon' => [
            'description' => 'Story control: projects a tedi-icon into the button.',
            'control' => 'text',
            'table' => ['category' => 'story controls'],
        ],
        'iconPosition' => [
            'description' => 'Position of the projected icon.',
            'control' => 'radio',
            'options' => ['start', 'end', 'only'],
            'table' => ['category' => 'story controls'],
        ],
        'variant' => [
            'control' => 'radio',
            'options' => ['primary', 'secondary'],
            'description' => "The button's color variant.",
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'primary'],
                'type' => ['summary' => 'FloatingButtonVariant'],
            ],
        ],
        'size' => [
            'control' => 'radio',
            'options' => ['default', 'large'],
            'description' => "The button's size.",
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'default'],
                'type' => ['summary' => 'FloatingButtonSize'],
            ],
        ],
        'axis' => [
            'control' => 'radio',
            'options' => ['horizontal', 'vertical'],
            'description' => '`vertical` rotates the button 90° counter-clockwise, with rounded left corners and square right corners. Intended for desktop side placement; on mobile prefer a horizontal button near the bottom, or an icon-only one.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'horizontal'],
                'type' => ['summary' => 'FloatingButtonAxis'],
            ],
        ],
        'position' => [
            'control' => 'radio',
            'options' => ['fixed', 'absolute', 'sticky', 'relative', 'static'],
            'description' => 'CSS positioning mode. Defaults to `fixed`; use `static` to keep the button in normal document flow.',
            'table' => [
                'category' => 'inputs',
                'defaultValue' => ['summary' => 'fixed'],
                'type' => ['summary' => 'FloatingButtonPosition'],
            ],
        ],
        'zIndex' => [
            'control' => 'number',
            'description' => "Overrides the button's CSS `z-index`. Defaults to `--z-index-feedback` when omitted.",
            'table' => [
                'category' => 'inputs',
                'type' => ['summary' => 'number'],
            ],
        ],
    ],
])

@php
    $iconName = $icon ?: null;
    $only = $iconName && $iconPosition === 'only';
@endphp

{{-- transform makes this div the containing block, so a fixed- or absolutely
     positioned button stays inside the preview instead of floating over
     Storybook. The margin leaves room above and below: a vertical button is
     rotated after layout, so half its length sits outside the edge it pins to. --}}
<div style="position: relative; transform: translateZ(0); min-height: 16rem; margin: 6rem 0; display: grid; place-items: center; padding: 1rem;">
    <tedi:floating-button
        :variant="$variant"
        :size="$size"
        :axis="$axis"
        :position="$position"
        :z-index="$zIndex !== '' && $zIndex !== null ? (int) $zIndex : null"
        :icon-start="$iconName && $iconPosition !== 'end' ? $iconName : null"
        :icon-end="$iconName && $iconPosition === 'end' ? $iconName : null"
        :icon-only="$only"
        :aria-label="$only ? $ngContent : null"
    >@unless ($only){{ $ngContent }}@endunless</tedi:floating-button>
</div>
