<div class="gx-sec">
    <h2>Text Field</h2>
    <p>Port of <code>form/text-field</code>. The native <code>&lt;input tedi-text-field&gt;</code> — wrap it in <code>&lt;tedi:form-field&gt;</code> for the box, label and feedback row.</p>

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
        <div class="gx-case__label">states: default | invalid (aria-invalid) | disabled</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field>
                <tedi:text-field id="tf-default" placeholder="Placeholder" />
            </tedi:form-field>

            <tedi:form-field :invalid="true">
                <tedi:text-field id="tf-invalid" :invalid="true" value="Vigane" />
                <x-slot:feedback>
                    <tedi:feedback-text type="error" text="Tagasiside tekst" />
                </x-slot:feedback>
            </tedi:form-field>

            <tedi:form-field :disabled="true">
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
    <p>Port of <code>form/textarea</code>. The native <code>&lt;textarea tedi-textarea&gt;</code>; height, min-height and max-height are computed inline styles.</p>

    <div class="gx-case">
        <div class="gx-case__label">resizable: true (default) | false</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-resizable" placeholder="Resizable" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-not-resizable" :resizable="false" placeholder="Not resizable" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">height: default 7.5rem | custom 4rem | numeric 200 (→ 200px) | "" (native rows)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-height-default" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-height-custom" height="4rem" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-height-numeric" :height="200" :resizable="false" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-height-rows" height="" rows="5" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">autoGrow: minRows/maxRows bounds, and combined with maxHeight (min(…))</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-auto-grow" :auto-grow="true" placeholder="3 → 12 rows" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-auto-grow-rows" :auto-grow="true" :min-rows="5" :max-rows="8" placeholder="5 → 8 rows" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-auto-grow-max" :auto-grow="true" max-height="200px" placeholder="Capped at 200px" />
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-max-height" max-height="12rem" placeholder="maxHeight without autoGrow" />
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: invalid | disabled | small form-field | character limit</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :textarea="true" :invalid="true">
                <tedi:textarea id="ta-invalid" :invalid="true" value="Vigane sisu" />
                <x-slot:feedback>
                    <tedi:feedback-text type="error" text="Tagasiside tekst" />
                </x-slot:feedback>
            </tedi:form-field>

            <tedi:form-field :textarea="true" :disabled="true">
                <tedi:textarea id="ta-disabled" :disabled="true" value="Disabled" />
            </tedi:form-field>

            <tedi:form-field :textarea="true" size="small">
                <tedi:textarea id="ta-small" placeholder="Small" />
            </tedi:form-field>

            <tedi:form-field :textarea="true" :character-limit="400" :character-count="12">
                <tedi:textarea id="ta-char-limit">Kaksteist tk</tedi:textarea>
            </tedi:form-field>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">content: slot fallback | value wins | wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-slot">Sisu slotist</tedi:textarea>
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-value" value="Sisu value-propist">Ignoreeritud</tedi:textarea>
            </tedi:form-field>

            <tedi:form-field :textarea="true">
                <tedi:textarea id="ta-wire" wire:model="bio" />
            </tedi:form-field>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Search</h2>
    <p>Port of <code>form/search</code>. Composes <code>form-field</code> + <code>text-field</code> with an optional trailing <code>button</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: small | default | large — plain, icon-only button, button with text</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            @foreach (['small', 'default', 'large'] as $size)
                <tedi:search :input-id="'search-'.$size.'-plain'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size" />
                <tedi:search :input-id="'search-'.$size.'-icon'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size.', nupp ikooniga'" :button="['ariaLabel' => 'Otsi']" />
                <tedi:search :input-id="'search-'.$size.'-button'" :size="$size" label="Otsing" :aria-label="'Otsing – '.$size.', nupp tekstiga'" :button="['text' => 'Otsi']" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">button variant: primary (default) | secondary | neutral | danger</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['primary', 'secondary', 'neutral', 'danger'] as $variant)
                <tedi:search :input-id="'search-variant-'.$variant" label="Otsing" :button="['text' => 'Otsi', 'variant' => $variant]" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: clearable with value | clearable off | disabled | hint | valid | error</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:search input-id="search-clearable" label="Otsing" value="Lorem ipsum" />
            <tedi:search input-id="search-not-clearable" label="Otsing" value="Lorem ipsum" :clearable="false" />
            <tedi:search input-id="search-disabled" label="Otsing" value="Lorem ipsum" :disabled="true" />
            <tedi:search input-id="search-hint" label="Otsing" :feedback-text="['text' => 'Vihjetekst']" />
            <tedi:search input-id="search-valid" label="Otsing" :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'valid']" />
            <tedi:search input-id="search-error" label="Otsing" :feedback-text="['text' => 'Tagasiside tekst', 'type' => 'error']" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">no visible label (ariaLabel only) | custom searchIcon | wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:search input-id="search-no-label" placeholder="Otsi tooteid või teenuseid..." aria-label="Otsi tooteid või teenuseid" />
            <tedi:search input-id="search-custom-icon" label="Otsing" search-icon="person" />
            <tedi:search input-id="search-wire" label="Otsing" wire:model.live="query" :button="['text' => 'Otsi']" :button-attributes="['wire:click' => 'search']" />
        </div>
    </div>
</div>
