@storybook([
    'name' => 'Confirm before closing',
    'order' => 13,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Upstream sets `ModalRef.canClose` on a ModalService modal. The template
    branch has no ModalRef, so the guard is the cancelable `tedi-modal-close`
    event: while the form is dirty the listener cancels the close and opens the
    confirmation modal. "Discard" closes the edit modal with the unguarded
    `close()`, as upstream's resolved guard does.
--}}
<div x-data="{ dirty: false }">
    <tedi:button variant="secondary" x-on:click="$dispatch('open-guarded-modal')">Edit contact</tedi:button>

    <tedi:modal
        width="md"
        x-on:open-guarded-modal.window="dirty = false; show()"
        x-on:discard-contact.window="dirty = false; close()"
        x-on:tedi-modal-close="if (dirty) { $event.preventDefault(); $dispatch('open-discard-modal') }"
    >
        <tedi:modal-header>
            <h1>Edit contact</h1>
            <x-slot:description>
                <p tedi-modal-description>Edit a field, then close the modal.</p>
            </x-slot:description>
        </tedi:modal-header>
        <tedi:modal-content>
            <form id="guarded-form" x-on:submit.prevent="dirty = false; hide()">
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label for="guarded-name">Name</tedi:form.label>
                    </x-slot:label>

                    <tedi:text-field id="guarded-name" value="Mari Maasikas" x-on:input="dirty = true" />
                </tedi:form-field>
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label for="guarded-email">E-mail</tedi:form.label>
                    </x-slot:label>

                    <tedi:text-field id="guarded-email" type="email" value="mari@example.com" x-on:input="dirty = true" />
                </tedi:form-field>
            </form>
        </tedi:modal-content>
        <tedi:modal-footer>
            <tedi:button variant="secondary" x-on:click="hide()">Cancel</tedi:button>
            <tedi:button type="submit" form="guarded-form">Save</tedi:button>
        </tedi:modal-footer>
    </tedi:modal>

    <tedi:modal size="small" x-on:open-discard-modal.window="show()">
        <tedi:modal-header :show-close="false">
            <h2>Discard changes?</h2>
            <x-slot:description>
                <p tedi-modal-description>Your edits will be lost.</p>
            </x-slot:description>
        </tedi:modal-header>
        <tedi:modal-footer>
            <tedi:button variant="secondary" x-on:click="hide()">Keep editing</tedi:button>
            <tedi:button variant="danger" x-on:click="hide(); $dispatch('discard-contact')">Discard</tedi:button>
        </tedi:modal-footer>
    </tedi:modal>
</div>
