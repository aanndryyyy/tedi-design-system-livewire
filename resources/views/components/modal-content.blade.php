{{--
    TEDI Modal content.
    Port of angular/tedi/components/overlay/modal/modal-content/modal-content.component.ts

    Class-only shell. Angular attaches CdkScrollable as a host directive; that
    exists so CDK's overlay can observe the scroll container and is not needed
    here — the actual scrolling comes from `.tedi-modal-content { overflow-y: auto }`
    in the vendored SCSS, which applies either way.
--}}
<tedi-modal-content {{ $attributes->class(['tedi-modal-content']) }}>
    {{ $slot }}
</tedi-modal-content>
