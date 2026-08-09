{{--
    TEDI Filter group.
    Port of angular/tedi/components/filter/filter-group.component.ts

    Angular's selector is the element `tedi-filter-group`, but the vendored SCSS
    has no `tedi-filter-group { … }` ELEMENT rule — every rule keys on the
    `.tedi-filter-group` class and its `.tedi-filter` descendants — so per the
    batch ruling in CONVENTIONS.md §4 the root is a `<div>` carrying the host
    class, the way checkbox-group.blade.php and radio-card-group.blade.php do.
    That also keeps `:first-child` / `:last-child` / `:only-child` and the
    `.tedi-filter--secondary + .tedi-filter--secondary` sibling rules working,
    since they key on the children's classes rather than on this element.

    Divergences from Angular:

    * `managed` is an explicit prop (default false), exactly as on
      `tedi:checkbox-group` / `tedi:radio-group`. Angular flips an internal
      `isManaged` signal the moment a ControlValueAccessor registers, and only
      then emits `role="group"` / `role="radiogroup"` on the host. A
      server-rendered template cannot detect that, so per CONVENTIONS.md §5 it
      becomes a prop. **Pass `managed` whenever the group is a real grouping
      control** rather than a layout wrapper — it is also what turns each child
      filter into a `role="radio"` in single-select mode.

    * The managed-group ControlValueAccessor (`writeValue` / `selectFilter` /
      `isSelected`, which own every child's selected state) is NOT ported. Put
      `wire:model` on each child `tedi:filter` instead — the same ruling
      checkbox-group and radio-group took. `value` is therefore declared for
      input parity and is informational on the server render: it documents the
      selection, it does not drive the children.

    * `managed` and `disabled` are the ambient state that reaches the children
      through `@aware` (CONVENTIONS.md §3). Both fallbacks in filter.blade.php
      are `false`, matching the `@props` defaults here, so `<tedi:filter-group>`
      and `<tedi:filter-group :managed="false" :disabled="false">` render
      identically. `tests/AwareTest.php` pins that.

    * `allowMultiple` does NOT reach the children that way, because they declare
      a prop of the same name for their own dropdown mode and Laravel resolves
      the child's own data first — the case CONVENTIONS.md §3 bans. **In a
      managed multi-select group, pass `group-allow-multiple` on each child
      filter as well**, or the children will render as `role="radio"`. The two
      values are separate objects in Angular too (`filterGroup.allowMultiple()`
      vs the filter's own `allowMultiple()`), so this keeps them apart rather
      than merging them.

    Every class emitted here exists in dist/tedi.css; nothing is dropped.
--}}
@props([
    /**
     * Multi-select mode allows several filters to be selected at once. When
     * false the group is radio-like, which is what gives each child filter
     * `role="radio"` instead of `aria-pressed` (see filter.blade.php).
     * NOT inherited — mirror it with `group-allow-multiple` on each child.
     */
    'allowMultiple' => false,
    /**
     * Selected value (single-select) or values (multi-select). Informational on
     * the server render — child selection comes from each filter's own
     * wire:model, not from here.
     */
    'value' => null,
    /** Accessible label for the group. Emitted as `aria-label`, as in Angular, managed or not. */
    'label' => null,
    /** Explicit stand-in for Angular's runtime `isManaged` signal; gates the group ARIA. */
    'managed' => false,
    /** Disables every filter in the group; propagates to the children via @aware. */
    'disabled' => false,
])

<div {{ $attributes->class([
    'tedi-filter-group',
])->merge(array_filter([
    'role' => $managed ? ($allowMultiple ? 'group' : 'radiogroup') : null,
    'aria-label' => $label,
])) }}>
    {{ $slot }}
</div>
