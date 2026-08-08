{{-- Every combination of `labelPosition` (`top` / `horizontal`) and `valuePosition` (`horizontal` / `bottom`). --}}
@storybook([
    'name' => 'Position',
    'order' => 3,
    'status' => 'stable',
])

<div style="display: grid; grid-template-columns: 1fr; gap: 1rem; align-items: start; width: 100%;">
    <div>
        <tedi:text as="p" modifiers="small">Top title</tedi:text>
        <tedi:text as="p" modifiers="small">Horizontal value</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom hint</tedi:text>
    </div>
    <tedi:progress-bar :value="20" label="Edenemisriba pealkiri" :required="true" label-position="top" value-position="horizontal">
        <tedi:feedback-text text="Üleslaadimine" type="hint" />
    </tedi:progress-bar>

    <div>
        <tedi:text as="p" modifiers="small">Top title</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom value</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom hint</tedi:text>
    </div>
    <tedi:progress-bar :value="20" label="Edenemisriba pealkiri" :required="true" label-position="top" value-position="bottom">
        <tedi:feedback-text text="Üleslaadimine" type="hint" />
    </tedi:progress-bar>

    <div>
        <tedi:text as="p" modifiers="small">Horizontal title</tedi:text>
        <tedi:text as="p" modifiers="small">Horizontal value</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom hint</tedi:text>
    </div>
    <tedi:progress-bar :value="20" label="Edenemisriba pealkiri" :required="true" label-position="horizontal" value-position="horizontal">
        <tedi:feedback-text text="Üleslaadimine" type="hint" />
    </tedi:progress-bar>

    <div>
        <tedi:text as="p" modifiers="small">Horizontal title</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom value</tedi:text>
        <tedi:text as="p" modifiers="small">Bottom hint</tedi:text>
    </div>
    <tedi:progress-bar :value="20" label="Edenemisriba pealkiri" :required="true" label-position="horizontal" value-position="bottom">
        <tedi:feedback-text text="Üleslaadimine" type="hint" />
    </tedi:progress-bar>
</div>
