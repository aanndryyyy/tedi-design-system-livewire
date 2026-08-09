@storybook([
    'name' => 'Trigger',
    'order' => 4,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.56.78?m=dev&node-id=5797-117364',
    'args' => [],
    'argTypes' => [],
])

@php
    $triggerButton = 'tedi-button tedi-button--secondary tedi-button--default tedi-button--pl tedi-button--pr';
@endphp

<tedi:row :gap="3">
    <tedi:col>
        <tedi:popover container-id="popover-trigger-1">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" :class="$triggerButton">Button Trigger</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content>This popover is triggered by button.</tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        {{-- Angular stacks two directives on one host: `<button tedi-info-button
             tedi-popover-trigger>`. The Blade equivalent applies the trigger
             wiring to <tedi:info-button> through its attribute bag, exactly as
             popover-trigger.blade.php documents. --}}
        <tedi:popover container-id="popover-trigger-2">
            <x-slot:trigger>
                <tedi:info-button
                    tedi-popover-trigger
                    id="popover-trigger-2_trigger"
                    tabindex="0"
                    aria-haspopup="dialog"
                    aria-expanded="false"
                    x-ref="trigger"
                    x-on:click="toggle()"
                    x-bind:aria-expanded="open"
                    x-bind:aria-controls="open ? 'popover-trigger-2' : null"
                />
            </x-slot:trigger>

            <tedi:popover-content>This popover is triggered by info button.</tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        <tedi:popover container-id="popover-trigger-3">
            <x-slot:trigger>
                <tedi:popover-trigger :underline="true">Text Trigger</tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content>This popover is triggered by text. By default text has dashed underline.</tedi:popover-content>
        </tedi:popover>
    </tedi:col>

    <tedi:col>
        {{-- Blade-only column, with no counterpart in the Angular story. It
             pins the phrasing-content fix: the panel and arrow are <span>s, so
             a popover inline in running text stays inside <tedi-popover> and
             inside the Alpine scope. As <div>s the parser auto-closed this <p>
             and hoisted the panel out, leaving a popover that never opened —
             and no class assertion could see it. See CONVENTIONS.md §11. --}}
        <p>
            Jääkaru elab Arktikas, kus <tedi:popover container-id="popover-trigger-4"><x-slot:trigger><tedi:popover-trigger :underline="true">jääd jätkub</tedi:popover-trigger></x-slot:trigger><tedi:popover-content>Popover inline in running text — the paragraph must stay on one line and the panel must remain inside the component.</tedi:popover-content></tedi:popover> aasta läbi.
        </p>
    </tedi:col>
</tedi:row>
