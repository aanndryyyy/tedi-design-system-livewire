{{--
    TEDI Text Group Value.
    Port of angular/tedi/components/content/text-group/text-group-value.component.ts

    Same rationale as text-group-label.blade.php: no host bindings in
    Angular, styled purely by the `tedi-text-group-value` element selector in
    the vendored SCSS, so the tag name is emitted literally.
--}}
<tedi-text-group-value {{ $attributes }}>{{ $slot }}</tedi-text-group-value>
