{{--
    TEDI Radio Group.
    Port of angular/tedi/components/form/radio-group/radio-group.component.{ts,html}

    Angular's selector `tedi-radio-group` is an element selector, but the
    vendored SCSS contains no `tedi-radio-group { … }` element rule (only the
    `.tedi-radio-group` class and its `__checks` / `__subtexts` children), so
    per CONVENTIONS.md §4 this renders a <div> carrying the host class — the
    same shape as radio-card-group.blade.php.

    Divergences from Angular:

    * `tedi-radio-group__label` is DROPPED. Angular puts it on the label <p>,
      but dist/tedi.css ships no rule for it (verified: 0 hits), so per
      CONVENTIONS.md §4 ("classes Angular emits but TEDI never styles") it is
      omitted. Restore it here if TEDI ever ships the rule. The <p> is still
      rendered — it carries the generated `id` that `aria-labelledby` points at
      — and still gets Angular's `tedi-text color="secondary"` treatment via
      <tedi:text as="p" color="secondary">.

    * `managed` is an explicit prop (default false). Angular flips an internal
      `managed` signal the moment `[(value)]` is bound or a
      ControlValueAccessor registers, and only then emits `role`,
      `aria-labelledby`, `aria-label` and `aria-disabled` on the host. A
      server-rendered template cannot detect that, so per CONVENTIONS.md §5
      (runtime detection → explicit prop) it becomes a prop, defaulting to
      false to match Angular's untouched default. **Pass `managed` whenever the
      group is a real grouping control** rather than a layout wrapper — that is
      what turns on `role="radiogroup"` and the rest. Note the consequence:
      while unmanaged, an `aria-label` / `aria-labelledby` you pass is
      swallowed, exactly as in Angular.

    * Angular's managed-group ControlValueAccessor (registerChild /
      onChildChange / applyCheckedTo, which writes `checked` onto every child)
      is NOT ported — `wire:model` on each child <tedi:radio> replaces it, and
      radios sharing a `name` already coordinate natively (same ruling as
      radio.blade.php). `value` is therefore declared but informational for the
      server render: it documents the selection and drives the `managed`
      semantics; it does not set child `checked`. Put `wire:model` on each
      <tedi:radio>, not on the group — on the group it would land inertly on
      this <div>.

    * `disabled` and `name` are the ambient state that does port: child
      <tedi:radio> components read both through `@aware` (CONVENTIONS.md §3).
      The `@aware` fallbacks there (`false` / `null`) match this component's
      `@props` defaults, so `<tedi:radio-group>` and
      `<tedi:radio-group :disabled="false">` render identically. Because
      `@aware` walks the whole ancestor stack, a radio also inherits `disabled`
      from an enclosing <tedi:form-field> / <tedi:input-group>, which already
      propagate it the same way.

      Angular auto-generates a group `name` (`tedi-radio-group-N`) when none is
      given and imperatively stamps it onto every child. That cannot cross the
      `@aware` boundary: `@aware` reads the parent's *attribute bag*, so only a
      `name` the consumer explicitly wrote propagates. **Pass `name`
      explicitly** — which Angular's own input doc already recommends, to avoid
      SSR hydration mismatches.

    * Angular's `<ng-content select="tedi-feedback-text" />` becomes the named
      `subtexts` slot: `<x-slot:subtexts><tedi:feedback-text … /></x-slot:subtexts>`.
      The wrapper <div> is rendered unconditionally, as Angular does; the SCSS
      hides it with `&:empty`, which is why nothing — not even whitespace — may
      be emitted inside it when the slot is absent.
--}}
@props([
    /** Label text displayed above the radio group. */
    'label' => null,
    /** horizontal|vertical */
    'direction' => 'horizontal',
    /**
     * Selected value. Informational on the server render — child `checked`
     * state comes from each radio's own wire:model, not from here.
     */
    'value' => null,
    /** Shared `name` applied to child radios (see the note above). Not auto-generated — pass it. */
    'name' => null,
    /** Disables the entire group; propagates to child radios (see the note above). */
    'disabled' => false,
    /** Explicit stand-in for Angular's runtime `managed` signal; gates the group ARIA. */
    'managed' => false,
    /** Accessible name. Ignored unless `managed`, and when `label`/`ariaLabelledby` is set. */
    'ariaLabel' => null,
    /** ID of an external labelling element. Ignored unless `managed`, and when `label` is set. */
    'ariaLabelledby' => null,
])

@php
    $labelId = \Tedi\Livewire\Tedi::id('tedi-radio-group').'-label';

    $managedAriaLabelledby = ! $managed ? null : ($label ? $labelId : $ariaLabelledby);
    $managedAriaLabel = ! $managed ? null : (($label || $ariaLabelledby) ? null : $ariaLabel);
@endphp

<div {{ $attributes->class([
    'tedi-radio-group',
])->merge(array_filter([
    'role' => $managed ? 'radiogroup' : null,
    'aria-labelledby' => $managedAriaLabelledby,
    'aria-label' => $managedAriaLabel,
    'aria-disabled' => ($managed && $disabled) ? 'true' : null,
])) }}>
    @if ($label)
        <tedi:text as="p" color="secondary" id="{{ $labelId }}">{{ $label }}</tedi:text>
    @endif

    <div @class([
        'tedi-radio-group__checks',
        'tedi-radio-group__checks--vertical' => $direction === 'vertical',
    ])>
        {{ $slot }}
    </div>

    <div class="tedi-radio-group__subtexts">{{ $subtexts ?? '' }}</div>
</div>
