# TEDI Design System — Laravel Blade & Livewire

Blade components for the [TEDI Design System](https://github.com/TEDI-Design-System),
in the spirit of [Flux](https://github.com/livewire/flux).

```blade
<tedi:button variant="primary" icon-start="add">Lisa</tedi:button>
<tedi:tag type="danger" closable>Vigane</tedi:tag>
<tedi:alert type="warning">Tähelepanu</tedi:alert>
```

## What this is

TEDI publishes a React package and an Angular package. This one gives Laravel
the same components as **anonymous Blade components**, so they work in plain
Blade and in Livewire without a JS framework. One exception: `tedi:form-field`
is a class component (`Tedi\Livewire\View\Components\FormField`), because it
must build its field context before its slot renders.

Styling is TEDI's own CSS — **not** a Tailwind reimplementation. You can use
Tailwind on top if you want; nothing here depends on it.

### Where the pieces come from

| Layer | Source | Why |
|---|---|---|
| Tokens, base, typography, fonts, icons, utilities | `@tedi-design-system/core` | The canonical style foundation |
| Per-component CSS | `@tedi-design-system/angular` | All its components use `ViewEncapsulation.None`, so the CSS is already global and BEM-classed |
| Markup & prop APIs | `@tedi-design-system/angular` | Templates + `classes()` map cleanly onto Blade |
| Both, for the components that exist only there | `@tedi-design-system/react` | Its CSS Modules hash class names in *React's* build, not in the source — the SCSS is plain BEM and vendors like Angular's |

> Angular remains the port source wherever a component exists in both, because
> that is what the rest of the library was ported from and re-syncing against
> two upstreams is how a port drifts. React is used **additively**, for
> components Angular does not have — see
> [From the React package](#from-the-react-package) below and CONVENTIONS.md §13.
> Note that `core` alone is not sufficient as a source — it contains no component
> styles, only the token/base layer.

## Installation

```bash
composer require aanndryyyy/tedi-design-system-livewire
php artisan vendor:publish --tag=tedi-assets
```

Then in your layout:

```blade
<!DOCTYPE html>
<html class="tedi-theme--default">
<head>
    @tediStyles
</head>
<body>
    ...
    @tediScripts
</body>
</html>
```

`@tediStyles` emits the stylesheet; `@tediScripts` emits the optional Alpine
behaviour bundle (harmless to include — it emits nothing if not published).

### Themes

Set `tedi-theme--default` (light) or `tedi-theme--dark` on `<html>`.

## Syntax

Both forms work and are equivalent:

```blade
<tedi:button variant="primary">Salvesta</tedi:button>     {{-- short, Flux-style --}}
<x-tedi::button variant="primary">Salvesta</x-tedi::button> {{-- standard namespace --}}
```

The short form is enabled by a Blade string-preparation callback that rewrites
`<tedi:…>` to `<x-tedi::…>` before component tags are compiled. (It has to run
at that stage — a normal `Blade::precompiler()` runs *after* component
compilation and would be too late.)

## Livewire

Form components put `{{ $attributes }}` on the native control, so `wire:model`
binds directly:

```blade
<tedi:checkbox wire:model.live="accepted" name="accepted" label="Nõustun" />
<tedi:select wire:model="county" :options="$counties" placeholder="Vali maakond" />
<tedi:text-field wire:model="name" :value="$name" />
<tedi:textarea wire:model="bio" :value="$bio" />
<tedi:number-field wire:model="quantity" :value="$quantity" :min="0" />
<tedi:toggle wire:model.live="enabled" :checked="$enabled" />
<tedi:slider wire:model="volume" :value="$volume" :min="0" :max="100" />
```

Pass `:value` alongside `wire:model` on the text-like controls. Blade renders
once on the server, so the control's initial paint comes from the `value` you
give it — Livewire takes over only from the first client update. For the same
reason the `value` attribute is omitted entirely when empty, rather than
rendered as `value=""`, which would blank a bound field on first paint.

Livewire is **not** a required dependency — the package works in plain Blade.

## Known divergences from the Angular package

These are deliberate and documented in [CONVENTIONS.md](CONVENTIONS.md) §7:

1. **Breakpoint props are not ported.** Angular's `xs`/`sm`/`md`/`lg`/`xl`/`xxl`
   inputs are resolved at runtime in JS by `BreakpointService`, picking the value
   for the currently-matched breakpoint. Server-rendered Blade has no viewport,
   and TEDI ships no breakpoint-variant CSS classes to emit instead. Components
   accept the base props only.
2. **`output()` events are not re-emitted.** Bind `wire:click` / `x-on:click`
   directly to the rendered element instead.
3. **Overlay positioning is this package's own engine, not CDK Overlay.**
   `Alpine.data('tediOverlay')` in `resources/js/src/overlay.js` ports upstream's
   `overlay-position.util.ts`, so tooltip, popover and dropdown anchor the same
   way. The one structural divergence: CDK re-parents its pane into a
   `.cdk-overlay-container` on `<body>`, and Blade renders once on the server,
   so panels stay where they are written. Inside a `transform`ed ancestor a
   fixed/absolute panel is positioned relative to that ancestor
   (CONVENTIONS.md §11). The toast's placement is still the consumer's job.
   The dropdown takes `tediDropdown` instead — `tediOverlay` plus upstream's
   keyboard layer: a roving `tabindex` over the items, Arrow/Home/End, Enter and
   Space activation, ArrowDown/ArrowUp to open, and `tabOutOfDropdown`. Like
   upstream, it has no focus trap; Tab leaves the panel by design.
4. **Runtime DOM introspection becomes explicit props.** Angular's button
   inspects its projected children to decide `--icon-only` / padding modifiers;
   Blade uses `icon-start`, `icon-end` and `icon-only` instead.

### Where each divergence actually bites

Every affected component carries a header comment explaining its own case. The
summary:

| Component | Divergence |
|---|---|
| `card`, `accordion`, `row`/`col`, `link`, `progress-bar`, `text-group`, `header.*`, `footer.*` | Breakpoint props accepted in Angular are absent; base props only |
| `footer`, `footer.body`, `footer.side`, `footer.bottom` | `mobileLayout` not ported, so the `--mobile` variants never apply |
| `header.profile` | Wired to `<tedi:popover>`, but the branch is chosen by `size` server-side, not by the viewport: `size="small"` renders the mobile button + modal, anything else the popover. `showPopover` stays inert |
| `header.role` | Wired to `<tedi:popover>`; the mobile (`*hideAt('lg')`) accordion branch is not ported, so the desktop branch renders at every width. Search filters client-side with `x-show`, so hidden entries keep their DOM position and the item separators follow the written order |
| `header.search` | Angular's breakpoint-driven mobile state becomes the explicit `mobile` prop |
| `pagination` | Always renders the inline branch; `pagination-option-picker-modal` not ported. The `xs`–`xxl` layout overrides (`PaginationBreakpointInputs`) are not ported; `align="between"` emits no `--align-between` (unstyled) |
| `tabs`, `tabs.list` | Overflow "More" dropdown dropped; `dropdownLabel` accepted for API parity but inert |
| `alert` | `closeDelay` accepted for API parity but inert |
| `toast` | Placement/animation, the auto-close timer (hover/focus pause) and `ToastService`'s announcer not ported; `role` and `pauseOnHover` accepted but inert — the toast is never a live region |
| `progress-bar` | `announce` updates need the bundled `tediProgressBar` behaviour; without it the live region stays empty |
| `checkbox`, `radio`, `checkbox-group`, `radio-group` | Managed-group `ControlValueAccessor` not ported — `wire:model` on each child replaces it. The group's `managed` flag is an explicit prop (default `false`, matching Angular) gating `role` / `aria-labelledby` / `aria-label` / `aria-disabled` |
| `checkbox-group`, `radio-group` | `disabled` (and `name`, for radio) reach children via `@aware`. Angular's auto-generated group `name` cannot cross that boundary — pass `name` explicitly |
| `radio-card` | `grouped` and `bordered` inherit from `radio-card-group` via `@aware`, so the nearest explicit value wins; Angular ORs them, so an explicit `:grouped="false"` / `:bordered="false"` on a card overrides a grouped/bordered group |
| `text-field`, `textarea`, `number-field`, `search`, `date-input` | The `value` attribute is emitted only when non-empty; an unconditional `value=""` would blank a `wire:model`-bound field on the initial server render. `slider` is the deliberate exception — a `type="range"` input has no empty state |
| `number-field` | Increment/decrement buttons are inert; attach handlers via `decrement-attributes` / `increment-attributes`. `LiveAnnouncer` and wrapper click-to-focus not ported |
| `slider` | Thumb tooltip not ported — it needs `trackPosition`, the one `<tedi:tooltip>` prop that is itself unported. `tedi-slider--invalid` and `--dragging` dropped |
| `textarea` | Slot content is a Blade extension used when `value` is empty; Angular has no `ng-content` here |
| `form-field` | Both halves of Angular's DI — the field context the control injects and `contentChild(TEDI_FORM_FIELD_CONTROL)` — run through a `FieldContext` object the form-field (a class component) builds before its slot renders; controls find the nearest one through Blade's public `@aware` lookup (`Tedi::fieldContext()`). Only `tedi:text-field` / `tedi:textarea` register a control, so only they earn the clear button. Its state is server-rendered, then kept live by the bundled `tediFormField` behaviour: it follows typing, and `clear()` empties the control, fires `input` and `change` (so `wire:model` follows) and focuses it. Without the bundle the button keeps its render-time state and only `clear-attributes` (e.g. `wire:click`) act on a click; an `x-on:click` in `clear-attributes` runs after `clear()` rather than replacing it. The feedback text's id is not added to the control's `aria-describedby` (pass it yourself). `--tedi-form-field-control-scrollbar` is always `0px`. Click-to-focus on the box padding is not ported. `tedi-form-field--valid` / `--invalid` / `--disabled` dropped — no rule |
| `calendar-header`, `date-picker-header` | `monthYearSelectType` / `monthMode` / `yearMode` keep their `dropdown` default, but only the trigger renders. `<tedi:dropdown>` now exists; these two are simply not wired to it yet |
| `calendar`, `date-picker`, `date-field` | Month/weekday names come from the `date-picker.*` translation keys, not `Intl` (`ext-intl` is not a declared dependency), so `localeCode` is dropped. No narrow weekday key exists, so narrow names fall back to `-short` |
| `date-field`, `time-field` | The panel renders under an explicit `open` prop, anchored with inline styles at upstream's first position — below the field, start-aligned for an input trigger and end-aligned for the button, 4px gap (`FIELD_OVERLAY_GAP`). CDK's flip and fallback positions, re-parenting and `hideOnScroll` are not ported. The modal branch, `useNativePicker`, `fullscreen` and `modal` are not ported |
| `date-field` | Matcher machinery (`disabledMatchers`, `minDate`/`maxDate`, `disablePast`/`disableFuture`, …) collapses to a flat `disabledDays` array; `formatDate`/`parseDate` become the explicit `display` and `tags` props. `size`, `clearable`, `invalid`/`valid`/`disabled` fall back to the nearest `<tedi:form-field>`'s explicitly written props (`Tedi::fieldContext()`), not to Angular's injected field context. A feedback text's id is not added to `aria-describedby` automatically |
| `date-input` | Port-only `size` (the date field's resolved size, handed to the inner text-field) and `iconAttributes` (extra attributes for the calendar icon button) |
| `date-picker` | Deprecated upstream (9.0) in favour of `date-field`; still rendered unchanged |
| `time-picker` | All three `tedi-time-picker--{scroll,slots,dropdown}` classes are unstyled and dropped; `variant` still selects the markup branch. Only `--disabled` and `--bordered` are emitted. The scroll-wheel engine (9.0 item-height re-sync, gesture/scroll-settle guards) is runtime-only and not ported |
| `tag` | `tedi-tag--primary` not emitted — TEDI ships no rule for it; the base `.tedi-tag` block is the primary appearance |
| `select` | Native-`<select>` subset (see table above). `clearable` / `showClearOnInteraction` are declared but inert |
| `search` | Autocomplete mode runs on `tediSearch`. Search never filters: pass the already-filtered `suggestions` on each render (e.g. `wire:model.live`). `searchEvent`/`suggestionSelect` are the DOM events `tedi-search:search` and `tedi-search:select` (detail `{index,label,id,item}`) on the input. Prefer `item` to `index`: the list is re-queried when the field is filled. The custom-row `ng-template` becomes the `rows` slot of `tedi:search-suggestion` rows with `:custom="true"`, and `tediSearchFooter` becomes the `footer` slot. `panelOpen` is the initial state only. `resultsCountText` is a Closure or a `:count` string. `tedi-search__announcement` is dropped (no rule). The panel stays in the document with `z-index: var(--z-index-dropdown)` |
| `ellipsis` | CSS clamp only; no overflow measurement, so no reveal-on-hover tooltip |
| `accordion`, `tabs`, `carousel`, `header.toggle`, `footer.section`, `collapse`, `dropdown`, `popover`, `tooltip`, `modal`, `sidenav`, `button-group`, `table`, `table-of-contents-collapsible`, `truncate`, `search` (autocomplete mode), `form-field` | Inert without the bundled Alpine behaviour; markup and classes are still correct. `form-field` degrades to its server-rendered state: the clear button shows or hides as rendered and does not follow typing |
| `dropdown`, `popover`, `tooltip` | Panels stay where they are written instead of being re-parented into a `.cdk-overlay-container` (divergence 3 above) |
| `dropdown` | `value` does not drive item selection — Angular's items read it through `inject(DROPDOWN_API)`, which `@aware` cannot reproduce. Pass `:selected` on each `<tedi:dropdown-item>`. `container-id` replaces Angular's generated `containerId` and wires the trigger to the content `<ul>`. The trigger's default `aria-haspopup` (derived from `dropdown-role`) and its open-only `aria-controls` are set by `tediDropdown` at runtime. With `interactive-content`, the item's role, ARIA state and tabindex are stamped onto the projected `a[href], button` by Alpine; without JS the control keeps its native semantics |
| `tooltip` | `trackPosition` (rAF repositioning against a moving origin) and the touch handling are not ported. For element triggers, `aria-describedby` is set on the resolved focusable child by Alpine at runtime; the `role="tooltip"` content's id is `description-id`, or an Alpine `$id` when omitted |
| `modal` | Only the `[(open)]` template branch is ported. `ModalService` / `ModalRef` / `MODAL_DATA` and the `ConfigKey`s that exist only for them (`scrollBehavior`, `fullscreen`, `maxWidth`, `closeOnEscape`, `data`) are not, nor is the CDK focus trap. `ModalRef.canClose` becomes a cancelable `tedi-modal-close` event (`detail.reason`: `backdrop` \| `escape` \| `close-button` \| `programmatic`); Alpine's `close()` bypasses it. The heading-derived `aria-labelledby` is set by `tediModal` on open, not server-side. `tedi-modal--bottom` dropped — TEDI ships no rule for it |
| `breadcrumbs` | The ellipsis dropdown is a `dropdown-role="list"` panel. `tedi-breadcrumbs__dropdown-item` is emitted although TEDI no longer ships a rule for it, because upstream documents it as the restyle hook for collapsed crumbs |
| `multiselect` | Deprecated upstream (use TEDI-ready Select `multiple`); upstream removed its stories, so it has no Storybook entry. The deprecation console warning is not ported |
| `table` | Markup layer only, the same spirit as `select`. `@tanstack/angular-table` is not ported, so sorting, filtering, selection and expansion **state** are the consumer's and come back in through `:columns` / `:rows`. With the engine go row virtualisation, drag reorder, column resizing, `state` persistence, the built-in filter popover, the small-viewport filter modal and `filterModalBreakpoint` / `filterModalFullscreen` / `filterPopoverWidth` |
| `carousel` | Content + navigation sit in `.tedi-carousel__body`, which `tedi:carousel-content` renders itself; an `overlay` navigation goes in its `navigation` slot instead of directly inside `tedi:carousel`. The looping clone buffer, wheel scrolling, per-breakpoint `slidesPerView`/`gap` and LiveAnnouncer are not ported |
| `sidenav` | `SideNavService`'s signals become explicit props (`collapsed`, `mobile`, `mobileOpen`, `mobileItemOpen`); the sibling `sidenav.toggle` / `sidenav.overlay` talk to the nav over window events. `desktopBreakpoint` is not declared at all. The drawer `top` comes from `sidenav.toggle` over the `tedi-sidenav:toggle` event and is re-measured on window resize, not with a `ResizeObserver` on the header; `header.toggle` does not supply it |
| `button-group` | `enableMobileDropdown` + `mobileBreakpoint` become the explicit `dropdownMode` prop; the collapsed dropdown is built from `items` only, so a slot-only group cannot collapse |
| `breadcrumbs`, `horizontal-stepper`, `button-group` | Angular's `contentChildren` registration becomes explicit props — an `items` array, or `step-number` on each stepper item |
| `vertical-stepper-item` | Same `contentChildren` case: nested steps go in the `sub-items` slot and each carries `:sub-item="true"`, because Blade cannot set it on the slot's children. `route`/RouterLink becomes a plain `href`, and `routerLinkActive`'s auto-selection (plus the `opened` effect it drives) becomes explicit `:selected` / `:opened`. `tedi-vertical-stepper--compact` dropped — TEDI ships no rule for it |
| `filter` | **No native form control.** `wire:model` binds through `x-modelable="model"` on the root rather than through a hidden `<input>`: the model is `string`, `string[]` or `boolean` depending on the mode, and only Alpine can carry all three. Nothing is submitted in a plain non-Livewire `<form>` POST |
| `filter` | `tedi-filter-dropdown--custom` and `tedi-filter-dropdown__item--select-all` are dropped — Angular emits both, TEDI ships no rule for either |
| `filter` | The `cleared` output is not re-emitted; the custom-content dropdown's clear button forwards `clear-attributes` (e.g. `wire:click`) instead |
| `filter` | `group-allow-multiple` is a Blade-only prop with no Angular input. Angular reads its group's mode from `filterGroup.allowMultiple()` — a different object from the filter's own `allowMultiple()` — to decide `role="radio"` (managed single-select group) versus `aria-pressed` (everything else). `@aware` cannot carry it, because the filter declares `allowMultiple` itself and Laravel resolves the child's own data first (CONVENTIONS.md §3). **Inside a `<tedi:filter-group allow-multiple>`, mirror it as `group-allow-multiple` on each child filter**; omitted, the child assumes a single-select group, matching filter-group's default |
| `filter-group` | The managed-group `ControlValueAccessor` is not ported. `managed` is an explicit prop gating `role="group"` / `role="radiogroup"` — and, on each child, `role="radio"` instead of `aria-pressed`. Selection comes from `wire:model` on each child `<tedi:filter>`, as with `checkbox-group` / `radio-group`. A multi-select group must also mirror `group-allow-multiple` onto each child — see the `filter` row |
| `vertical-spacing`, `vertical-spacing-item` | Angular's attribute directives port as wrapper `<div>`s carrying the class and the inline `--vertical-spacing-internal`. The inline style is *merged* (`$attributes->style()`) where Angular's `setAttribute('style', …)` clobbers. For `vertical-spacing-item` the wrapper takes the margin, so the child's own bottom margin collapses through it and the effective gap is `max(child margin, size)` — apply the class directly where that matters |
| `hide-at`, `show-at` | TEDI ships no display-utility classes, so visibility is an Alpine `matchMedia` binding (`tediBreakpoint`) rather than CSS. Angular's structural forms (`*hideAt` / `*showAt`) that remove the element from the DOM are not ported — only the attribute form's `display` toggle. Without the bundled JS the content is **visible** at every width; Angular hides until its breakpoint observer emits, so the no-JS fallback deliberately errs toward showing content rather than losing it |
| `choicegroup` | Angular is an attribute directive applied to an existing group host; Blade renders a wrapper element instead. `spacing` only ever decides `--stacked` (upstream never writes a gap either) |
| `table-of-contents`, `table-of-contents-item`, `table-of-contents-collapsible` | Controlled by `active-id`, like upstream; the root is an `x-modelable` Alpine scope. Nested items go in the `sub-items` slot and the row suffix in the `suffix` slot (Blade cannot split one slot by type). Level and hierarchical numbers are computed server-side from the rendered slot. With `default-open=false` off-trail groups stay in the DOM, hidden by `x-show`. The collapsible's sheet stays where it is written (fixed, `--z-index-modal`) instead of a CDK Dialog, has no focus trap, and `scroll-container` is a CSS selector. `tedi-table-of-contents__row--level-1`, `tedi-table-of-contents-collapsible` dropped — no rule |
| `floating-button` | Always a `<button>` (no `href`). `placement` / `offset` are arrays. `--horizontal` dropped — no rule |
| `truncate` | `max-length` is a number only (breakpoint object not ported); `[(expanded)]` sets the initial state only. `tedi-truncate__text` dropped — no rule |
| `skeleton`, `skeleton-block` | Breakpoint `xs`–`xxl` override objects not ported. The completed-label announcement goes through a body-level live region in place of CDK `LiveAnnouncer` |
| `print` | Directive ported as a wrapper `<div>`; `visibility` is Angular's `tediPrint`. Cannot cancel a `no-print` on the same element — put `class="show-print"` on that component |
| `file-dropzone` | Markup subset — Angular's `FileService`, `ControlValueAccessor` and async validators are the server's job here (see the table above) |

Five classes are emitted that TEDI ships **no CSS rule** for
(`tedi-empty-state--default`, `tedi-empty-state--separate`,
`tedi-feedback-text--hint`, `tedi-text-group--vertical`,
`tedi-breadcrumbs__dropdown-item`). These are faithful — the Angular components
emit them too, and upstream documents the last one as the hook for restyling
collapsed crumbs — and are allowlisted as `$deadUpstream` in
`tests/IntegrityTest.php`, which fails if an entry ever goes stale.

Everywhere else the guardrail wins and the class is **dropped**: a class with no
rule is DOM noise a consumer may mistake for a hook (CONVENTIONS.md §4). Each
drop is recorded in its component's docblock — among them all three
`tedi-time-picker--<variant>` classes, `tedi-slider--invalid`, the `__label`
class on both group components, and `tedi-tag--primary` (the base `.tedi-tag`
block already *is* the primary appearance).

## Upgrading

### Breaking changes in this sync

**Run `php artisan view:clear` after upgrading.** `tedi:form-field` is now a
class component. Views compiled before the upgrade still render it as an
anonymous component: it renders, but controls inside it do not register, so the
field shows no clear button until the views are recompiled. If you published
`form-field`, `text-field`, `date-input`, `date-field` or `date-time-field`,
re-publish them: `$__tediFieldControl` and `$__tediFieldId` no longer exist (use
`$fieldContext->control` and `$fieldContext->id()`).

The re-sync to Angular 9.0.1-rc.1, React 20.1.0-rc.1 and core 6.12 changes
markup, class names and props. Check these before upgrading:

- **Print.** `button` (and every component built on it), `closing-button`,
  `info-button`, `button-group-button`, `floating-button`, `header`, `footer`
  and `sidenav` now carry core's `no-print` utility.
- **`alert`**: the host no longer has `aria-live` or `aria-label`; name the
  close button with the new `close-aria-label`. Text takes the per-type colour.
- **`toast`**: the inner alert is `role="none"`; the toast is never a live
  region, and its `role` prop is inert.
- **`card`**: the vendored stylesheet dropped `overflow: hidden`.
- **`timeline`**: card-variant item padding is 0.5 (was 1).
- **`sidenav`**: the mobile drawer is a fixed overlay and takes its `top` from
  `sidenav.toggle`.
- **`carousel`**: content and navigation are wrapped in `.tedi-carousel__body`,
  rendered by `tedi:carousel-content`; an `overlay` navigation goes in that
  component's `navigation` slot. Drag, swipe and keyboard are live, and with
  `loop=false` the arrows disable at the ends.
- **`table`**: control columns are 40px (were 60), which shifts
  `stickyFirstColumn` offsets. Control cells lose `--align-center` and gain
  `--control` / `--control-fit`; a labelled expand column loses its 150px width;
  `tedi-table__sr-only` is now `sr-only`.
- **`slider`**: the keep-space label class is `tedi-label--reserve-space`
  (`tedi-slider__label--reserve-space` is gone).
- **`form-field`**: `clearable` defaults to `true`, so the clear button shows
  on a filled text field or textarea. The root is `<tedi-form-field>` with the
  label inside it. The `textarea` prop, `tedi-form-field__input` and
  `--valid` / `--invalid` / `--disabled` are removed. A bare control no longer
  draws a box — use `tedi:text-field`.
- **`textarea`**: is clearable; the 7.5rem default height and the 12-row cap are
  gone.
- **`label-row`**: the root is `<tedi-label-row>`.
- **`feedback-text`**: renders an `id`.
- **`select`**: takes `value` and `name` props. The native `<select>` carries
  `tedi-select__trigger`, so it paints the field surface and fills the width
  below its label. **`pagination`**: the page-size `<select>` is wrapped in
  `tedi-pagination__page-size-select tedi-select`.
- **`number-field`**: label, field and feedback are wrapped in one
  `<tedi-number-field>` root (Angular's host), instead of three sibling roots.
- **`date-field`**, **`time-field`**: an open panel is anchored below the field
  (absolutely positioned) instead of sitting in the flow beside it.
- **`date-field`**: `size` defaults to `null` and inherits from the form field;
  surface classes are on the field; `read-only` keeps the clear button; a
  disabled form field disables the input. **`date-input`**: `read-only` no
  longer hides the clear button. **`time-field`**: `clearable` defaults to
  `null` (inherits); the clear button is hidden when disabled.
  **`date-time-field`**: the popup ARIA moves to the icon button, inside a
  surface shell. **`time-picker`**: the slots variant is laid out inline with
  `display: grid`.
- **`search`**: `tedi-search__input--has-button` is replaced by
  `tedi-search--has-button` on the root; `input-class` is gone. Events are
  `tedi-search:search` (`{value}`) and `tedi-search:select`
  (`{index,label,id,item}`).
- **`dropdown-item-value`**, `-label`, `-meta` are renamed `option-content`,
  `-label`, `-meta` (tags and classes; no alias).
- **`dropdown-trigger`**: `aria-haspopup` follows the content's role; the `id`
  moves to the `<ul>`, which gets `aria-labelledby`; `aria-controls` is present
  only while open; a disabled item swallows clicks.
- **`breadcrumbs`**: the ellipsis dropdown is a list (no `menuitem`), and
  crumbs are underlined.
- **`tooltip`**, **`info-tooltip`**: the `description` prop is removed;
  `aria-describedby` is set on the trigger's child by Alpine.
- **`modal`**: closing can be cancelled through `tedi-modal-close`;
  `aria-label` / `aria-labelledby` are on the inner dialog.
- **`floating-button`**: `position` defaults to `fixed` (was inline); new
  `placement`, `offset` and `z-index` props.
- **`heading-with-icon`**: `name` is now `icon`; the root is a
  `<tedi-heading-with-icon>` wrapper with `__icon` / `__heading` children; new
  `icon-variant`.
- **`truncate`**: `content` is now `text`; `more-label` / `less-label` and the
  slot are removed; the root is `<tedi-truncate>`; text is cut only when
  strictly longer than `max-length`, counted in graphemes; new `expanded`.
- **`skeleton`**: the root is `<tedi-skeleton aria-busy>`, with a polite empty
  region filled after `label-delay`; new `completed-label`. `skeleton-block`'s
  root is `<tedi-skeleton-block aria-hidden>`, and `width` auto writes no style.
- **`table-of-contents`**: the API and markup are replaced. Classes are
  `tedi-table-of-contents*`; `position`, `modal-breakpoint`, `scroll-aware`
  and `scroll-on-click` are removed; the consumer owns `active-id` (no scroll
  spy); `default-open` changes meaning and defaults to `true`; the mobile
  variant is the new `tedi:table-of-contents-collapsible`. On items, `id-to` is
  now `item-id`, `selected` is removed, there is no anchor, and rows carry
  `data-toc-item-id`.
- **`top-nav-item`**: no `aria-haspopup`. **`top-nav-separator`**: the
  `separator` role is on the inner span.
- **`file-upload`**: `accept` renders a hint plus `aria-describedby`; the column
  class is `flex`; `max-size` is in bytes.
- **`multi-value-field`**: the clear button carries
  `tedi-multi-value-field__clear-button`.
- **Storybook**: the Checkbox and Radio card story ids are renamed; Collapse
  and EmptyState move to `TEDI-Ready/Content/`; FloatingButton and
  TableOfContents move to `TEDI-Ready/Components/…`; the multiselect stories
  are gone.

## What's implemented

The first phase ported the **template-only** components: those whose rendered
markup is a pure function of their inputs (no `ControlValueAccessor`, no CDK
Overlay positioning, no open/close state, no DOM event handling).

The second phase added the **form-value-binding** components — the ones whose
Angular implementation is a `ControlValueAccessor`. Livewire makes that binding
the framework's job rather than the component's: `wire:model` goes on the native
control, so `{{ $attributes }}` sits on the `<input>` / `<textarea>` / `<select>`
rather than on the wrapper (CONVENTIONS.md §6). The calendar and date/time
components came with it, as documented subsets wherever they need an overlay.

The third phase added the **stateful and overlay-anchored** components — the
ones Angular builds on CDK Overlay, CDK Dialog or an injectable service. They
run on this package's own Alpine layer instead (`tediOverlay`, `tediDropdown`,
`tediModal`, and per-component inline state), documented in CONVENTIONS.md §8
and §11.

Later passes added the components that exist only in Angular's `community/`
entry point (see
[From Angular's `community/` entry point](#from-angulars-community-entry-point)
below), `filter`, the `tedi/directives/`, and the components that exist only in
React. As of Angular 9.0.1-rc.1 that covers every directory under
`src/tedi/components/<group>/` (73), plus `filter` and the four directives:
**155 Blade components** once sub-components are counted. With 5 from
`community/` and 24 from React, that is **184** in total.

| Angular component | Blade tag |
|---|---|
| `base/icon` | `<tedi:icon>` |
| `base/text` | `<tedi:text>` |
| `buttons/button` | `<tedi:button>` |
| `buttons/button-group` | `<tedi:button-group>`, `<tedi:button-group-button>` |
| `buttons/card-button` | `<tedi:card-button>` |
| `buttons/closing-button` | `<tedi:closing-button>` |
| `buttons/collapse-button` | `<tedi:collapse-button>` |
| `buttons/info-button` | `<tedi:info-button>` |
| `content/accordion` | `<tedi:accordion>`, `<tedi:accordion-item>`, `<tedi:accordion-item-header>`, `<tedi:accordion-item-content>` |
| `content/card` | `<tedi:card>`, `<tedi:card-header>`, `<tedi:card-content>`, `<tedi:card-icon>`, `<tedi:card-row>` |
| `content/carousel` | `<tedi:carousel>` + `-header`, `-content`, `-slide`, `-indicators`, `-navigation`, `-footer` |
| `content/calendar` | `<tedi:calendar>`, `<tedi:calendar-header>`, `<tedi:calendar-day-grid>`, `<tedi:calendar-month-grid>`, `<tedi:calendar-year-grid>` |
| `content/collapse` | `<tedi:collapse>` |
| `content/empty-state` | `<tedi:empty-state>` |
| `content/label` | `<tedi:form.label>` ¹ |
| `content/list` | `<tedi:list>` |
| `content/table` | `<tedi:table>`, `<tedi:table-toolbar>`, `<tedi:table-header-button>`, `<tedi:table-columns-menu>` ⁵ |
| `content/text-group` | `<tedi:text-group>`, `<tedi:text-group-label>`, `<tedi:text-group-value>` |
| `filter/filter` | `<tedi:filter>`, `<tedi:filter-group>` |
| `form/checkbox` | `<tedi:checkbox>` |
| `form/checkbox-card` | `<tedi:checkbox-card>`, `<tedi:checkbox-card-group>` |
| `form/checkbox-group` | `<tedi:checkbox-group>` |
| `form/date-field` | `<tedi:date-field>`, `<tedi:date-input>` ⁴ |
| `form/date-picker` | `<tedi:date-picker>` + `-header`, `-calendar-grid`, `-month-grid`, `-year-grid` |
| `form/feedback-text` | `<tedi:feedback-text>` |
| `form/form-field` | `<tedi:form-field>` |
| `form/input-group` | `<tedi:input-group>` |
| `form/label-row` | `<tedi:label-row>` |
| `form/number-field` | `<tedi:number-field>` |
| `form/radio` | `<tedi:radio>` |
| `form/radio-card` | `<tedi:radio-card>`, `<tedi:radio-card-group>` |
| `form/radio-group` | `<tedi:radio-group>` |
| `form/search` | `<tedi:search>`, `<tedi:search-suggestion>`, `<tedi:search-footer-actions>` |
| `form/slider` | `<tedi:slider>` |
| `form/text-field` | `<tedi:text-field>` |
| `form/textarea` | `<tedi:textarea>` |
| `form/time-field` | `<tedi:time-field>` ⁴ |
| `form/time-picker` | `<tedi:time-picker>` |
| `form/toggle` | `<tedi:toggle>` |
| `helpers/attachment` | `<tedi:attachment>` |
| `helpers/grid` | `<tedi:row>`, `<tedi:col>` ² |
| `helpers/option-content` | `<tedi:option-content>`, `<tedi:option-content-label>`, `<tedi:option-content-meta>` |
| `helpers/separator` | `<tedi:separator>` |
| `helpers/timeline` | `<tedi:timeline>`, `<tedi:timeline-item>` |
| `layout/footer` | `<tedi:footer>` + `.body`, `.section`, `.side`, `.bottom` |
| `layout/header` | `<tedi:header>` + `.top`, `.bottom`, `.content`, `.logo`, `.login`, `.logout`, `.profile`, `.role`, `.search`, `.language`, `.actions`, `.toggle`, `.mobile-button` |
| `layout/sidenav` | `<tedi:sidenav>` + `.item`, `.toggle`, `.overlay`, `.group-title`, `.dropdown`, `.dropdown-group`, `.dropdown-item` |
| `loader/progress-bar` | `<tedi:progress-bar>` |
| `loader/spinner` | `<tedi:spinner>` |
| `navigation/breadcrumbs` | `<tedi:breadcrumbs>` |
| `navigation/horizontal-stepper` | `<tedi:horizontal-stepper>`, `<tedi:horizontal-stepper-item>` |
| `navigation/link` | `<tedi:link>` |
| `navigation/pagination` | `<tedi:pagination>` |
| `navigation/tabs` | `<tedi:tabs>`, `<tedi:tabs.list>`, `<tedi:tabs.trigger>`, `<tedi:tabs.content>` |
| `notifications/alert` | `<tedi:alert>` |
| `notifications/toast` | `<tedi:toast>` ³ |
| `overlay/dropdown` | `<tedi:dropdown>` + `-trigger`, `-content`, `-item` |
| `overlay/info-tooltip` | `<tedi:info-tooltip>` |
| `overlay/modal` | `<tedi:modal>`, `<tedi:modal-header>`, `<tedi:modal-content>`, `<tedi:modal-footer>` |
| `overlay/popover` | `<tedi:popover>`, `<tedi:popover-trigger>`, `<tedi:popover-content>` |
| `overlay/tooltip` | `<tedi:tooltip>`, `<tedi:tooltip-trigger>`, `<tedi:tooltip-content>` |
| `tags/status-badge` | `<tedi:status-badge>` |
| `tags/status-indicator` | `<tedi:status-indicator>` |
| `tags/tag` | `<tedi:tag>` |

Angular's `src/tedi/directives/` tree sits beside `components/` rather than
inside it. All four port, as wrapper elements — Blade has no directives, so this
follows the `choicegroup` precedent (CONVENTIONS.md §12). The fourth, `print`,
is listed under [Promoted to Angular `tedi/`](#promoted-to-angular-tedi) below:

| Angular directive | Blade tag |
|---|---|
| `directives/hide-at` | `<tedi:hide-at>` |
| `directives/show-at` | `<tedi:show-at>` |
| `directives/vertical-spacing` | `<tedi:vertical-spacing>`, `<tedi:vertical-spacing-item>` |

¹ Kept under `form/` rather than flattened, because `label` is the most generic
name in the library and a future collision is likely. Angular has since moved
the component to `content/label`; the Blade tag stays `<tedi:form.label>`.
² Named after the CSS classes it emits (`.tedi-row` / `.tedi-col`) rather than
after the Angular directory.
³ Visual markup only — overlay placement is out of scope (see divergences).
⁴ Angular's `date-field-modal` and `time-picker-modal` are still not ported.
`<tedi:modal>` now exists, but their styles were inline in the Angular decorator
rather than vendored, so no rule for them exists in `dist/tedi.css`. Both fields
always take the popover branch.
⁵ The markup layer only — the TanStack table engine is not ported. Sorting,
filtering, selection and expansion state are the consumer's and are rendered
back in through `:columns` / `:rows` (see the divergences table above).

### Shipped as a documented subset

| Component | What you get | What's missing |
|---|---|---|
| `<tedi:select>` | Native `<select>` styled with `tedi-input` | Searchable / multi-select custom combobox (needs CDK Overlay) |
| `<tedi:ellipsis>` | CSS line-clamped truncation | Reveal-on-hover tooltip (needs `ResizeObserver` measurement) |
| `<tedi:scroll-fade>` | Static markup | Scroll-driven fade state |
| `<tedi:slider>` | Full track, thumb, labels and progress fill | Thumb tooltip (needs CDK Overlay); `--dragging` drag state |
| `<tedi:table>` | The full markup: toolbar, sortable headers, control columns, expandable and clickable rows, column menu | The `@tanstack/angular-table` engine — sorting/filtering/selection/expansion state, virtualisation, drag reorder, column resizing, persistence, the filter popover |
| `<tedi:calendar-header>`, `<tedi:date-picker-header>` | The month/year trigger buttons | The dropdown panel itself — `<tedi:dropdown>` exists but these are not wired to it yet |
| `<tedi:time-picker>` | All three variants' markup | The variant host classes, which TEDI ships no rules for |
| `<tedi:date-field>`, `<tedi:time-field>` | Input, tags, trigger, and the panel under an `open` prop, anchored below the field | Open/close behaviour, CDK's flip/fallback placement, and the mobile modal branch |
| `<tedi:number-field>` | Full markup with correct disabled states | Button behaviour — wire it via `increment-attributes` / `decrement-attributes` |
| `<tedi:file-dropzone>` | Full markup, a native `<input type="file">` that `wire:model` binds, drag-and-drop into that input, and the file list / states rendered from `:files`, `state`, `has-error`, `error` | Angular's client-side file pipeline: `FileService` (append/replace, duplicate renaming), the `ControlValueAccessor`, and the `validators` / `validateIndividually` async validation that wrote `uploadState` |
| `<tedi:multiselect>` | The full combobox: trigger, selected-value tags, clear button, and an anchored listbox panel with select-all and selectable groups | Per-option custom content, and pointing the panel's width at an arbitrary element (`dropdownWidthRef`) — it is the trigger's width or `auto` |
| `<tedi:table-of-contents>`, `<tedi:table-of-contents-collapsible>` | Every input, both variants, sticky geometry, numbering, the active trail, `scroll-active-into-view`, the bottom bar + sheet with Escape/backdrop/link close and `hide-on-scroll` | The CDK Dialog (sheet rendered in place, fixed) and its focus trap |

### From Angular's `community/` entry point

Angular ships a second entry point, `@tedi-design-system/angular/community`, next
to `tedi/`. Its components are built the same way — `ViewEncapsulation.None`,
global BEM classes — so they port under the same rules; CONVENTIONS.md §12
covers what differs (group mapping and the `Community/…` Storybook prefix).

The four components that exist **only** there are ported:

| Angular component | Blade tag |
|---|---|
| `community/form/choicegroup` | `<tedi:choicegroup>` ⁶ |
| `community/form/file-dropzone` | `<tedi:file-dropzone>` ⁷ |
| `community/form/select/multiselect` | `<tedi:multiselect>` ⁷ ⁸ |
| `community/navigation/vertical-stepper` | `<tedi:vertical-stepper>`, `<tedi:vertical-stepper-item>` |

⁶ Angular is an attribute directive with no template; Blade has no directives,
so it ports as a wrapper element carrying the same four host classes. It is the
community predecessor of `tedi/`'s `<tedi:radio-card>` / `<tedi:checkbox-card>`,
which is what TEDI-Ready ships — prefer those in new code.
⁷ Documented subsets — see the table above.
⁸ Deprecated upstream in favour of TEDI-Ready Select with `multiple`, but
`tedi/`'s `select` is ported here as a native-`<select>` subset, so a
tag-rendering multi-select combobox still has no other home in this package. It was buildable only once §11's anchoring engine existed — the
"needs CDK Overlay" rationale in CONVENTIONS.md §7 item 4 is stale for anything
that just needs a positioned panel.

Everything else in `community/` duplicates a `tedi/` component under an older
name (`accordion`, `card`, `checkbox`, `radio`, `select`, `modal`, `dropdown`,
`dropdown-item`, `tabs`, `tag`, `status-badge`, `breadcrumbs`, `pagination`,
`progress-bar`, `search`, `textarea`, `form-field`, `input-group`) or, since
Angular 9, a component `tedi/` now ships itself (`floating-button`,
`table-of-contents`), and is covered by the `tedi/` port. Three community-only
components are still **not ported**: `input` (superseded by
`<tedi:text-field>`), `table-styles` (a styling wrapper superseded by
`<tedi:table>`) and `text-editor` (a wrapper around the `ngx-quill` / Quill
editor, a client-side dependency this package does not carry).

### Promoted to Angular `tedi/`

These components used to come from Angular's `community/` entry point or from
React. Angular 9 ships them in `tedi/`, so they are now ported from there, and
each changed API in the process (see
[Breaking changes in this sync](#breaking-changes-in-this-sync)):

| Angular component | Blade tag | Was from |
|---|---|---|
| `buttons/floating-button` | `<tedi:floating-button>` | `community/` |
| `navigation/table-of-contents` | `<tedi:table-of-contents>`, `<tedi:table-of-contents-item>`, `<tedi:table-of-contents-collapsible>` ⁷ | `community/` |
| `loader/skeleton` | `<tedi:skeleton>`, `<tedi:skeleton-block>` | React |
| `content/heading-with-icon` | `<tedi:heading-with-icon>` | React |
| `content/truncate` | `<tedi:truncate>` | React |
| `directives/print` | `<tedi:print>` ⁹ | React |

⁹ Emits core's own print utilities (`no-print`, `show-print`, `break-before-*`,
`break-after-*`, `break-inside-*`), which are not `tedi-`-prefixed. That is
core's naming.

### From the React package

`@tedi-design-system/react` is a **third, additive source**. The rule that kept
it out — CSS Modules hashing its class names — turns out to hold for the built
dist and not for the source: `section.module.scss` declares a literal
`.tedi-section`, and the TSX looks it up by that name. So a `*.module.scss`
vendors into `resources/scss/` exactly like an Angular `*.component.scss`, and
everything in CONVENTIONS.md §§2–11 applies unchanged. §13 covers the seven things
that differ, of which the important one is the new feasibility gate: because
these styles were never part of the Angular build, **every `var(--…)` they use
has to be checked against the installed `@tedi-design-system/core`** before the
component can be ported at all.

Twelve React-only components are ported:

| React component | Blade tag |
|---|---|
| `content/section` | `<tedi:section>` |
| `misc/stretch-content` | `<tedi:stretch-content>` |
| `misc/affix` | `<tedi:affix>` ⁸ |
| `misc/scroll-visibility` | `<tedi:scroll-visibility>` |
| `navigation/hash-trigger` | `<tedi:hash-trigger>` |
| `layout/top-nav` | `<tedi:top-nav>`, `<tedi:top-nav-item>`, `<tedi:top-nav-submenu>`, `<tedi:top-nav-group>`, `<tedi:top-nav-subitem>`, `<tedi:top-nav-separator>` ⁸ |
| `form/file-upload` | `<tedi:file-upload>` ⁸ |
| `form/multi-value-field` | `<tedi:multi-value-field>` ⁸ ¹⁰ |
| `form/date-time-field` | `<tedi:date-time-field>` ⁸ |
| `overlays/sheet` | `<tedi:sheet>`, `<tedi:sheet-trigger>`, `<tedi:sheet-content>`, `<tedi:sheet-header>`, `<tedi:sheet-body>`, `<tedi:sheet-footer>`, `<tedi:sheet-closer>` ⁸ |
| `navigation/card-stepper` | `<tedi:card-stepper>` ⁸ |
| `content/table-card` | `<tedi:table-card>`, `<tedi:text-group-list>` ⁸ ¹¹ |

⁸ Documented subsets — see the table below.
¹⁰ React ships no stories for it, so it has no Storybook entry — Blast derives
components from story directories, and writing stories upstream does not have
would be inventing documentation.
¹¹ `<tedi:text-group-list>` (React's `TextGroupList`) exists for the table
card's body and its child row-groups. Its stylesheet collides with the Angular
text-group, so it is loaded scoped under `.tedi-table-card` (CONVENTIONS.md
§13.7) and the list is styled only inside a table card. React's standalone
`TextGroup` stories are not ported for that reason.

Two React-only components are **not** ported. `buttons/button-content` is an
internal primitive with no standalone export. `layout/mobile-nav` reuses
`tedi-sidenav--mobile` and adds only `tedi-mobile-nav-toggle`, whose job
`<tedi:sidenav.toggle>` already does. (React's `misc/option-content`
now has an Angular counterpart, `helpers/option-content`, which is what
`<tedi:option-content>` ports.) React's `community/` tree is skipped
wholesale: it is the older generation Angular's `community/` also carries, and
the map-component suite in it is a domain-specific application, not a design
system.

| Component | What you get | What's missing |
|---|---|---|
| `<tedi:affix>` | Both positions, the full offset scale, and `position: sticky` written inline | `react-sticky-box`'s container measurement, and the `relative` prop that adds the rendered header height to the offset |
| `<tedi:top-nav>` | The whole desktop bar: items, separator, both submenu fits, the open/close and dismissal behaviour | The mobile drawer. Upstream rebuilds it by walking its children with `Children.map` and re-emitting them as sidenav data — pair `<tedi:sidenav>` with `<tedi:hide-at>` / `<tedi:show-at>` instead |
| `<tedi:file-upload>` | The full markup, a native `<input type="file">` that `wire:model` binds, the file list rendered from `:files`, and upstream's accept / max-size hint (`max-size` in bytes, `show-restrictions`) | `useFileUpload` — the client-side list, `maxSize`/`accept` validation, `keepRejectedFiles`, and the announcements derived from them. Also the below-`md` clear-button swap |
| `<tedi:multi-value-field>` | Tags, overflow counter, clear control, icon trigger, and the JSON hidden input | The ResizeObserver that measures how many tags fit — pass `:visible-count` |
| `<tedi:date-time-field>` | All four panel layouts (range, side-by-side, and both multi-step steps), composed from the existing calendar and time picker | Popup placement and the `datetime-local` native branch — it inherits `<tedi:date-field>`'s subset exactly. `clearable` ported; popup ARIA on the icon button; the date-input is wrapped in a `tedi-date-field tedi-field-surface` shell, the only vendored rule that draws its border. Not ported: `labelProps`, `minYear`/`maxYear` |
| `<tedi:sheet>` | Open/close, dismissal, focus cycling and return, scroll lock, collapse, drag and snap points, header/footer layouts | Unmounting while closed (always behaves as `keepMounted`), the white closing-button variant (the brand header uses `neutral-inverted`), `initialFocus`, `closeButtonProps`, breakpoint `radius` |
| `<tedi:card-stepper>` | The card, counter, progress, navigation and the step list (in `<tedi:sheet>`) | The `CardStepper.Step` children API (pass `:steps`); uncontrolled navigation — the active step is server state, changes arrive as `tedi:step-change` |
| `<tedi:table-card>` | Every layout, alignment, column and colSpan option, the header (title, subtitle, end slot, selection checkbox), collapse (Alpine), summary row, actions footer and child row-groups. The card is Angular's `<tedi:card>`; React's separator is restated as one scoped rule | Breakpoint props (`md={{ columns: 2 }}`); `onOpenChange` / `onSelectedChange` (controlled `open` / `selected` only set the initial state); small labels keep the body line height (Angular's label has no small line height) |

### Not ported

Within `tedi/`, every component is ported; what remains are the documented
subsets above plus four places where a now-available primitive has not been
wired up yet:

| Component | Would gain |
|---|---|
| `calendar-header`, `date-picker-header` | Their real month/year pickers, from `<tedi:dropdown>` |
| `date-field`, `time-field`, `pagination` | Their overlay / modal branches |
| `tabs.list` | The overflow "More" dropdown behind the inert `dropdownLabel` |
| `slider` | Its thumb tooltip, once `<tedi:tooltip>` gains `trackPosition` |
| `toast` | Its `tedi-toast-container`. Angular's `ToastService` spawns toasts into a CDK-positioned container; `<tedi:toast>` renders one toast's markup and leaves placement to the consumer |

## Storybook

The components are documented in a real Storybook, via
[area17/blast](https://github.com/area17/blast). It lives in `storybook/` — a
small Laravel app that installs this package through a Composer path repository,
which is the topology Blast is built for.

```bash
npm install && npm run build        # build dist/tedi.css, which the stories render against

cd storybook
composer install
cp .env.example .env && php artisan key:generate
composer start                      # serves the app + launches Storybook
```

Then open <http://localhost:6006>. The first run installs Storybook's npm
dependencies inside `vendor/area17/blast`, so it takes a few minutes; later runs
start immediately.

`composer start` runs `start.sh`, which does three things: publishes `dist/` to
`storybook/public/vendor/tedi`, serves the host app on the `APP_URL` from `.env`,
and runs `php artisan blast:launch`. Both processes are needed — Storybook Server
renders each story by making an HTTP request back to the Laravel app.

### Structure

The sidebar mirrors the Angular Storybook one-for-one: the directory a story
lives in *is* its Storybook title, so
`storybook/resources/views/stories/TEDI-Ready/Components/Buttons/Button/full-width.blade.php`
becomes the story *Full Width* under *TEDI-Ready/Components/Buttons/Button* —
the same title the Angular `button.stories.ts` declares. Each Angular story
export is one Blade file. `storybook/CONTRACT.md` documents the authoring rules.

### Docs pages

Every component also has a **Docs** page (`…--docs`), the Blade equivalent of the
Angular Storybook's autodocs. Blast tags a component with `autodocs` as soon as
its story directory contains any `.md` file:

| File | Becomes |
|---|---|
| `<component-dir>/README.md` | the component description at the top of the Docs page |
| `<component-dir>/<story>.md` | that story's description, under its heading |

Both are ported verbatim from the Angular story file — its `parameters.docs.description.component` /
`.story` if present, otherwise the doc comment above the `export default` / `export const`.
All 84 components have a `README.md`; 173 stories have a per-story description,
which is every Angular story that carries one.

Two known gaps. `Community/Form/FormField` has no description because the Angular
story has none — its `README.md` is an HTML comment that renders nothing and
exists only to switch the Docs page on. And the props table on a Docs page is
Storybook's, built from the component's *first* story; because that ordering is
alphabetical (above) rather than Angular's export order, 23 of the 66 pages
audited when the stories were first ported land
on a story that declares no `argTypes` and show "No inputs found for this
component" instead of the table. The per-story Controls panel is unaffected.

Stories are regenerated from the Blade files by `php artisan blast:generate-stories`;
`blast:launch` also runs a watcher that does it on save. Don't run the command
by hand while `blast:launch` is up — the watcher runs it too, and two concurrent
runs corrupt the generated `.stories.json`.

Component titles, story names and the group hierarchy match Angular exactly.
One thing does not: Storybook lists the stories **inside** a component
alphabetically rather than in Angular's export order. Each story still carries
its Angular export position in the directive's `order` key, so the sequence is
recorded and applies wherever Storybook honours it.

Every Angular `TEDI-Ready` group now has a Blade counterpart, including
`TEDI-Ready/Components/Overlay/*`. Under `Community/`, the community-only
components that have upstream stories are present — `Form/FileDropzone` and
`Navigation/VerticalStepper` — plus `Form/FormField`, which is where Angular
files the shared form-field stories. `multiselect` has none, because upstream
removed its stories when it deprecated the component; `floating-button` and
`table-of-contents` moved to `TEDI-Ready/Components/` with their promotion. `<tedi:choicegroup>` has no directory
because Angular has no story file for it: it is a directive, exercised through
the community `radio` / `checkbox` stories, which belong to components the
`tedi/` port already covers.

### Angular stories with no Blade equivalent

638 stories across 84 components are ported. Some Angular stories exist purely
to demonstrate behaviour this package documents as not ported (see the
divergences table above). Those are deliberately absent rather than faked:

| Skipped Angular story | Why |
|---|---|
| `Checkbox`: VerticalTree, WithReactiveForms · `Radio`: WithReactiveForms | Managed-group `ControlValueAccessor` / reactive forms — use `wire:model` instead. VerticalTree also needs the JS-only indeterminate state |
| `Checkbox`: States — the Indeterminate row | `indeterminate` has no HTML attribute; it is set only from JS |
| `Select`: ValueType, EllipsisTags, Examples, Tooltip, ReactiveForms, CustomSearchFunction, Outputs, VirtualScroll, OpenForVisualTest, ClearButtonOnInteraction | The searchable/multi-select combobox; the port is the native-`<select>` subset |
| `Ellipsis`: NoTooltip | Needs the `ResizeObserver` overflow measurement the CSS-clamp subset does not do |
| `Toast`: Positions, HoverBehavior, CustomTimerForAutoclose, PersistentToast, WCAGCompliance | Overlay placement, the JS auto-close timer and `ToastService`'s announcer |
| `Toast` Docs page: the "Usage" section | Documents Angular's `ToastService`, which spawns toasts into a CDK Overlay container. `<tedi:toast>` renders markup only and leaves placement to the consumer, so the section is replaced by a note saying so |
| `Tabs`: OverflowBehavior, WithSubTabs · `Pagination`: ResponsiveVisibility, ShowAll | The overflow "More" dropdown and the option-picker modal — both primitives now exist, but `tabs.list` and `pagination` are not wired to them |
| `Card`: BreakpointProps · `TextGroup`, `ProgressBar`: Responsive · `Pagination`: ResponsiveAlign | Breakpoint props |
| `Skeleton`: Responsive, Accessibility · `Truncate`: ResponsiveMaxLength | Breakpoint props; Accessibility is a playground that mounts/unmounts skeletons on a timer |
| `ProgressBar`: Animated, Announced · `InputGroup`: StartDynamic, EndDynamic, AllControls · `Attachment`: LabeledActions | Runtime state / `output()` events |
| `Header`: LoggedInWithSidenav | Composes `header` and `sidenav`, which are ported separately; the combined story is not |
| `Card`: WithDottedSeparator, PrescriptionExample | A style this port doesn't emit |
| `DatePicker`, `TimePicker`, `TimeField`, `DateField`, `Filter`: WithReactiveForms · `TextField`, `Textarea`: WithTemplateDrivenForms, WithReactiveForms | Angular reactive / template-driven forms — use `wire:model` instead |
| `Search`: AutocompleteAsync, WithReactiveForms | The debounce and loading transition is consumer runtime (a Livewire round-trip); reactive forms: use `wire:model` |
| `DateField`, `TimeField`: NativePicker, MobileModal · `DateField`: CustomFormatAndParse, CustomLocale | Breakpoint props, the unported modal branch, JS format/parse callables, and `localeCode` |
| `FileDropzone`: Replace | Exercises the `mode` input, part of the unported `FileService` file pipeline |
| `DateField`, `TimeField`: OpenForVisualTest | Hidden upstream (`!dev`), visual-regression only, driven by a play function |
| `DateField`: Range — the "min/max limits" field | Open-ended `minDate`/`maxDate` cannot be expressed as the flat `disabledDays` list |

Angular's Hover / Active / Focus matrix rows are reproduced in full.
`storybook-addon-pseudo-states` isn't one of Blast's dependencies, so
`storybook/start.sh` installs it on top of them and `.storybook/main.js` loads it
from there; a matrix story opts in with a `pseudoStates` arg. See
`storybook/CONTRACT.md` §5.

### React stories

The React-sourced components' stories are taken from `*.stories.tsx` under the
same rule, with one mechanical exception: **React's own titles are inconsistently
cased** (`TEDI-Ready/…`, `Tedi-Ready/…`, `Tedi-ready/…`). Left alone they would be
three sidebar roots, and on a case-insensitive filesystem the directories collide
outright, so the case is normalised to `TEDI-Ready` and nothing else is changed.
Two of them open groups beside `Components/` — `TEDI-Ready/Content/Section` and
`TEDI-Ready/Layout/TopNav` — which is upstream's structure, kept.

These React stories have no Blade equivalent:

| Story | Why |
|---|---|
| `HashTrigger`: TabsWithHashTrigger | Demonstrates the Tabs component selecting a tab from the URL hash, which this port's tabs do not do — `<tedi:hash-trigger>` emits a `tedi:hash-match` event to wire yourself |
| `TopNav`: ControlledMobile | Forces the unported mobile drawer |
| `FileUpload`: MultipleHandled, ControlledClearing | React state demos of `onChange` / `files` round-tripping |
| `FileUpload`: SizeLimited, ExtensionAndSizeLimit | Exist to show the unported client-side validation rejecting a file |
| `DateTimeField`: Native | The `datetime-local` branch, chosen at runtime by breakpoint |
| `TableCard`: WithResponsiveLayout | Switches `columns` with the `md` breakpoint prop, which is not ported |
| `DateTimeField`: Size, States, FieldOptions, PerDayTimeSlots, RangePredefinedTimeSlots, Controlled, YearGrid | Runtime selection state — each drives the picker through props this port hands to the consumer instead |
| `Sheet`: OpenForVisualTest, OpenBrandForVisualTest, OpenSmallForVisualTest, OpenWithoutHandleForVisualTest, CollapsedForVisualTest, OpenSnapPointForVisualTest, OpenScrollableForVisualTest | Tagged `!dev`/`!autodocs` — Chromatic snapshot fixtures, not in upstream's Storybook sidebar |
| `CardStepper`: Interactive | The `CardStepper.Step` children API driven by React state |

## Development

```bash
npm run build     # compile dist/tedi.css (+ js, fonts)
npm run watch     # recompile CSS on change
composer test     # class-parity test suite
```

The component SCSS under `resources/scss/components/**` is a **verbatim vendored
copy** from the Angular repo so it can be re-synced on TEDI releases — don't edit
it. Divergences belong in the Blade templates. Files ending `.module.scss` are the
same thing vendored from the React repo, for the components that exist only there
(CONVENTIONS.md §13); the suffix is kept as the provenance marker.

Porting rules live in [CONVENTIONS.md](CONVENTIONS.md). Read it before adding a
component.

### Upstream baseline

The port is in sync with these upstream commits. The next re-sync diffs from
here (`git -C .reference/angular diff <sha> origin/rc -- src/tedi src/community`):

| Repo | Version | Commit |
|---|---|---|
| `TEDI-Design-System/angular` (`rc`) | 9.0.1-rc.1 | `d86bf8c7` |
| `TEDI-Design-System/react` (`rc`) | 20.1.0-rc.1 | `e2c22c3c` |
| `TEDI-Design-System/core` (`rc`) | 6.12.0 | `452b66a9` |

`.reference/` holds plain clones of the three repos and is git-ignored. The
Angular block of `lang/*/tedi.php` is regenerated from
`src/tedi/services/translation/translations.ts` with
`node scripts/generate-lang.cjs`; the React block at the foot of each file is
left alone.

## License

MIT
