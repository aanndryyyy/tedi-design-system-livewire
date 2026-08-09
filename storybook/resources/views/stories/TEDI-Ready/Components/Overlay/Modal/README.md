<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.23.38?node-id=4626-89579&m=dev" target="_blank">Figma ↗</a><br>
<a href="https://www.tedi.ee/1ee8444b7/p/31221b-modal" target="_blank">Zeroheight ↗</a>

---

## Service-based modal (ModalService)

**Not ported.** Angular opens modals programmatically via `ModalService.open()`, which uses
Angular CDK Dialog to spawn a component into an overlay and handles focus trapping, scroll
blocking, backdrop and keyboard events. There is no CDK here and no imperative
component-spawning equivalent, so the whole service branch is omitted — including
`ModalService`, `ModalRef`, `MODAL_DATA`, and the `ModalConfig` keys that only exist for it
(`scrollBehavior`, `fullscreen`, `maxWidth`, `closeOnEscape`, `ariaLabel`, `ariaLabelledBy`,
`data`), plus the `tedi-modal--service` / `tedi-modal-dialog*` class families.

## Template-based modal

The Angular component marks this branch `@deprecated` in favour of `ModalService.open()`, but
it is the only branch with markup, so it is what this package ports. Open state, backdrop
click, Escape, body scroll lock and focus restore come from the `tediModal` Alpine component.

```html
<tedi:modal :open="true" size="default" width="sm" position="center">
    <tedi:modal-header>
        <h1>Title</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        <!-- Content -->
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
        <tedi:button x-on:click="hide()">Continue</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
```

Two further divergences: Angular re-parents the modal host to `<body>` in `ngAfterViewInit`
and Blade cannot, so a modal written inside a `transform`ed ancestor is positioned relative to
that ancestor; and `cdkTrapFocus` is not ported, so focus moves into the dialog and back out
on close but does not cycle. `position="bottom"` is accepted but emits no class — TEDI ships
no `.tedi-modal--bottom` rule.
