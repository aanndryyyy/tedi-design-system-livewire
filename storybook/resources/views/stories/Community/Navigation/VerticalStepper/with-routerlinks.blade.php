{{--
    Angular's WithRouterlinks story. `route` + RouterLink become a plain `href`;
    RouterLinkActive's auto-selection has no server-side equivalent, so the
    current step is marked with `:selected` (see with-routerlinks.md).
--}}
@storybook([
    'name' => 'With Routerlinks',
    'order' => 4,
    'status' => 'subset',
    'args' => [
        'ariaLabel' => '',
    ],
    'argTypes' => [
        'ariaLabel' => [
            'control' => 'text',
            'description' => 'Aria label for stepper',
        ],
    ],
])

<tedi:vertical-stepper :aria-label="$ariaLabel ?: null">
    <tedi:vertical-stepper-item title="Link 1" href="link1" :selected="true" />
    <tedi:vertical-stepper-item title="Link 2" href="link2" />
    <tedi:vertical-stepper-item title="Link 3" href="link3" />
</tedi:vertical-stepper>
