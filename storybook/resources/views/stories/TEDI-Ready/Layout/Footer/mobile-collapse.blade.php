@storybook([
    'name' => 'Mobile Collapse',
    'order' => 4,
    'status' => 'stable',
    'layout' => 'fullscreen',
    'args' => [],
])

{{--
    Angular's `MobileCollapse` story only differs from `FooterBottom` by
    passing `[collapse]="true"` to each `tedi-footer-section`. Angular gates
    the actual collapse *behaviour* on `collapse() && mobileLayout()`
    (breakpoint-driven); since `mobileLayout` is not ported (see
    footer/section.blade.php's docblock), `collapse` alone drives the toggle
    here, so the sections are collapsible at any viewport rather than only
    below the `sm` breakpoint. Documented, not faked.
--}}
<tedi:footer>
    <tedi:footer.body>
        <tedi:footer.section heading="Heading" icon="account_circle" collapse>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.section>
        <tedi:footer.section heading="Heading" icon="account_circle" collapse>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
            <tedi:link variant="inverted" href="#">Link</tedi:link>
        </tedi:footer.section>
        <tedi:footer.section heading="Heading" icon="account_circle" collapse>
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
