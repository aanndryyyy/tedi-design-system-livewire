<div class="gx-sec">
    <h2>Toggle</h2>
    <p>Port of <code>form/toggle</code>. A <code>&lt;div class="tedi-toggle"&gt;</code> wrapping a native <code>&lt;input type="checkbox" role="switch"&gt;</code> — <code>wire:model</code> lands on the input.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant &times; type: primary|colored &times; filled|outlined (unchecked / checked)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['primary', 'colored'] as $variant)
                @foreach (['filled', 'outlined'] as $type)
                    <div style="display:flex;align-items:center;gap:1rem">
                        <span style="min-width:12rem">{{ $variant }} / {{ $type }}</span>
                        <tedi:toggle :variant="$variant" :type="$type" :aria-label="$variant.' '.$type.' off'" />
                        <tedi:toggle :variant="$variant" :type="$type" :checked="true" :aria-label="$variant.' '.$type.' on'" />
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default | large</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach (['default', 'large'] as $size)
                <div style="display:flex;align-items:center;gap:1rem">
                    <span style="min-width:12rem">{{ $size }}</span>
                    <tedi:toggle :size="$size" :aria-label="$size.' off'" />
                    <tedi:toggle :size="$size" :checked="true" :aria-label="$size.' on'" />
                </div>
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon: lock (large only) &mdash; iconColor() across variant &times; type &times; checked</div>
        <div class="gx-case__demo gx-case__demo--stack">
            {{-- iconColor(): outlined is always white; filled is brand/tertiary
                 for primary and success/danger for colored. --}}
            @foreach (['primary', 'colored'] as $variant)
                @foreach (['filled', 'outlined'] as $type)
                    <div style="display:flex;align-items:center;gap:1rem">
                        <span style="min-width:12rem">{{ $variant }} / {{ $type }}</span>
                        <tedi:toggle size="large" :icon="true" :variant="$variant" :type="$type" :aria-label="$variant.' '.$type.' lock off'" />
                        <tedi:toggle size="large" :icon="true" :variant="$variant" :type="$type" :checked="true" :aria-label="$variant.' '.$type.' lock on'" />
                    </div>
                @endforeach
            @endforeach

            {{-- icon is ignored at the default size, matching Angular. --}}
            <tedi:toggle size="default" :icon="true" aria-label="Ikoonita, vaikimisi suurus" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: disabled | disabled + checked | required</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:toggle :disabled="true" aria-label="Keelatud" />
            <tedi:toggle :disabled="true" :checked="true" aria-label="Keelatud, sees" />
            <tedi:toggle :required="true" aria-label="Kohustuslik" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">external label + wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <div style="display:flex;align-items:center;gap:.5rem">
                <tedi:form.label for="matrix-toggle-labelled">Saada teavitusi</tedi:form.label>
                <tedi:toggle input-id="matrix-toggle-labelled" wire:model="notifications" />
            </div>
        </div>
    </div>
</div>

<div class="gx-sec">
    <h2>Slider</h2>
    <p>Port of <code>form/slider</code>. A <code>&lt;div class="tedi-slider"&gt;</code> wrapping a native <code>&lt;input type="range"&gt;</code>. The thumb tooltip (CDK Overlay) is not ported, and <code>tedi-slider--invalid</code> / <code>tedi-slider--dragging</code> are not emitted &mdash; see the component header.</p>

    <div class="gx-case">
        <div class="gx-case__label">default (label, min/max labels)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-default" label="Väärtus" :value="50" min-label="0%" max-label="100%" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">hideLabel: false | true | keep-space</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-hl-false" label="Nähtav silt" :hide-label="false" :value="20" min-label="0%" max-label="100%" />
            <tedi:slider input-id="matrix-slider-hl-true" label="Peidetud silt" :hide-label="true" :value="40" min-label="0%" max-label="100%" />
            <tedi:slider input-id="matrix-slider-hl-keep" label="Ruumi hoidev silt" hide-label="keep-space" :value="60" min-label="0%" max-label="100%" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">progress: 0 / 25 / 50 / 100 (clamped, with the --tedi-slider-progress custom properties)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            @foreach ([0, 25, 50, 100, 150] as $value)
                <tedi:slider :input-id="'matrix-slider-p-'.$value" :aria-label="'Väärtus '.$value" :value="$value" min-label="0" max-label="100" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">custom range + step (1&ndash;10, step 1)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-range" label="Hinne" :min="1" :max="10" :step="1" :value="4" min-label="1" max-label="10" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">showCurrentValue (with and without a valueFormatter)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-current" aria-label="Väärtus" :value="35" :show-current-value="true" min-label="0%" />
            <tedi:slider
                input-id="matrix-slider-current-fmt"
                aria-label="Vormindatud väärtus"
                :value="35"
                :show-current-value="true"
                min-label="0%"
                :value-formatter="fn ($value) => $value.'%'"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">states: disabled | invalid (aria-invalid only) | required</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-disabled" label="Keelatud" :disabled="true" :value="50" min-label="0%" max-label="100%" />
            <tedi:slider input-id="matrix-slider-invalid" label="Vigane" :invalid="true" :value="50" min-label="0%" max-label="100%" />
            <tedi:slider input-id="matrix-slider-required" label="Kohustuslik" :required="true" :value="50" min-label="0%" max-label="100%" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">feedbackText: hint | error (error also sets aria-invalid)</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider
                input-id="matrix-slider-hint"
                label="Vihjega"
                :value="50"
                min-label="0%"
                max-label="100%"
                :feedback-text="['text' => 'Vali väärtus 0 ja 100 vahel.', 'type' => 'hint', 'position' => 'left']"
            />
            <tedi:slider
                input-id="matrix-slider-error"
                label="Veaga"
                :value="50"
                min-label="0%"
                max-label="100%"
                :feedback-text="['text' => 'See väli on kohustuslik.', 'type' => 'error', 'position' => 'left']"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">addon slot ([sliderAddon] content projection) + wire:model binding</div>
        <div class="gx-case__demo gx-case__demo--stack">
            <tedi:slider input-id="matrix-slider-addon" label="Väärtus" :value="20" min-label="0%" max-label="100%" wire:model="volume">
                <x-slot:addon>
                    <input type="number" aria-label="Väärtus" value="20" style="width: 5rem" />
                </x-slot:addon>
            </tedi:slider>
        </div>
    </div>
</div>
