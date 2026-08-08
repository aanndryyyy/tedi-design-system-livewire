<div class="gx-sec">
    <h2>Checkbox</h2>
    <p>Port of <code>form/checkbox</code>. The native <code>&lt;input type="checkbox"&gt;</code> — wrap it in your own <code>&lt;label&gt;</code> for the click target and text.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | large</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox size="default" />
                Default
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox size="large" />
                Large
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: checked | invalid | disabled | disabled+checked</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox :checked="true" />
                Checked
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox :invalid="true" />
                Invalid
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox :disabled="true" />
                Disabled
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox :disabled="true" :checked="true" />
                Disabled + checked
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:checkbox wire:model="agree" />
                Bound to $agree
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">label + sibling feedback-text (hint / error indentation)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            {{-- checkbox.component.scss:110 indents the feedback text to line up with
                 the label text via `label:has(input[tedi-checkbox]) + tedi-feedback-text`
                 — an adjacent-sibling rule, so the two must be siblings in this order. --}}
            <div>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox />
                    Saada mulle teavitusi
                </label>
                <tedi:feedback-text text="Kuni üks e-kiri nädalas." />
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:checkbox :invalid="true" />
                    Nõustun tingimustega
                </label>
                <tedi:feedback-text type="error" text="Tingimustega nõustumine on kohustuslik." />
            </div>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Radio</h2>
    <p>Port of <code>form/radio</code>. The native <code>&lt;input type="radio"&gt;</code> — radios sharing a <code>name</code> coordinate natively, or bind each with <code>wire:model</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">size: default | large</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-size-demo" size="default" />
                Default
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-size-demo" size="large" />
                Large
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: checked | invalid | disabled | disabled+checked</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-state-checked" :checked="true" />
                Checked
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-state-invalid" :invalid="true" />
                Invalid
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-state-disabled" :disabled="true" />
                Disabled
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio name="radio-state-disabled-checked" :disabled="true" :checked="true" />
                Disabled + checked
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio wire:model="plan" value="basic" />
                Basic
            </label>
            <label style="display:flex;align-items:center;gap:.5rem">
                <tedi:radio wire:model="plan" value="pro" />
                Pro
            </label>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">label + sibling feedback-text (hint / error indentation)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            {{-- radio.component.scss:102, same adjacent-sibling shape as checkbox. --}}
            <div>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio name="radio-feedback-demo" />
                    Igakuine
                </label>
                <tedi:feedback-text text="Arve esitatakse iga kuu 1. kuupäeval." />
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:.5rem">
                    <tedi:radio name="radio-feedback-demo" :invalid="true" />
                    Aastane
                </label>
                <tedi:feedback-text type="error" text="Vali makseperiood." />
            </div>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Checkbox Card</h2>
    <p>Port of <code>form/checkbox-card</code> + <code>form/checkbox-card-group</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant: primary | secondary</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:checkbox-card-group>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox :checked="true" />
                    Analytics
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox />
                    Export
                </tedi:checkbox-card>
            </tedi:checkbox-card-group>

            <tedi:checkbox-card-group>
                <tedi:checkbox-card variant="secondary">
                    <tedi:checkbox :checked="true" />
                    Analytics
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="secondary">
                    <tedi:checkbox />
                    Export
                </tedi:checkbox-card>
            </tedi:checkbox-card-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with description (feedback slot) | disabled | hidden indicator</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:checkbox-card-group>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox />
                    Audit log
                    <x-slot:feedback>
                        <span class="tedi-feedback-text">Adds a tamper-evident trail</span>
                    </x-slot:feedback>
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="primary">
                    <tedi:checkbox :disabled="true" />
                    Disabled
                </tedi:checkbox-card>
                <tedi:checkbox-card variant="primary" :show-indicator="false">
                    <tedi:checkbox :checked="true" />
                    Hidden indicator
                </tedi:checkbox-card>
            </tedi:checkbox-card-group>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Radio Card</h2>
    <p>Port of <code>form/radio-card</code> + <code>form/radio-card-group</code>.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant: primary | secondary</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-card-group>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="rc-primary" :checked="true" />
                    Basic
                </tedi:radio-card>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="rc-primary" />
                    Pro
                </tedi:radio-card>
            </tedi:radio-card-group>

            <tedi:radio-card-group>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="rc-secondary" :checked="true" />
                    Basic
                </tedi:radio-card>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="rc-secondary" />
                    Pro
                </tedi:radio-card>
            </tedi:radio-card-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">grouped (button-group layout, shared borders)</div>
        <div class="gx-case__demo">
            <tedi:radio-card-group :grouped="true">
                <tedi:radio-card variant="primary">
                    <tedi:radio name="rc-grouped" />
                    Basic
                </tedi:radio-card>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="rc-grouped" :checked="true" />
                    Pro
                </tedi:radio-card>
                <tedi:radio-card variant="primary">
                    <tedi:radio name="rc-grouped" />
                    Enterprise
                </tedi:radio-card>
            </tedi:radio-card-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">with description (feedback slot) | disabled | hidden indicator</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:radio-card-group>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="rc-misc" :checked="true" />
                    Pro
                    <x-slot:feedback>
                        <span class="tedi-feedback-text">Best for growing teams</span>
                    </x-slot:feedback>
                </tedi:radio-card>
                <tedi:radio-card variant="secondary">
                    <tedi:radio name="rc-misc-disabled" :disabled="true" />
                    Disabled
                </tedi:radio-card>
                <tedi:radio-card variant="secondary" :show-indicator="false">
                    <tedi:radio name="rc-misc-hidden" :checked="true" />
                    Hidden indicator
                </tedi:radio-card>
            </tedi:radio-card-group>
        </div>
    </div>
</div>
