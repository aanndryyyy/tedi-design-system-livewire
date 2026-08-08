@storybook([
    'name' => 'Footer Bottom',
    'order' => 3,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

<tedi:footer>
    <tedi:footer.body>
        <tedi:footer.section heading="Heading" icon="account_circle">
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.section>
        <tedi:footer.section heading="Heading" icon="account_circle">
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.section>
        <tedi:footer.section heading="Heading" icon="account_circle">
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.section>
    </tedi:footer.body>

    <x-slot:bottom>
        <tedi:footer.bottom>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.bottom>
    </x-slot:bottom>
</tedi:footer>
