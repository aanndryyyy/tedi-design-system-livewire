{{--
    TEDI Radio.
    Port of angular/tedi/components/form/radio/radio.component.ts

    Angular's selector is the attribute-based `input[type=radio][tedi-radio]`
    (not an element/class selector), so the vendored SCSS targets the literal
    `tedi-radio` HTML attribute directly — it must be emitted verbatim
    alongside the modifier classes for the styles to apply.

    Angular's RadioGroupComponent coordination (registerChild/onChildChange,
    a managed-group ControlValueAccessor) is not ported: per CONVENTIONS.md §6,
    Livewire's wire:model on this native <input> replaces reactive-forms
    binding — radios sharing a `name` attribute already coordinate natively.

    What does port from <tedi:radio-group> is ambient state: `disabled` and
    `name`, picked up via @aware (CONVENTIONS.md §3, `inject(ParentComponent)`)
    — Angular's group imperatively stamps a shared `name` onto every child
    radio. The fallbacks below (`false` / `null`) match radio-group's own @props
    defaults, so <tedi:radio-group> and <tedi:radio-group :disabled="false">
    render identically. Values written directly on this radio win over the
    ancestor's, and since @aware walks the whole ancestor stack the same
    `disabled` inheritance applies inside <tedi:form-field> /
    <tedi:input-group>, which already propagate it this way.

    Angular's auto-generated group name (`tedi-radio-group-N`) cannot cross the
    @aware boundary — @aware reads the parent's attribute bag, so only a `name`
    the consumer explicitly wrote on <tedi:radio-group> propagates. Pass it
    explicitly, or set `name` on each radio.

    `value` is deliberately NOT @aware: the group's `value` means the selected
    value, not this input's submitted value, and @aware would silently
    overwrite the latter.
--}}
@props([
    /** default|large */
    'size' => 'default',
    /** Is radio invalid? */
    'invalid' => false,
    'disabled' => false,
    'name' => null,
    'value' => null,
    'id' => null,
    'checked' => false,
])
@aware(['disabled' => false, 'name' => null])

<input
    type="radio"
    tedi-radio
    @if ($name !== null) name="{{ $name }}" @endif
    @if ($value !== null) value="{{ $value }}" @endif
    @if ($id !== null) id="{{ $id }}" @endif
    @checked($checked)
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-radio--large' => $size === 'large',
        'tedi-radio--invalid' => (bool) $invalid,
    ]) }}
/>
