{{--
    TEDI Text Group Label.
    Port of angular/tedi/components/content/text-group/text-group-label.component.ts

    Angular's component has no host bindings; its `<ng-content />` template
    is styled purely via the vendored SCSS's `tedi-text-group-label` element
    selector, so the custom tag name is emitted literally here to keep the
    CSS applying without touching the vendored stylesheet.
--}}
<tedi-text-group-label {{ $attributes }}>{{ $slot }}</tedi-text-group-label>
