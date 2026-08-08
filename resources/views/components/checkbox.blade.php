{{--
    TEDI Checkbox.
    Port of angular/tedi/components/form/checkbox/checkbox.component.ts

    Angular's selector is the attribute-based `input[type=checkbox][tedi-checkbox]`
    (not an element/class selector), so the vendored SCSS targets the literal
    `tedi-checkbox` HTML attribute directly — it must be emitted verbatim
    alongside the modifier classes for the styles to apply.

    Angular's CheckboxGroupComponent coordination (registerChild/onChildChange,
    a managed-group ControlValueAccessor) is not ported: per CONVENTIONS.md §6,
    Livewire's wire:model on this native <input> replaces reactive-forms
    binding.

    What does port from <tedi:checkbox-group> is ambient state: `disabled`,
    picked up via @aware (CONVENTIONS.md §3, `inject(ParentComponent)`). The
    fallback below is `false`, matching checkbox-group's own @props default, so
    <tedi:checkbox-group> and <tedi:checkbox-group :disabled="false"> render
    identically. A `disabled` written directly on this checkbox wins over the
    ancestor's, and since @aware walks the whole ancestor stack the same
    inheritance applies inside <tedi:form-field> / <tedi:input-group>, which
    already propagate `disabled` this way.

    `value` is deliberately NOT @aware: the group's `values` means the selected
    set, not this input's submitted value, and @aware would silently overwrite
    the latter.
--}}
@props([
    /** default|large */
    'size' => 'default',
    /** Is checkbox invalid? */
    'invalid' => false,
    'disabled' => false,
    'name' => null,
    'value' => null,
    'id' => null,
    'checked' => false,
])
@aware(['disabled' => false])

<input
    type="checkbox"
    tedi-checkbox
    @if ($name !== null) name="{{ $name }}" @endif
    @if ($value !== null) value="{{ $value }}" @endif
    @if ($id !== null) id="{{ $id }}" @endif
    @checked($checked)
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-checkbox--large' => $size === 'large',
        'tedi-checkbox--invalid' => (bool) $invalid,
    ]) }}
/>
