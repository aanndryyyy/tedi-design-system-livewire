@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.12.16--work-in-progress-?node-id=6459-181755&m=dev',
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

    <x-slot:end>
        <tedi:footer.side placement="end" position="center">
            <picture>
                <source srcset="SF-horizontal.png" media="(max-width: 576px)">
                <img src="SF-vertical.png" alt="Logo">
            </picture>
        </tedi:footer.side>
    </x-slot:end>
</tedi:footer>
