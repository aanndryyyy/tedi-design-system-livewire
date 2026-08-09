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
Blade and in Livewire without a JS framework.

Styling is TEDI's own CSS — **not** a Tailwind reimplementation. You can use
Tailwind on top if you want; nothing here depends on it.

### Where the pieces come from

| Layer | Source | Why |
|---|---|---|
| Tokens, base, typography, fonts, icons, utilities | `@tedi-design-system/core` | The canonical style foundation |
| Per-component CSS | `@tedi-design-system/angular` | All its components use `ViewEncapsulation.None`, so the CSS is already global and BEM-classed |
| Markup & prop APIs | `@tedi-design-system/angular` | Templates + `classes()` map cleanly onto Blade |

> The React package is deliberately **not** the port source: it uses CSS Modules,
> so its class names are hashed at build time and can't be reproduced in Blade.
> Note that `core` alone is not sufficient either — it contains no component
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
   `Alpine.data('tediOverlay')` in `resources/js/tedi.js` ports upstream's
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
| `header.profile`, `header.role` | Always render the modal branch; not yet wired to `<tedi:popover>` |
| `header.search` | Angular's breakpoint-driven mobile state becomes the explicit `mobile` prop |
| `pagination` | Always renders the inline branch; `pagination-option-picker-modal` not ported |
| `tabs`, `tabs.list` | Overflow "More" dropdown dropped; `dropdownLabel` accepted for API parity but inert |
| `alert` | `closeDelay` accepted for API parity but inert |
| `toast` | Placement/animation not ported; `pauseOnHover` accepted but inert |
| `checkbox`, `radio`, `checkbox-group`, `radio-group` | Managed-group `ControlValueAccessor` not ported — `wire:model` on each child replaces it. The group's `managed` flag is an explicit prop (default `false`, matching Angular) gating `role` / `aria-labelledby` / `aria-label` / `aria-disabled` |
| `checkbox-group`, `radio-group` | `disabled` (and `name`, for radio) reach children via `@aware`. Angular's auto-generated group `name` cannot cross that boundary — pass `name` explicitly |
| `text-field`, `textarea`, `number-field`, `search`, `date-input` | The `value` attribute is emitted only when non-empty; an unconditional `value=""` would blank a `wire:model`-bound field on the initial server render. `slider` is the deliberate exception — a `type="range"` input has no empty state |
| `number-field` | Increment/decrement buttons are inert; attach handlers via `decrement-attributes` / `increment-attributes`. `LiveAnnouncer` and wrapper click-to-focus not ported |
| `slider` | Thumb tooltip not ported — it needs `trackPosition`, the one `<tedi:tooltip>` prop that is itself unported. `tedi-slider--invalid` and `--dragging` dropped |
| `textarea` | Slot content is a Blade extension used when `value` is empty; Angular has no `ng-content` here |
| `calendar-header`, `date-picker-header` | `monthYearSelectType` / `monthMode` / `yearMode` keep their `dropdown` default, but only the trigger renders. `<tedi:dropdown>` now exists; these two are simply not wired to it yet |
| `calendar`, `date-picker`, `date-field` | Month/weekday names come from the `date-picker.*` translation keys, not `Intl` (`ext-intl` is not a declared dependency), so `localeCode` is dropped. No narrow weekday key exists, so narrow names fall back to `-short` |
| `date-field`, `time-field` | Popover positioning dropped — the panel renders inline under an explicit `open` prop. The modal branch, `useNativePicker`, `fullscreen` and `modal` are not ported |
| `date-field` | Matcher machinery (`disabledMatchers`, `minDate`/`maxDate`, `disablePast`/`disableFuture`, …) collapses to a flat `disabledDays` array; `formatDate`/`parseDate` become the explicit `display` and `tags` props. `size` is accepted for API parity but inert — set it on the wrapping `<tedi:form-field>` |
| `time-picker` | All three `tedi-time-picker--{scroll,slots,dropdown}` classes are unstyled and dropped; `variant` still selects the markup branch. Only `--disabled` and `--bordered` are emitted |
| `tag` | `tedi-tag--primary` not emitted — TEDI ships no rule for it; the base `.tedi-tag` block is the primary appearance |
| `select` | Native-`<select>` subset (see table above) |
| `ellipsis` | CSS clamp only; no overflow measurement, so no reveal-on-hover tooltip |
| `accordion`, `tabs`, `carousel`, `header.toggle`, `footer.section`, `collapse`, `dropdown`, `popover`, `tooltip`, `modal`, `sidenav`, `button-group`, `table` | Inert without the bundled Alpine behaviour; markup and classes are still correct |
| `dropdown`, `popover`, `tooltip` | Panels stay where they are written instead of being re-parented into a `.cdk-overlay-container` (divergence 3 above) |
| `dropdown` | `value` does not drive item selection — Angular's items read it through `inject(DROPDOWN_API)`, which `@aware` cannot reproduce. Pass `:selected` on each `<tedi:dropdown-item>`. `container-id` replaces Angular's generated `containerId` |
| `tooltip` | `trackPosition` (rAF repositioning against a moving origin) and the touch handling are not ported. The sr-only description Angular reads from projected `textContent` is the explicit `description` prop |
| `modal` | Only the `[(open)]` template branch is ported. `ModalService` / `ModalRef` / `MODAL_DATA` and the `ConfigKey`s that exist only for them (`scrollBehavior`, `fullscreen`, `maxWidth`, `closeOnEscape`, `ariaLabel`, `ariaLabelledBy`, `data`) are not, nor is the CDK focus trap. `tedi-modal--bottom` dropped — TEDI ships no rule for it |
| `table` | Markup layer only, the same spirit as `select`. `@tanstack/angular-table` is not ported, so sorting, filtering, selection and expansion **state** are the consumer's and come back in through `:columns` / `:rows`. With the engine go row virtualisation, drag reorder, column resizing, `state` persistence and the built-in filter popover |
| `sidenav` | `SideNavService`'s signals become explicit props (`collapsed`, `mobile`, `mobileOpen`, `mobileItemOpen`); the sibling `sidenav.toggle` / `sidenav.overlay` talk to the nav over window events. `desktopBreakpoint` is not declared at all |
| `button-group` | `enableMobileDropdown` + `mobileBreakpoint` become the explicit `dropdownMode` prop; the collapsed dropdown is built from `items` only, so a slot-only group cannot collapse |
| `breadcrumbs`, `horizontal-stepper`, `button-group` | Angular's `contentChildren` registration becomes explicit props — an `items` array, or `step-number` on each stepper item |
| `vertical-stepper-item` | Same `contentChildren` case: nested steps go in the `sub-items` slot and each carries `:sub-item="true"`, because Blade cannot set it on the slot's children. `route`/RouterLink becomes a plain `href`, and `routerLinkActive`'s auto-selection (plus the `opened` effect it drives) becomes explicit `:selected` / `:opened`. `tedi-vertical-stepper--compact` dropped — TEDI ships no rule for it |
| `choicegroup` | Angular is an attribute directive applied to an existing group host; Blade renders a wrapper element instead. `spacing` only ever decides `--stacked` (upstream never writes a gap either) |
| `table-of-contents` | The mobile panel is the same nav with `table-of-contents--modal-active` rather than a second copy inside a CDK dialog. `onToggle`/`open` collapse into `default-open` — Angular never reads them. `TableOfContentsNestedWrapperComponent` is an Angular-bug workaround and is not ported. Its classes are not `tedi-`-prefixed upstream, so the stylesheet guardrails skip them |
| `file-dropzone` | Markup subset — Angular's `FileService`, `ControlValueAccessor` and async validators are the server's job here (see the table above) |

