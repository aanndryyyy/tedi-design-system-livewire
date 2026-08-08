@storybook([
    'name' => 'Body',
    'order' => 4,
    'status' => 'stable',
])

<div style="display: flex; flex-direction: column;">
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <b>Native element</b>
        <b>Modifier</b>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <tedi:text as="p">Body regular</tedi:text>
        <tedi:text as="h1" modifiers="normal">Body regular</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <b>Body bold</b>
        <tedi:text as="p" modifiers="bold">Body bold</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <i>Body italic</i>
        <tedi:text as="p" modifiers="italic">Body italic</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <small>Small</small>
        <tedi:text as="span" modifiers="small">Small</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <small><b>Small bold</b></small>
        <tedi:text as="small" modifiers="bold">Small bold</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <small><i>Small italic</i></small>
        <tedi:text as="small" modifiers="italic">Small italic</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" class="border-bottom" style="padding: 14px 16px;">
        <small>Extra small</small>
        <tedi:text as="span" modifiers="extra-small">Extra small</tedi:text>
    </tedi:row>
    <tedi:row :cols="2" style="padding: 14px 16px;">
        <small><b>Extra small bold</b></small>
        <tedi:text as="span" :modifiers="['extra-small', 'bold']">Extra small bold</tedi:text>
    </tedi:row>
</div>
