{{--
    Angular sets `globals.backgrounds = 'brand'` per story. Blast's @storybook
    directive has no `parameters`/`globals` passthrough (CONTRACT.md §5), so the
    brand backdrop is reproduced with a wrapper div.
--}}
@storybook([
    'name' => 'Icon Only Inverted',
    'order' => 7,
    'status' => 'stable',
])

<div style="padding: 1.5rem; background: var(--general-icon-background-brand-primary);">
    <tedi:collapse :inverted="true" :hide-collapse-text="true" open-text="Open" close-text="Close">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora rerum
        perspiciatis consectetur blanditiis maxime, optio minus amet similique!
        Et, saepe placeat. Omnis obcaecati corrupti repellat enim asperiores sunt
        quam laudantium voluptate optio deserunt distinctio harum dolores, iure
        unde nemo reprehenderit!
    </tedi:collapse>
</div>
