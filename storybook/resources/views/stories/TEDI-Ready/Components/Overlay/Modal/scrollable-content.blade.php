@storybook([
    'name' => 'Scrollable Content',
    'order' => 7,
    'status' => 'subset',
    'args' => [
        'title' => 'Uus toiming',
    ],
    'argTypes' => [
        'title' => [
            'control' => 'text',
            'description' => 'Modal heading. Angular offers three legs here; only the first is ported. "Content scrollbar" is this story. "Content fade" wraps the body in tedi:scroll-fade, which is itself a documented static-markup subset. "Page scroll" is `scrollBehavior: \'page\'`, whose only rule is `.tedi-modal-dialog--scroll-page` — part of the unported ModalService branch.',
            'table' => ['category' => 'Story'],
        ],
    ],
])

@php
    $sections = [
        'Teenus' => [
            ['service', 'Teenus'],
            ['institution', 'Asutus'],
            ['persons', 'Isikud'],
            ['priority', 'Prioriteet'],
            ['description', 'Probleemi kirjeldus'],
            ['start-date', 'Alguskuupäev'],
            ['end-date', 'Lõppkuupäev'],
        ],
        'Kontaktisik' => [
            ['contact-first-name', 'Eesnimi'],
            ['contact-last-name', 'Perenimi'],
            ['contact-id', 'Isikukood'],
            ['contact-phone', 'Telefon'],
            ['contact-email', 'E-post'],
            ['contact-address', 'Aadress'],
        ],
        'Esindaja' => [
            ['rep-first-name', 'Eesnimi'],
            ['rep-last-name', 'Perenimi'],
            ['rep-id', 'Isikukood'],
            ['rep-phone', 'Telefon'],
            ['rep-email', 'E-post'],
            ['rep-address', 'Aadress'],
            ['rep-relation', 'Seos isikuga'],
        ],
    ];
@endphp

<tedi:modal :open="true" width="md">
    <tedi:modal-header>
        <h1>{{ $title }}</h1>
    </tedi:modal-header>
    <tedi:modal-content>
        @foreach ($sections as $heading => $fields)
            @if (! $loop->first)
                <hr style="border: none; border-top: 1px solid var(--modal-border-inner); margin: 0;" />
            @endif
            <h3 style="margin: 0;">{{ $heading }}</h3>
            @foreach ($fields as [$id, $label])
                <tedi:form-field>
                    <x-slot:label>
                        <tedi:form.label :for="$id">{{ $label }}</tedi:form.label>
                    </x-slot:label>

                    <tedi:text-field :id="$id" />
                </tedi:form-field>
            @endforeach
        @endforeach
    </tedi:modal-content>
    <tedi:modal-footer>
        <tedi:button variant="secondary" x-on:click="hide()">Katkesta</tedi:button>
        <tedi:button x-on:click="hide()">Lisa</tedi:button>
    </tedi:modal-footer>
</tedi:modal>
