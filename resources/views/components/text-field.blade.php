{{--
    TEDI Text field.
    Port of angular/src/tedi/components/form/text-field/text-field.component.ts

    Angular's selector is the attribute-based `input[tedi-text-field]` (not an
    element or class selector), so the root is a native `<input>` carrying the
    literal `tedi-text-field` attribute alongside the host class — the same
    shape as checkbox.blade.php (CONVENTIONS.md §4, "element selectors in the
    vendored SCSS").

    `{{ $attributes }}` lands on the `<input>` itself, so `wire:model` binds
    directly (CONVENTIONS.md §6, native-control carve-out).

    Since Angular 8.0 (#612) the field paints its own surface
    (`tedi-field-surface`) and needs no wrapper. Inside a `tedi:form-field`
    that renders a box (an icon, or a clear button) the box paints it instead.
    The field context — Angular's `inject(TEDI_FIELD_CONTEXT)` — comes from
    `Tedi::fieldContext()`, which also registers this field as the form-field's
    control (Angular's `TEDI_FORM_FIELD_CONTROL`), so the form-field's clear
    button and character counter follow this field's `value`, `disabled`,
    `readonly` and `invalid` without repeating them there:

      size      own `size` → the field context's → default
      invalid   own `invalid`, or the context's (an `invalid` form-field, an
                exceeded character limit, an invalid input group)
      disabled  own `disabled`, or the context's (a disabled form-field or
                input group)
      valid     the context's (`tedi-field-surface--valid`, only while this
                field paints the surface)

    Divergences:

    * `value` is emitted ONLY when non-empty. Angular's `value = model<string>("")`
      defaults to the empty string, but unconditionally rendering `value=""`
      would blank out a `wire:model`-bound field on the initial server render.
      Pass `value=""` (or omit it) for "no value"; the attribute is dropped.
    * The `clear` output() and the ControlValueAccessor plumbing
      (writeValue/registerOnChange/setDisabledState, the `effect()` that syncs
      the DOM value, `reset()`, `focus()`) are not ported — CONVENTIONS.md §7
      item 2. The clear button itself lives on `tedi:form-field`; bind your own
      listener to it via that component's `clearAttributes`.
    * Reactive-forms state (`deriveControlState`: invalid once touched or
      dirty, the form's disabled state) has no server-side equivalent; pass
      `invalid` / `disabled` explicitly.
    * `readonly` is one prop; Angular accepts both the `readonly` attribute and
      the `[readOnly]` property spelling.
    * `aria-describedby` merges what you write with the ids the field context
      pushes (the character counter). A feedback text's id is not among them —
      see form-field.blade.php.
--}}
@props([
    /** Current value. Emitted as the `value` attribute only when non-empty. */
    'value' => '',
    /** small|default|large. Falls back to the wrapping form-field's size. */
    'size' => null,
    /** Forces the error state (`aria-invalid="true"`). */
    'invalid' => false,
    /** Hides the native spinner arrows on `type="number"` inputs. */
    'arrowsHidden' => true,
    'disabled' => false,
    /** Native `readonly`. A wrapping form-field hides its clear button while it is set. */
    'readonly' => false,
    /** Port-only: an explicit field context (Tedi\Livewire\View\FieldContext), which wins over the nearest form-field's; `tedi:date-input` passes its date field's here. */
    'fieldContext' => null,
])

@php
    $context = \Tedi\Livewire\Tedi::fieldContext($__env, [
        'reset' => true,
        'value' => $value,
        'disabled' => (bool) $disabled,
        'readOnly' => (bool) $readonly,
        'invalid' => (bool) $invalid,
    ]);

    $isDisabled = (bool) $disabled || ($context['disabled'] ?? false);
    $isInvalid = (bool) $invalid || ($context['invalid'] ?? false);
    $resolvedSize = $size ?? ($context['size'] ?? 'default');
    $paintsSurface = ! ($context['ownsSurface'] ?? false);
    $isValid = $context['valid'] ?? false;

    // controlDescribedBy(): own ids first, then the pushed ones, de-duplicated.
    $describedBy = array_values(array_unique(array_merge(
        preg_split('/\s+/', trim((string) $attributes->get('aria-describedby', '')), -1, PREG_SPLIT_NO_EMPTY),
        $context['describedBy'] ?? [],
    )));
@endphp

<input
    tedi-text-field
    @disabled($isDisabled)
    @readonly($readonly)
    {{ $attributes->except('aria-describedby')->class([
        'tedi-text-field',
        'tedi-field-surface' => $paintsSurface,
        'tedi-field-surface--valid' => $paintsSurface && $isValid,
        'tedi-text-field--small' => $resolvedSize === 'small',
        'tedi-text-field--large' => $resolvedSize === 'large',
        'tedi-text-field--arrows-hidden' => (bool) $arrowsHidden,
    ])->merge(array_filter([
        'value' => (string) $value !== '' ? $value : null,
        'aria-invalid' => $isInvalid ? 'true' : null,
        'aria-describedby' => $describedBy !== [] ? implode(' ', $describedBy) : null,
    ], fn ($v) => $v !== null)) }}
/>
