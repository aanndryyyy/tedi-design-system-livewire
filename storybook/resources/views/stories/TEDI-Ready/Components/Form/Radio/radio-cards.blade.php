@storybook([
    'name' => 'Radio Cards',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

{{--
    Radio cards with primary and secondary variants side by side. Primary
    uses a filled background when selected, secondary uses an outline border.

    <tedi:radio-card> / <tedi:radio-card-group> have no Angular story file of
    their own (grepped tedi/components/form/radio-card{,-group}); they're
    only ever exercised from within radio.stories.ts, so they're ported here
    under the same title, matching that file's `title`.
--}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-primary" :checked="true" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-primary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="primary">
                <tedi:radio name="card-primary" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:radio-card-group>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-secondary" :checked="true" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-secondary" />
                Text
            </tedi:radio-card>
            <tedi:radio-card variant="secondary">
                <tedi:radio name="card-secondary" />
                Text
            </tedi:radio-card>
        </tedi:radio-card-group>
    </tedi:col>
</tedi:row>
