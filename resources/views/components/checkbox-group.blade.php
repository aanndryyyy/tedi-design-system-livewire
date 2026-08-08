{{--
    TEDI Checkbox Group.
    Port of angular/tedi/components/form/checkbox-group/checkbox-group.component.{ts,html}

    Angular's selector `tedi-checkbox-group` is an element selector, but the
    vendored SCSS contains no `tedi-checkbox-group { … }` element rule (only the
    `.tedi-checkbox-group` class and its `__checks` / `__subtexts` children), so
    per CONVENTIONS.md §4 this renders a <div> carrying the host class — the
    same shape as radio-card-group.blade.php.

    Divergences from Angular:

    * `tedi-checkbox-group__label` is DROPPED. Angular puts it on the label <p>,
      but dist/tedi.css ships no rule for it (verified: 0 hits), so per
      CONVENTIONS.md §4 ("classes Angular emits but TEDI never styles") it is
      omitted. Restore it here if TEDI ever ships the rule. The <p> is still
      rendered — it carries the generated `id` that `aria-labelledby` points at
      — and still gets Angular's `tedi-text color="secondary"` treatment via
      <tedi:text as="p" color="secondary">.

    * `managed` is an explicit prop (default false). Angular flips an internal
      `managed` signal the moment `[(values)]` is bound or a
      ControlValueAccessor registers, and only then emits `role`,
      `aria-labelledby`, `aria-label` and `aria-disabled` on the host. A
      server-rendered template cannot detect that, so per CONVENTIONS.md §5
      (runtime detection → explicit prop) it becomes a prop, defaulting to
      false to match Angular's untouched default. **Pass `managed` whenever the
      group is a real grouping control** rather than a layout wrapper — that is
      what turns on the group ARIA. Note the consequence: while unmanaged, an
      `aria-label` / `aria-labelledby` you pass is swallowed, exactly as in
      Angular.

    * Angular's managed-group ControlValueAccessor (registerChild /
      onChildChange / applyCheckedTo, which writes `checked` onto every child)
      is NOT ported — `wire:model` on each child <tedi:checkbox> replaces it
      (same ruling as checkbox.blade.php). `values` is therefore declared but
      informational for the server render: it documents the selection and
      drives the `managed` semantics; it does not set child `checked`. Put
      `wire:model` on each <tedi:checkbox>, not on the group — on the group it
      would land inertly on this <div>.

    * `disabled` is the one piece of ambient state that does port: child
      <tedi:checkbox> components read it through `@aware` (CONVENTIONS.md §3).
      The `@aware` fallback there is `false`, matching this component's `@props`
      default, so `<tedi:checkbox-group>` and `<tedi:checkbox-group :disabled="false">`
      render identically. Because `@aware` walks the whole ancestor stack, a
      checkbox also inherits `disabled` from an enclosing <tedi:form-field> /
      <tedi:input-group>, which already propagate it the same way.

    * Angular's `<ng-content select="tedi-feedback-text" />` becomes the named
      `subtexts` slot: `<x-slot:subtexts><tedi:feedback-text … /></x-slot:subtexts>`.
      The wrapper <div> is rendered unconditionally, as Angular does; the SCSS
      hides it with `&:empty`, which is why nothing — not even whitespace — may
      be emitted inside it when the slot is absent.
--}}
@props([
    /** Label text displayed above the checkbox group. */
    'label' => null,
    /** horizontal|vertical */
    'direction' => 'horizontal',
    /**
     * Selected values. Informational on the server render — child `checked`
     * state comes from each checkbox's own wire:model, not from here.
     */
    'values' => [],
    /** Disables the entire group; propagates to child checkboxes (see the note above). */
    'disabled' => false,
    /** Explicit stand-in for Angular's runtime `managed` signal; gates the group ARIA. */
    'managed' => false,
    /** Accessible name. Ignored unless `managed`, and when `label`/`ariaLabelledby` is set. */
    'ariaLabel' => null,
    /** ID of an external labelling element. Ignored unless `managed`, and when `label` is set. */
    'ariaLabelledby' => null,
])

@php
    $labelId = \Tedi\Livewire\Tedi::id('tedi-checkbox-group').'-label';

    $managedAriaLabelledby = ! $managed ? null : ($label ? $labelId : $ariaLabelledby);
    $managedAriaLabel = ! $managed ? null : (($label || $ariaLabelledby) ? null : $ariaLabel);
@endphp

<div {{ $attributes->class([
    'tedi-checkbox-group',
])->merge(array_filter([
    'role' => $managed ? 'group' : null,
    'aria-labelledby' => $managedAriaLabelledby,
    'aria-label' => $managedAriaLabel,
    'aria-disabled' => ($managed && $disabled) ? 'true' : null,
])) }}>
    @if ($label)
        <tedi:text as="p" color="secondary" id="{{ $labelId }}">{{ $label }}</tedi:text>
    @endif

    <div @class([
        'tedi-checkbox-group__checks',
        'tedi-checkbox-group__checks--vertical' => $direction === 'vertical',
    ])>
        {{ $slot }}
    </div>

    <div class="tedi-checkbox-group__subtexts">{{ $subtexts ?? '' }}</div>
</div>
