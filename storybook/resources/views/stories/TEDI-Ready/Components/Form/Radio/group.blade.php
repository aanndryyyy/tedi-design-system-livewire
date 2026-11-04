@storybook([
    'name' => 'Group',
    'order' => 6,
    'status' => 'stable',
    'args' => [],
])

{{--
    Angular projects the trailing <tedi-feedback-text> into the group through
    <ng-content select="tedi-feedback-text" />; in Blade that is the named
    `subtexts` slot.
--}}
{{-- Upstream's row is `[xs]="{cols: 1}" [md]="{cols: 2}"`. Breakpoint props are
     not ported (CONVENTIONS.md §7), so the xs value is the base. --}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:radio-group label="Tooraine" direction="vertical">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-hint" />
            Kartul
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-hint" />
            Kapsas
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-hint" />
            Peet
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Tegemist on veidi veidra valikuga, kuid vähemalt ühe baaselemendi peab valima" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Tooraine">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-hint" />
            Kartul
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-hint" />
            Kapsas
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-hint" />
            Peet
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Eestis levinud köögiviljad" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Tooraine" direction="vertical">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-error" />
            Kartul
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-error" />
            Kapsas
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-error" />
            Peet
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Vähemalt 1 tooraine on kohustuslik valida" type="error" />
        </x-slot:subtexts>
    </tedi:radio-group>

    <tedi:radio-group label="Tooraine">
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-error" />
            Kartul
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-error" />
            Kapsas
        </tedi:form.label>
        <tedi:form.label color="primary" class="flex align-items-center gap-2">
            <tedi:radio name="group-h-error" />
            Peet
        </tedi:form.label>
        <x-slot:subtexts>
            <tedi:feedback-text text="Vähemalt 1 tooraine on kohustuslik valida" type="error" />
        </x-slot:subtexts>
    </tedi:radio-group>
</tedi:row>
