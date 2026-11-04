<div class="gx-sec">
    <h2>Text Field</h2>
    <p>Port of <code>form/text-field</code>. The native <code>&lt;input tedi-text-field&gt;</code>. Since Angular 8.0 it paints its own field surface; a <code>&lt;tedi:form-field&gt;</code> that renders a box (an icon, or the default clear button) paints it instead.</p>

    <div class="gx-case">
        <div class="gx-case__label">standalone: default | small | large | invalid | disabled | readonly</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:text-field id="tf-standalone" placeholder="Standalone" />
            <tedi:text-field id="tf-standalone-small" size="small" placeholder="Small" />
            <tedi:text-field id="tf-standalone-large" size="large" placeholder="Large" />
            <tedi:text-field id="tf-standalone-invalid" :invalid="true" value="Vigane" />
            <tedi:text-field id="tf-standalone-disabled" :disabled="true" value="Disabled" />
            <tedi:text-field id="tf-standalone-readonly" readonly value="Read-only" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">arrowsHidden: true (default) | false</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <x-slot:label>
                    <tedi:form.label for="tf-arrows-hidden">Arrows hidden</tedi:form.label>
                </x-slot:label>
                <tedi:text-field id="tf-arrows-hidden" type="number" value="42" />
            </tedi:form-field>

            <tedi:form-field>
                <x-slot:label>
                    <tedi:form.label for="tf-arrows-shown">Arrows shown</tedi:form.label>
                </x-slot:label>
                <tedi:text-field id="tf-arrows-shown" type="number" value="42" :arrows-hidden="false" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">in a form-field: default | invalid | valid (bare, clearable=false) | disabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <tedi:text-field id="tf-default" placeholder="Placeholder" />
            </tedi:form-field>

            <tedi:form-field :invalid="true">
                <tedi:text-field id="tf-invalid" value="Vigane" />
                <x-slot:feedback>
                    <tedi:feedback-text type="error" text="Tagasiside tekst" />
                </x-slot:feedback>
            </tedi:form-field>

            <tedi:form-field :valid="true" :clearable="false">
                <tedi:text-field id="tf-valid" value="Korrektne" />
                <x-slot:feedback>
                    <tedi:feedback-text type="valid" text="Tagasiside tekst" />
                </x-slot:feedback>
            </tedi:form-field>

            <tedi:form-field>
                <tedi:text-field id="tf-disabled" :disabled="true" value="Text value" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">form-field size: default | small | large, with and without icon</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['default', 'small', 'large'] as $size)
                <tedi:form-field :size="$size">
                    <tedi:text-field :id="'tf-size-'.$size" :placeholder="$size" />
                </tedi:form-field>
                <tedi:form-field :size="$size" icon="person">
                    <tedi:text-field :id="'tf-size-icon-'.$size" :placeholder="$size.' + icon'" />
                </tedi:form-field>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model binding</div>
        <div class="gx-case__demo">
            <tedi:form-field>
                <tedi:text-field id="tf-wire" wire:model="query" />
            </tedi:form-field>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Textarea</h2>
    <p>Port of <code>form/textarea</code>. The native <code>&lt;textarea tedi-textarea&gt;</code>; height, min-height and max-height are computed inline styles. Since Angular 9.0 a textarea takes the form-field's clear button.</p>

    <div class="gx-case">
        <div class="gx-case__label">standalone | small | resizable: false</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:textarea id="ta-standalone" placeholder="Standalone" />
            <tedi:textarea id="ta-standalone-small" size="small" placeholder="Small" />
            <tedi:textarea id="ta-not-resizable" :resizable="false" placeholder="Not resizable" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">height: minRows 3 (default) | custom 4rem | numeric 200 (→ 200px) | minRows 1 + rows 1</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <tedi:textarea id="ta-height-default" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-height-custom" height="4rem" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-height-numeric" :height="200" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-height-rows" :min-rows="1" rows="1" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">autoGrow (no row limit) | minRows/maxRows bounds | maxRows + maxHeight (min(…)) | maxHeight alone</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <tedi:textarea id="ta-auto-grow" :auto-grow="true" placeholder="3 rows, grows without limit" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-auto-grow-rows" :auto-grow="true" :min-rows="5" :max-rows="8" placeholder="5 → 8 rows" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-auto-grow-max" :auto-grow="true" :max-rows="12" max-height="200px" placeholder="Capped at 200px" />
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-max-height" max-height="12rem" placeholder="maxHeight without autoGrow" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: invalid | disabled | small form-field | character limit | icon + clear button</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :invalid="true">
                <tedi:textarea id="ta-invalid" value="Vigane sisu" />
                <x-slot:feedback>
                    <tedi:feedback-text type="error" text="Tagasiside tekst" />
                </x-slot:feedback>
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-disabled" :disabled="true" value="Disabled" />
            </tedi:form-field>

            <tedi:form-field size="small">
                <tedi:textarea id="ta-small" placeholder="Small" />
            </tedi:form-field>

            <tedi:form-field :character-limit="400">
                <tedi:textarea id="ta-char-limit" value="Kaksteist tk" />
            </tedi:form-field>

            <tedi:form-field icon="edit">
                <tedi:textarea id="ta-icon" value="Sisu" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">content: slot fallback | value wins | wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <tedi:textarea id="ta-slot">Sisu slotist</tedi:textarea>
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-value" value="Sisu value-propist">Ignoreeritud</tedi:textarea>
            </tedi:form-field>

            <tedi:form-field>
                <tedi:textarea id="ta-wire" wire:model="bio" />
            </tedi:form-field>
        </div>
    </div>
</div>
