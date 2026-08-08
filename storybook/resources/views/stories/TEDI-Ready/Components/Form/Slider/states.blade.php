@storybook([
    'name' => 'States',
    'order' => 7,
    'status' => 'subset',
    'args' => ['pseudoStates' => [
        'hover' => ['[data-state=hover] .tedi-slider', '#state-hover'],
        'active' => ['[data-state=active] .tedi-slider', '#state-active'],
        'focusVisible' => ['#state-focus'],
    ]],
    'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
])

{{--
    All visual states of the slider. The Hover, Active and Focus rows are forced
    by storybook-addon-pseudo-states; the `pseudoStates` arg is what
    .storybook/preview.js turns into the addon's `parameters.pseudo`.

    DIVERGENCE from CONTRACT §5's "copy the selectors verbatim": Angular's map
    uses the ELEMENT selector `tedi-slider[data-state=hover]`. This port renders
    the host as `<div class="tedi-slider">` (the vendored SCSS has no
    `tedi-slider` element rule — see slider.blade.php's header), and its
    `$attributes` land on the range <input> so `data-state` cannot be written on
    the host from the outside. The marker therefore goes on a wrapping <div> and
    the selector becomes `[data-state=hover] .tedi-slider`. The `#state-hover` /
    `#state-active` / `#state-focus` id selectors, which target the <input>,
    transfer unchanged.

    The Error row keeps its feedback text and `aria-invalid`, but no
    `tedi-slider--invalid` class: dist/tedi.css ships no rule for it, so it is
    dropped per the stylesheet guardrail.
--}}
<tedi:row :cols="1" :gap-y="3">
    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Default</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:slider input-id="state-default" aria-label="Väärtus" :value="50" min-label="0%" max-label="100%" />
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Hover</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <div data-state="hover">
                <tedi:slider input-id="state-hover" aria-label="Väärtus" :value="50" min-label="0%" max-label="100%" />
            </div>
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Active</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <div data-state="active">
                <tedi:slider input-id="state-active" aria-label="Väärtus" :value="50" min-label="0%" max-label="100%" />
            </div>
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Disabled</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:slider input-id="state-disabled" aria-label="Väärtus" :value="50" :disabled="true" min-label="0%" max-label="100%" />
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Focus</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:slider input-id="state-focus" aria-label="Väärtus" :value="50" min-label="0%" max-label="100%" />
        </tedi:col>
    </tedi:row>

    <tedi:row :cols="6" align-items="center">
        <tedi:col :width="1">
            <tedi:text as="p" modifiers="bold">Error</tedi:text>
        </tedi:col>
        <tedi:col :width="5">
            <tedi:slider
                input-id="state-error"
                aria-label="Väärtus"
                :value="50"
                :invalid="true"
                min-label="0%"
                max-label="100%"
                :feedback-text="['text' => 'See väli on kohustuslik', 'type' => 'error', 'position' => 'left']"
            />
        </tedi:col>
    </tedi:row>
</tedi:row>
