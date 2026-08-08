<div class="gx-sec">
    <h2>Checkbox Group</h2>
    <p>Port of <code>form/checkbox-group</code>. A layout + labelling wrapper for <code>&lt;tedi:checkbox&gt;</code> children; <code>disabled</code> propagates to them via <code>&#64;aware</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">direction: horizontal (default) | vertical</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:checkbox-group label="Horisontaalne">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="b" />
                    Teine
                </label>
            </tedi:checkbox-group>

            <tedi:checkbox-group label="Vertikaalne" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="b" />
                    Teine
                </label>
            </tedi:checkbox-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">without label | without subtexts (the __subtexts div stays, hidden by :empty)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:checkbox-group>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" />
                    Sildita rühm
                </label>
            </tedi:checkbox-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with subtexts slot: hint | error</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:checkbox-group label="Vihjega" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" />
                    Esimene
                </label>
                <x-slot:subtexts>
                    <tedi:feedback-text text="Vihjetekst." />
                </x-slot:subtexts>
            </tedi:checkbox-group>

            <tedi:checkbox-group label="Veaga" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" :invalid="true" />
                    Esimene
                </label>
                <x-slot:subtexts>
                    <tedi:feedback-text type="error" text="Vali vähemalt üks." />
                </x-slot:subtexts>
            </tedi:checkbox-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">managed (role/aria) × disabled — disabled propagates to children via &#64;aware</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            {{-- unmanaged + disabled: no group ARIA, children still disabled --}}
            <tedi:checkbox-group label="Haldamata, keelatud" :disabled="true">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" />
                    Keelatud laps
                </label>
            </tedi:checkbox-group>

            {{-- managed + label: role=group + aria-labelledby --}}
            <tedi:checkbox-group label="Hallatud" managed :values="['a']" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="b" />
                    Teine
                </label>
            </tedi:checkbox-group>

            {{-- managed, no label: aria-label --}}
            <tedi:checkbox-group managed aria-label="Hallatud rühm ilma sildita">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" />
                    Esimene
                </label>
            </tedi:checkbox-group>

            {{-- managed + disabled: aria-disabled --}}
            <tedi:checkbox-group label="Hallatud, keelatud" managed :disabled="true">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox value="a" />
                    Esimene
                </label>
            </tedi:checkbox-group>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Radio Group</h2>
    <p>Port of <code>form/radio-group</code>. Same shape as checkbox-group, and additionally propagates a shared <code>name</code> to its <code>&lt;tedi:radio&gt;</code> children via <code>&#64;aware</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">direction: horizontal (default) | vertical</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-group label="Horisontaalne" name="rg-horizontal">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="b" />
                    Teine
                </label>
            </tedi:radio-group>

            <tedi:radio-group label="Vertikaalne" name="rg-vertical" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="b" />
                    Teine
                </label>
            </tedi:radio-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">without label | without subtexts</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-group name="rg-bare">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" />
                    Sildita rühm
                </label>
            </tedi:radio-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with subtexts slot: hint | error</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-group label="Vihjega" name="rg-hint" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" />
                    Esimene
                </label>
                <x-slot:subtexts>
                    <tedi:feedback-text text="Vihjetekst." />
                </x-slot:subtexts>
            </tedi:radio-group>

            <tedi:radio-group label="Veaga" name="rg-error" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" :invalid="true" />
                    Esimene
                </label>
                <x-slot:subtexts>
                    <tedi:feedback-text type="error" text="Vali üks variant." />
                </x-slot:subtexts>
            </tedi:radio-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">managed (role/aria) × disabled — name and disabled propagate via &#64;aware</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-group label="Haldamata, keelatud" name="rg-off" :disabled="true">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" />
                    Keelatud laps
                </label>
            </tedi:radio-group>

            <tedi:radio-group label="Hallatud" name="rg-managed" managed value="a" direction="vertical">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" :checked="true" />
                    Esimene
                </label>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="b" />
                    Teine
                </label>
            </tedi:radio-group>

            <tedi:radio-group managed name="rg-aria" aria-label="Hallatud rühm ilma sildita">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" />
                    Esimene
                </label>
            </tedi:radio-group>

            <tedi:radio-group label="Hallatud, keelatud" name="rg-managed-off" managed :disabled="true">
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio value="a" />
                    Esimene
                </label>
            </tedi:radio-group>
        </div>
    </div>
</div>
