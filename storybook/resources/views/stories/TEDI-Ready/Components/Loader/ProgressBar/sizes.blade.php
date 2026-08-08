{{-- `default` renders an 8px bar, `small` a 4px one. --}}
@storybook([
    'name' => 'Sizes',
    'order' => 2,
    'status' => 'stable',
])

<tedi:row :cols="4" align-items="center" :gap-x="4" :gap-y="3">
    <tedi:text as="p" modifiers="small">Default</tedi:text>
    <tedi:col :width="3">
        <tedi:progress-bar :value="20" aria-label="Edenemisriba pealkiri">
            <tedi:feedback-text text="Üleslaadimine" type="hint" />
        </tedi:progress-bar>
    </tedi:col>

    <tedi:text as="p" modifiers="small">Small</tedi:text>
    <tedi:col :width="3">
        <tedi:progress-bar :value="20" size="small" aria-label="Edenemisriba pealkiri">
            <tedi:feedback-text text="Üleslaadimine" type="hint" />
        </tedi:progress-bar>
    </tedi:col>
</tedi:row>
