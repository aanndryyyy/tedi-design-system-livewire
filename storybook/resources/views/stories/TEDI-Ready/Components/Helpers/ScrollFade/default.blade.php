@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'subset',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=10758-111142&m=dev',
    'args' => [
        'scrollBar' => 'custom',
        'fadeSize' => 20,
        'fadePosition' => 'both',
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'scrollBar' => [
            'control' => 'radio',
            'options' => ['default', 'custom'],
            'description' => 'Scrollbar style.',
        ],
        'fadeSize' => [
            'control' => 'radio',
            'options' => [0, 10, 20],
            'description' => 'Size of the fade, in percent.',
        ],
        'fadePosition' => [
            'control' => 'radio',
            'options' => ['top', 'bottom', 'both'],
            'description' => 'Which edges show the fade.',
        ],
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Accessible label for the scrollable region. Falls back to a translated default.',
        ],
    ],
])

{{--
    Angular toggles the fade classes from a ResizeObserver + scroll listener
    (measured at runtime). This port ships the same markup with minimal inline
    Alpine tracking scroll position (CONTRACT.md §5, status: subset).
--}}
<div style="max-width:200px;max-height:200px">
    <tedi:scroll-fade :scroll-bar="$scrollBar" :fade-size="$fadeSize" :fade-position="$fadePosition" :aria-label="$ariaLabel ?: null">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris Lorem ipsum dolor sit amet,
        consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
    </tedi:scroll-fade>
</div>
