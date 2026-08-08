{{--
    TEDI Select — NATIVE-<select> SUBSET. The full combobox is NOT ported in
    this phase; see CONVENTIONS.md §7 item 4.

    Port of angular/tedi/components/form/select/{select.component.ts,select.component.html}

    Angular's tedi-select (~48KB of TS) is a full custom combobox: a div
    trigger + CDK-overlay listbox, virtual scrolling, multiselect tags, a
    search input, custom option/value templates, and a ControlValueAccessor —
    none of it a native <select>. CDK Overlay positioning is explicitly out of
    scope for this phase (CONVENTIONS.md §7 item 3), so reproducing that
    markup here would just emit divs the vendored SCSS doesn't style for a
    non-existent overlay. Instead this renders a genuinely native <select>:
    Livewire's `wire:model` binds directly, no JS reimplementation of value
    tracking is needed, and the browser supplies accessible keyboard/option
    behaviour for free. `{{ $attributes }}` therefore sits on the <select>
    itself, not the wrapper (CONVENTIONS.md §6's native-control carve-out
    overriding the general "attributes on the single root" rule — a real
    tension between §4/§6 that this component resolves in §6's favour).

    Only classes that exist in the vendored SCSS are emitted: `tedi-select`
    (block) plus the shared `tedi-input`/`tedi-input--disabled|small|error|valid`
    modifiers select.component.scss defines directly, and `tedi-select--multiselect`
    for the `multiple` attribute. None of Angular's `tedi-select__trigger`,
    `__arrow`, `__options`, etc. are rendered — those style the custom combobox
    this component does not build.

    Dropped entirely because they only make sense for the custom combobox and
    have no native-<select> equivalent: `searchable`, `groupBy`, virtual
    scrolling, tags/custom tag rendering, custom option/value templates,
    `tooltip` (overlay component, unported), and `ellipsis` (native options
    can't be styled per-character). `allowMultiple` maps to the native
    `multiple` attribute; the browser renders its own multi-select UI.
    `clearable` is dropped — a native <select> has no client-side value to
    clear without JS, and Livewire consumers can reset via wire:model instead.
--}}
@props([
    /** Id for the <select>, also used for the label's `for`. Auto-generated (Tedi::id()) when omitted. */
    'inputId' => null,
    /** Label text rendered above the control via the form label component. */
    'label' => null,
    /** small|default */
    'size' => 'default',
    /** default|valid|error */
    'state' => 'default',
    'required' => false,
    'disabled' => false,
    /** Placeholder shown as a disabled, selected first option when no value is bound. */
    'placeholder' => '',
    /**
     * Options to render. Accepts:
     *   - a flat value => label map,
     *   - a list of ['value' => ..., 'label' => ..., 'disabled' => bool] arrays,
     *   - a list of plain scalars (used as both value and label).
     */
    'options' => [],
    /** Property name to read as the option label, for array-of-object options. */
    'bindLabel' => 'label',
    /** Property name to read as the option value, for array-of-object options. When null, the array's own key is used. */
    'bindValue' => null,
    /** Renders a multi-select (native `multiple` attribute). */
    'allowMultiple' => false,
    /** Configuration for the feedback text below the select: ['text' => ..., 'type' => 'hint', 'position' => 'left']. */
    'feedbackText' => null,
])

@aware([
    'disabled' => false,
    'invalid' => false,
])

@php
    // Unconditional: referenced on every render path (the <select> id, the
    // label's `for`), so it must never depend on a branch being taken.
    $inputId = $inputId ?? \Tedi\Livewire\Tedi::id('tedi-select');

    $state = $invalid ? 'error' : $state;

    $normalizedOptions = [];
    foreach ($options as $key => $option) {
        if (is_array($option)) {
            $normalizedOptions[] = [
                'value' => $bindValue !== null ? ($option[$bindValue] ?? null) : ($option['value'] ?? $key),
                'label' => (string) ($option[$bindLabel] ?? $option['label'] ?? $key),
                'disabled' => (bool) ($option['disabled'] ?? false),
            ];
        } else {
            $normalizedOptions[] = [
                'value' => is_int($key) ? $option : $key,
                'label' => (string) $option,
                'disabled' => false,
            ];
        }
    }
@endphp

<div class="tedi-select{{ $allowMultiple ? ' tedi-select--multiselect' : '' }}">
    @if ($label)
        <tedi:label-row>
            <tedi:form.label :for="$inputId" :required="$required" :size="$size === 'small' ? 'small' : 'default'">
                {{ $label }}
            </tedi:form.label>
        </tedi:label-row>
    @endif

    <select
        id="{{ $inputId }}"
        @if ($required) required @endif
        @disabled($disabled)
        @if ($allowMultiple) multiple @endif
        {{ $attributes->class([
            'tedi-input',
            'tedi-input--disabled' => $disabled,
            'tedi-input--small' => $size === 'small',
            'tedi-input--error' => $state === 'error',
            'tedi-input--valid' => $state === 'valid',
        ]) }}
    >
        @if ($placeholder !== '' && ! $allowMultiple)
            <option value="" disabled selected hidden>{{ $placeholder }}</option>
        @endif

        @foreach ($normalizedOptions as $option)
            <option value="{{ $option['value'] }}" @disabled($option['disabled'])>{{ $option['label'] }}</option>
        @endforeach
    </select>

    @if ($feedbackText)
        <tedi:feedback-text
            :text="$feedbackText['text']"
            :type="$feedbackText['type'] ?? 'hint'"
            :position="$feedbackText['position'] ?? 'left'"
        />
    @endif
</div>
