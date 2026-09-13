{{--
    TEDI Text field.
    Port of angular/tedi/components/form/text-field/text-field.component.ts

    Angular's selector is the attribute-based `input[tedi-text-field]` (not an
    element or class selector), so the root is a native `<input>` carrying the
    literal `tedi-text-field` attribute alongside the host class — the same
    shape as checkbox.blade.php (CONVENTIONS.md §4, "element selectors in the
    vendored SCSS").

    `{{ $attributes }}` lands on the `<input>` itself, so `wire:model` binds
    directly (CONVENTIONS.md §6, native-control carve-out).

    Divergences:

    * `value` is emitted ONLY when non-empty. Angular's `value = model<string>("")`
      defaults to the empty string, but unconditionally rendering `value=""`
      would blank out a `wire:model`-bound field on the initial server render.
      Pass `value=""` (or omit it) for "no value"; the attribute is dropped.
    * The `clear` output() and the ControlValueAccessor plumbing
      (writeValue/registerOnChange/setDisabledState, the `effect()` that syncs
      the DOM value) are not ported — CONVENTIONS.md §7 item 2. The clear button
      itself lives on <tedi:form-field>; bind your own listener to it via that
      component's `clearAttributes`.
    * Angular's `disabled` is a computed of the local input, the reactive-forms
      disabled state and the enclosing input-group. Only the local prop exists
      here; nest inside <tedi:input-group> and pass `disabled` on the group's
      own control, or set it per field.
    * `invalid` is an Angular signal driven by the parent form-field's
      NgControl subscription (`setInvalidState`). Server-side there is no live
      control, so it is an explicit prop (CONVENTIONS.md §5).
    * `ownsSurface` / `valid` / `size` come from a wrapping `<tedi:form-field>`
      via @aware (`TEDI_FIELD_CONTEXT`). When the wrapper has no box, this
      control paints `tedi-field-surface` itself (Angular 8).
--}}
@props([
    /** Current value. Emitted as the `value` attribute only when non-empty. */
    'value' => '',
    /** Hides the native spinner arrows on `type="number"` inputs. */
    'arrowsHidden' => true,
    /** Renders `aria-invalid="true"`; the visual error state lives on the wrapping form-field. */
    'invalid' => false,
    'disabled' => false,
    'valid' => false,
    /** default|small|large — falls back to a wrapping form-field's size. */
    'size' => 'default',
    'ownsSurface' => false,
])

@aware([
    'ownsSurface' => false,
    'valid' => false,
    'size' => 'default',
    'invalid' => false,
    'disabled' => false,
])

<input
    tedi-text-field
    @disabled($disabled)
    {{ $attributes->class([
        'tedi-text-field',
        'tedi-text-field--small' => $size === 'small',
        'tedi-text-field--large' => $size === 'large',
        'tedi-text-field--arrows-hidden' => (bool) $arrowsHidden,
        'tedi-field-surface' => ! $ownsSurface,
        'tedi-field-surface--valid' => ! $ownsSurface && $valid,
    ])->merge(array_filter([
        'value' => (string) $value !== '' ? $value : null,
        'aria-invalid' => $invalid ? 'true' : null,
    ], fn ($v) => $v !== null)) }}
/>
