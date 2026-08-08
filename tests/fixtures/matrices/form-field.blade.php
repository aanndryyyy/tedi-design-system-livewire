<div class="gx-sec">
    <h2>Label</h2>
    <p>Port of <code>form/label</code>. Kept at <code>form/label.blade.php</code> (addressed as <code>&lt;tedi:form.label&gt;</code>) rather than flattened, since <code>label</code> is the most generic name in the library.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:form.label for="label-size-default">Nimi</tedi:form.label>
            <tedi:form.label for="label-size-small" size="small">Nimi</tedi:form.label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">color: secondary | primary</div>
        <div class="gx-case__demo gx-case__demo--row">
            <tedi:form.label color="secondary">Kohustuslik silt</tedi:form.label>
            <tedi:form.label color="primary">Esile tõstetud silt</tedi:form.label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">required</div>
        <div class="gx-case__demo">
            <tedi:form.label required>Isikukood</tedi:form.label>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Label row</h2>
    <p>Port of <code>form/label-row</code>. A thin flex wrapper for a label plus trailing content (e.g. an info icon).</p>

    <div class="gx-case">
        <div class="gx-case__label">label + trailing content</div>
        <div class="gx-case__demo">
            <tedi:label-row>
                <tedi:form.label for="label-row-demo">Konto number</tedi:form.label>
                <tedi:icon name="info" :size="16" color="secondary" />
            </tedi:label-row>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Feedback text</h2>
    <p>Port of <code>form/feedback-text</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">type: hint | valid | error</div>
        <div class="gx-case__demo">
            <tedi:feedback-text text="Sisesta oma täisnimi." type="hint" />
            <br>
            <tedi:feedback-text text="Väli on korrektselt täidetud." type="valid" />
            <br>
            <tedi:feedback-text text="See väli on kohustuslik." type="error" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">position: left | right</div>
        <div class="gx-case__demo" style="display:flex;justify-content:space-between">
            <tedi:feedback-text text="Vasakul" position="left" />
            <tedi:feedback-text text="Paremal" position="right" />
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Form field</h2>
    <p>Port of <code>form/form-field</code>. Wraps a native control. DOM-introspection inputs from Angular (textarea detection, live control value, validation) become explicit props here — see the component's doc comment.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small | large</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:form-field size="default"><input tedi-text-field placeholder="Default" /></tedi:form-field>
            <tedi:form-field size="small"><input tedi-text-field placeholder="Small" /></tedi:form-field>
            <tedi:form-field size="large"><input tedi-text-field placeholder="Large" /></tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon-start (icon prop) | clearable (with value) | disabled</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:form-field icon="search"><input tedi-text-field placeholder="Otsi" /></tedi:form-field>
            <tedi:form-field clearable value="Jäätmekäitlus OÜ"><input tedi-text-field value="Jäätmekäitlus OÜ" /></tedi:form-field>
            <tedi:form-field :disabled="true"><input tedi-text-field placeholder="Keelatud" disabled /></tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">valid | invalid (with feedback slot)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:form-field :valid="true">
                <input tedi-text-field value="ok@example.com" />
                <x-slot:feedback><tedi:feedback-text text="Aadress on kehtiv." type="valid" /></x-slot:feedback>
            </tedi:form-field>
            <tedi:form-field :invalid="true">
                <input tedi-text-field value="vale" />
                <x-slot:feedback><tedi:feedback-text text="See väli on kohustuslik." type="error" /></x-slot:feedback>
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">character limit (with and without exceeding it)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:form-field :character-limit="40" :character-count="12">
                <input tedi-text-field value="Lühike kommentaar" />
            </tedi:form-field>
            <tedi:form-field :character-limit="10" :character-count="18">
                <input tedi-text-field value="Liiga pikk kommentaar" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">textarea (icon/clearable suppressed, per Angular)</div>
        <div class="gx-case__demo" style="max-width:20rem">
            <tedi:form-field textarea icon="search" clearable value="x">
                <textarea class="tedi-textarea" placeholder="Kirjeldus"></textarea>
            </tedi:form-field>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Input group</h2>
    <p>Port of <code>form/input-group</code>. Prefix/suffix directives become named slots the component wraps itself. <code>disabled</code>/<code>invalid</code> flow into the nested <code>&lt;tedi:form-field&gt;</code> via <code>@@aware</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">prefix (text) | suffix (text)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:input-group>
                <x-slot:label><tedi:form.label for="ig-prefix">Aadress</tedi:form.label></x-slot:label>
                <x-slot:prefix>Tänav</x-slot:prefix>
                <tedi:form-field><input tedi-text-field id="ig-prefix" /></tedi:form-field>
            </tedi:input-group>

            <tedi:input-group>
                <x-slot:label><tedi:form.label for="ig-suffix">Hind</tedi:form.label></x-slot:label>
                <tedi:form-field><input tedi-text-field id="ig-suffix" /></tedi:form-field>
                <x-slot:suffix>EUR</x-slot:suffix>
            </tedi:input-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">disabled | invalid (with feedback slot)</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:input-group :disabled="true">
                <x-slot:label><tedi:form.label for="ig-disabled">Kupong</tedi:form.label></x-slot:label>
                <x-slot:prefix>%</x-slot:prefix>
                <tedi:form-field><input tedi-text-field id="ig-disabled" disabled /></tedi:form-field>
            </tedi:input-group>

            <tedi:input-group :invalid="true">
                <x-slot:label><tedi:form.label for="ig-invalid">Summa</tedi:form.label></x-slot:label>
                <tedi:form-field><input tedi-text-field id="ig-invalid" /></tedi:form-field>
                <x-slot:suffix>EUR</x-slot:suffix>
                <x-slot:feedback><tedi:feedback-text text="Vali makset kandev konto" type="error" /></x-slot:feedback>
            </tedi:input-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">addons: false (detached suffix action)</div>
        <div class="gx-case__demo" style="max-width:20rem">
            <tedi:input-group :addons="false">
                <x-slot:label><tedi:form.label for="ig-no-addons">Sooduskood</tedi:form.label></x-slot:label>
                <tedi:form-field><input tedi-text-field id="ig-no-addons" placeholder="Sisesta sooduskood" /></tedi:form-field>
                <x-slot:suffix><tedi:button size="small">Rakenda</tedi:button></x-slot:suffix>
            </tedi:input-group>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Select — native subset (full combobox deferred)</h2>
    <p>Port of <code>form/select</code>. Angular's custom CDK-overlay combobox is <strong>not</strong> ported this phase (CONVENTIONS §7.4 — overlay positioning is out of scope); this renders a genuine native <code>&lt;select&gt;</code> so <code>wire:model</code> binds directly. See the component's doc comment for the full per-prop rationale.</p>

    <div class="gx-case">
        <div class="gx-case__label">flat options map | object options (bindLabel/bindValue) | placeholder</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:select input-id="select-flat" label="Riik" :options="['ee' => 'Eesti', 'lv' => 'Läti', 'lt' => 'Leedu']" placeholder="Vali riik" />

            <tedi:select
                input-id="select-objects"
                label="Konto"
                :options="[
                    ['value' => 'checking', 'label' => 'Arvelduskonto'],
                    ['value' => 'savings', 'label' => 'Kogumiskonto'],
                    ['value' => 'investment', 'label' => 'Investeerimiskonto', 'disabled' => true],
                ]"
                placeholder="Vali konto"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default | small</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:select input-id="select-size-default" :options="['a' => 'Valik A', 'b' => 'Valik B']" />
            <tedi:select input-id="select-size-small" size="small" :options="['a' => 'Valik A', 'b' => 'Valik B']" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">state: valid | error | disabled</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:select input-id="select-valid" state="valid" :options="['a' => 'Valik A']" />
            <tedi:select input-id="select-error" state="error" :options="['a' => 'Valik A']" />
            <tedi:select input-id="select-disabled" :disabled="true" :options="['a' => 'Valik A']" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">multiple (native multiselect)</div>
        <div class="gx-case__demo" style="max-width:20rem">
            <tedi:select
                input-id="select-multi"
                label="Meeskonnad"
                :allow-multiple="true"
                :options="['design' => 'Disain', 'eng' => 'Tehnika', 'sales' => 'Müük']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with feedback text | wire:model binding</div>
        <div class="gx-case__demo" style="display:flex;flex-direction:column;gap:.5rem;max-width:20rem">
            <tedi:select
                input-id="select-feedback"
                label="Konto"
                :options="['checking' => 'Arvelduskonto']"
                :feedback-text="['text' => 'Vali makset kandev konto', 'type' => 'error']"
            />
            <tedi:select input-id="select-wire" label="Riik" wire:model="country" :options="['ee' => 'Eesti', 'lv' => 'Läti']" placeholder="Vali riik" />
        </div>
    </div>
</div>