Five classes are emitted that TEDI ships **no CSS rule** for
(`tedi-empty-state--default`, `tedi-empty-state--separate`,
`tedi-feedback-text--hint`, `tedi-form-field__icon`,
`tedi-text-group--vertical`). These are faithful — the Angular components emit
them too — and are allowlisted as `$deadUpstream` in `tests/IntegrityTest.php`,
which fails if an entry ever goes stale.

Everywhere else the guardrail wins and the class is **dropped**: a class with no
rule is DOM noise a consumer may mistake for a hook (CONVENTIONS.md §4). Each
drop is recorded in its component's docblock — among them all three
`tedi-time-picker--<variant>` classes, `tedi-slider--invalid`, the `__label`
class on both group components, and `tedi-tag--primary` (the base `.tedi-tag`
block already *is* the primary appearance).

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

Together that is **all 67** components in Angular's `tedi/` tree, which expand
to **138 Blade components** once sub-components are counted. A fourth pass added
the five components that exist only in Angular's `community/` entry point — see
[From Angular's `community/` entry point](#from-angulars-community-entry-point)
below — for **145** in total.

| Angular component | Blade tag |
|---|---|
| `base/icon` | `<tedi:icon>` |
| `base/text` | `<tedi:text>` |
| `buttons/button` | `<tedi:button>` |
| `buttons/button-group` | `<tedi:button-group>`, `<tedi:button-group-button>` |
| `buttons/card-button` | `<tedi:card-button>` |
| `buttons/closing-button` | `<tedi:closing-button>` |
| `buttons/collapse` | `<tedi:collapse>` |
| `buttons/collapse-button` | `<tedi:collapse-button>` |
| `buttons/info-button` | `<tedi:info-button>` |
| `content/accordion` | `<tedi:accordion>`, `<tedi:accordion-item>`, `<tedi:accordion-item-header>`, `<tedi:accordion-item-content>` |
| `content/card` | `<tedi:card>`, `<tedi:card-header>`, `<tedi:card-content>`, `<tedi:card-icon>`, `<tedi:card-row>` |
| `content/carousel` | `<tedi:carousel>` + `-header`, `-content`, `-slide`, `-indicators`, `-navigation`, `-footer` |
| `content/calendar` | `<tedi:calendar>`, `<tedi:calendar-header>`, `<tedi:calendar-day-grid>`, `<tedi:calendar-month-grid>`, `<tedi:calendar-year-grid>` |
| `content/list` | `<tedi:list>` |
| `content/table` | `<tedi:table>`, `<tedi:table-toolbar>`, `<tedi:table-header-button>`, `<tedi:table-columns-menu>` ⁵ |
| `content/text-group` | `<tedi:text-group>`, `<tedi:text-group-label>`, `<tedi:text-group-value>` |
| `form/checkbox` | `<tedi:checkbox>` |
| `form/checkbox-card` | `<tedi:checkbox-card>`, `<tedi:checkbox-card-group>` |
| `form/checkbox-group` | `<tedi:checkbox-group>` |
| `form/date-field` | `<tedi:date-field>`, `<tedi:date-input>` ⁴ |
| `form/date-picker` | `<tedi:date-picker>` + `-header`, `-calendar-grid`, `-month-grid`, `-year-grid` |
| `form/feedback-text` | `<tedi:feedback-text>` |
| `form/form-field` | `<tedi:form-field>` |
| `form/input-group` | `<tedi:input-group>` |
| `form/label` | `<tedi:form.label>` ¹ |
| `form/label-row` | `<tedi:label-row>` |
| `form/number-field` | `<tedi:number-field>` |
| `form/radio` | `<tedi:radio>` |
| `form/radio-card` | `<tedi:radio-card>`, `<tedi:radio-card-group>` |
| `form/radio-group` | `<tedi:radio-group>` |
| `form/search` | `<tedi:search>` |
| `form/slider` | `<tedi:slider>` |
| `form/text-field` | `<tedi:text-field>` |
| `form/textarea` | `<tedi:textarea>` |
| `form/time-field` | `<tedi:time-field>` ⁴ |
| `form/time-picker` | `<tedi:time-picker>` |
| `form/toggle` | `<tedi:toggle>` |
| `helpers/attachment` | `<tedi:attachment>` |
| `helpers/empty-state` | `<tedi:empty-state>` |
| `helpers/grid` | `<tedi:row>`, `<tedi:col>` ² |
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
| `overlay/dropdown` | `<tedi:dropdown>` + `-trigger`, `-content`, `-item`, `-item-value`, `-item-value-label`, `-item-value-meta` |
| `overlay/info-tooltip` | `<tedi:info-tooltip>` |
| `overlay/modal` | `<tedi:modal>`, `<tedi:modal-header>`, `<tedi:modal-content>`, `<tedi:modal-footer>` |
| `overlay/popover` | `<tedi:popover>`, `<tedi:popover-trigger>`, `<tedi:popover-content>` |
| `overlay/tooltip` | `<tedi:tooltip>`, `<tedi:tooltip-trigger>`, `<tedi:tooltip-content>` |
| `tags/status-badge` | `<tedi:status-badge>` |
| `tags/status-indicator` | `<tedi:status-indicator>` |
| `tags/tag` | `<tedi:tag>` |

¹ Kept under `form/` rather than flattened, because `label` is the most generic
name in the library and a future collision is likely.
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
| `<tedi:date-field>`, `<tedi:time-field>` | Input, tags, trigger, and the panel inline under an `open` prop | Popover placement, and the mobile modal branch |
| `<tedi:number-field>` | Full markup with correct disabled states | Button behaviour — wire it via `increment-attributes` / `decrement-attributes` |
| `<tedi:file-dropzone>` | Full markup, a native `<input type="file">` that `wire:model` binds, drag-and-drop into that input, and the file list / states rendered from `:files`, `state`, `has-error`, `error` | Angular's client-side file pipeline: `FileService` (append/replace, duplicate renaming), the `ControlValueAccessor`, and the `validators` / `validateIndividually` async validation that wrote `uploadState` |
| `<tedi:table-of-contents>` | The list, scroll spy, seek-on-click and the mobile trigger | The CDK dialog — the panel opens in place via `--modal-active` instead of over a backdrop |

### From Angular's `community/` entry point

Angular ships a second entry point, `@tedi-design-system/angular/community`, next
to `tedi/`. Its components are built the same way — `ViewEncapsulation.None`,
global BEM classes — so they port under the same rules; CONVENTIONS.md §12
covers the three things that differ (group mapping, the `Community/…` Storybook
prefix, and the one path-only SCSS edit `floating-button` needs).

The five components that exist **only** there are ported:

| Angular component | Blade tag |
|---|---|
| `community/buttons/floating-button` | `<tedi:floating-button>` |
| `community/form/choicegroup` | `<tedi:choicegroup>` ⁶ |
| `community/form/file-dropzone` | `<tedi:file-dropzone>` ⁷ |
| `community/navigation/table-of-contents` | `<tedi:table-of-contents>`, `<tedi:table-of-contents-item>` ⁷ |
| `community/navigation/vertical-stepper` | `<tedi:vertical-stepper>`, `<tedi:vertical-stepper-item>` |

⁶ Angular is an attribute directive with no template; Blade has no directives,
so it ports as a wrapper element carrying the same four host classes. It is the
community predecessor of `tedi/`'s `<tedi:radio-card>` / `<tedi:checkbox-card>`,
which is what TEDI-Ready ships — prefer those in new code.
⁷ Documented subsets — see the table above.

Everything else in `community/` duplicates a `tedi/` component under an older
name (`accordion`, `card`, `checkbox`, `radio`, `select`, `modal`, `dropdown`,
`dropdown-item`, `tabs`, `tag`, `status-badge`, `breadcrumbs`, `pagination`,
`progress-bar`, `search`, `textarea`, `form-field`, `input-group`) and is
covered by the `tedi/` port. Two community-only components are still **not
ported**: `input` (superseded by `<tedi:text-field>`) and `table-styles` (a
styling wrapper superseded by `<tedi:table>`).

### Not ported

Within `tedi/`, every component is ported; what remains are the documented
subsets above plus five places where a now-available primitive has not been
wired up yet:

| Component | Would gain |
|---|---|
| `calendar-header`, `date-picker-header` | Their real month/year pickers, from `<tedi:dropdown>` |
| `date-field`, `time-field`, `pagination` | Their overlay / modal branches |
| `header.profile`, `header.role` | The popover branch instead of the always-on modal branch |
| `tabs.list` | The overflow "More" dropdown behind the inert `dropdownLabel` |
| `slider` | Its thumb tooltip, once `<tedi:tooltip>` gains `trackPosition` |

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
All 66 components have a `README.md`; 127 stories have a per-story description,
which is every Angular story that carries one.

Two known gaps. `Community/Form/FormField` has no description because the Angular
story has none — its `README.md` is an HTML comment that renders nothing and
exists only to switch the Docs page on. And the props table on a Docs page is
Storybook's, built from the component's *first* story; because that ordering is
alphabetical (above) rather than Angular's export order, 23 of the 66 pages land
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
`TEDI-Ready/Components/Overlay/*`. Under `Community/`, the four components ported
from Angular's `community/` entry point are present —
`Buttons/Floating Button`, `Form/FileDropzone`, `Navigation/Table of Contents`
and `Navigation/VerticalStepper` — plus `Form/FormField`, which is where Angular
files the shared form-field stories. `<tedi:choicegroup>` has no directory
because Angular has no story file for it: it is a directive, exercised through
the community `radio` / `checkbox` stories, which belong to components the
`tedi/` port already covers.

### Angular stories with no Blade equivalent

510 stories across 66 components are ported. Some Angular stories exist purely
to demonstrate behaviour this package documents as not ported (see the
divergences table above). Those are deliberately absent rather than faked:

| Skipped Angular story | Why |
|---|---|
| `Checkbox`: Vertical, Horizontal, VerticalTree, Group, WithReactiveForms · `Radio`: Vertical, Horizontal, Group, WithReactiveForms | Managed-group `ControlValueAccessor` / reactive forms — use `wire:model` instead |
| `Select`: ValueType, EllipsisTags, Examples, Tooltip, ReactiveForms, CustomSearchFunction, Outputs, VirtualScroll | The searchable/multi-select combobox; the port is the native-`<select>` subset |
| `Ellipsis`: NoTooltip | Needs the `ResizeObserver` overflow measurement the CSS-clamp subset does not do |
| `Toast`: Positions, HoverBehavior, CustomTimerForAutoclose, PersistentToast | Overlay placement and the JS auto-close timer |
| `Toast` Docs page: the "Usage" section | Documents Angular's `ToastService`, which spawns toasts into a CDK Overlay container. `<tedi:toast>` renders markup only and leaves placement to the consumer, so the section is replaced by a note saying so |
| `Tabs`: OverflowBehavior, WithSubTabs · `Pagination`: ResponsiveVisibility, ShowAll | The overflow "More" dropdown and the option-picker modal — both primitives now exist, but `tabs.list` and `pagination` are not wired to them |
| `Card`: BreakpointProps · `TextGroup`, `ProgressBar`: Responsive | Breakpoint props |
| `ProgressBar`: Animated · `InputGroup`: StartDynamic, EndDynamic, AllControls · `Attachment`: LabeledActions | Runtime state / `output()` events |
| `Header`: LoggedInWithSidenav | Composes `header` and `sidenav`, which are ported separately; the combined story is not |
| `Card`: WithDottedSeparator, PrescriptionExample | A style this port doesn't emit |
| `DatePicker`, `TimePicker`, `TimeField`, `DateField`: WithReactiveForms | Angular reactive forms — use `wire:model` instead |
| `DateField`, `TimeField`: NativePicker, MobileModal · `DateField`: CustomFormatAndParse, CustomLocale | Breakpoint props, the unported modal branch, JS format/parse callables, and `localeCode` |
| `FileDropzone`: Replace | Exercises the `mode` input, part of the unported `FileService` file pipeline |

Angular's Hover / Active / Focus matrix rows are reproduced in full.
`storybook-addon-pseudo-states` isn't one of Blast's dependencies, so
`storybook/start.sh` installs it on top of them and `.storybook/main.js` loads it
from there; a matrix story opts in with a `pseudoStates` arg. See
`storybook/CONTRACT.md` §5.

## Development

```bash
npm run build     # compile dist/tedi.css (+ js, fonts)
npm run watch     # recompile CSS on change
composer test     # class-parity test suite
```

The component SCSS under `resources/scss/components/**` is a **verbatim vendored
copy** from the Angular repo so it can be re-synced on TEDI releases — don't edit
it. Divergences belong in the Blade templates.

Porting rules live in [CONVENTIONS.md](CONVENTIONS.md). Read it before adding a
component.

## License

MIT
