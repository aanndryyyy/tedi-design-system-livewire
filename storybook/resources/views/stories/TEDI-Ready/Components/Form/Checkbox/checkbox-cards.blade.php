@storybook([
    'name' => 'Checkbox Cards',
    'order' => 9,
    'status' => 'stable',
    'args' => [],
])

{{--
    Checkbox cards with primary and secondary variants. Primary uses a filled
    background when selected, secondary uses an outline border.

    <tedi:checkbox-card> / <tedi:checkbox-card-group> have no Angular story
    file of their own (grepped tedi/components/form/checkbox-card{,-group});
    they're only ever exercised from within checkbox.stories.ts, so they're
    ported here under the same title, matching that file's `title`.
--}}
<tedi:row :gap-y="3">
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Primary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox :checked="true" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="primary">
                <tedi:checkbox />
                Text
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
    <tedi:col class="flex flex-column gap-2">
        <tedi:text as="p">Secondary</tedi:text>
        <tedi:checkbox-card-group>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox :checked="true" />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Text
            </tedi:checkbox-card>
            <tedi:checkbox-card variant="secondary">
                <tedi:checkbox />
                Text
            </tedi:checkbox-card>
        </tedi:checkbox-card-group>
    </tedi:col>
</tedi:row>
