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
```

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
3. **Overlay positioning** (CDK Overlay / floating-ui) is out of scope for the
   template-only phase — this affects tooltip, popover, dropdown, modal and the
   toast's placement.
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
| `header.profile`, `header.role` | Always render the modal branch; the popover branch needs overlay positioning |
| `header.search` | Angular's breakpoint-driven mobile state becomes the explicit `mobile` prop |
| `pagination` | Always renders the inline branch; `pagination-option-picker-modal` not ported |
| `tabs`, `tabs.list` | Overflow "More" dropdown dropped; `dropdownLabel` accepted for API parity but inert |
| `alert` | `closeDelay` accepted for API parity but inert |
| `toast` | Placement/animation not ported; `pauseOnHover` accepted but inert |
| `checkbox`, `radio` | Managed-group `ControlValueAccessor` not ported — use `wire:model`; the `*-group` components are out of scope |
| `select` | Native-`<select>` subset (see table above) |
| `ellipsis` | CSS clamp only; no overflow measurement, so no reveal-on-hover tooltip |
| `accordion`, `tabs`, `carousel`, `header.toggle`, `footer.section` | Inert without the bundled Alpine behaviour; markup and classes are still correct |

A handful of classes are emitted that TEDI ships **no CSS rule** for
(`tedi-text--inherit`, `tedi-empty-state--default`, `tedi-feedback-text--hint`
and five others). These are faithful — the Angular components emit them too —
and are allowlisted in `tests/IntegrityTest.php`.

## What's implemented

This phase ports the **template-only** components: those whose rendered markup is
a pure function of their inputs. The set was derived mechanically from the
Angular sources (no `ControlValueAccessor`, no CDK Overlay positioning, no
open/close state, no DOM event handling) — **39 of the 67** components in
Angular's `tedi/` tree, which expand to **79 Blade components** once
sub-components are counted.

| Angular component | Blade tag |
|---|---|
| `base/icon` | `<tedi:icon>` |
| `base/text` | `<tedi:text>` |
| `buttons/button` | `<tedi:button>` |
| `buttons/card-button` | `<tedi:card-button>` |
| `buttons/closing-button` | `<tedi:closing-button>` |
| `buttons/info-button` | `<tedi:info-button>` |
| `content/accordion` | `<tedi:accordion>`, `<tedi:accordion-item>`, `<tedi:accordion-item-header>`, `<tedi:accordion-item-content>` |
| `content/card` | `<tedi:card>`, `<tedi:card-header>`, `<tedi:card-content>`, `<tedi:card-icon>`, `<tedi:card-row>` |
| `content/carousel` | `<tedi:carousel>` + `-header`, `-content`, `-slide`, `-indicators`, `-navigation`, `-footer` |
| `content/list` | `<tedi:list>` |
| `content/text-group` | `<tedi:text-group>`, `<tedi:text-group-label>`, `<tedi:text-group-value>` |
| `form/checkbox` | `<tedi:checkbox>` |
| `form/checkbox-card` | `<tedi:checkbox-card>`, `<tedi:checkbox-card-group>` |
| `form/feedback-text` | `<tedi:feedback-text>` |
| `form/form-field` | `<tedi:form-field>` |
| `form/input-group` | `<tedi:input-group>` |
| `form/label` | `<tedi:form.label>` ¹ |
| `form/label-row` | `<tedi:label-row>` |
| `form/radio` | `<tedi:radio>` |
| `form/radio-card` | `<tedi:radio-card>`, `<tedi:radio-card-group>` |
| `helpers/attachment` | `<tedi:attachment>` |
| `helpers/empty-state` | `<tedi:empty-state>` |
| `helpers/grid` | `<tedi:row>`, `<tedi:col>` ² |
| `helpers/separator` | `<tedi:separator>` |
| `helpers/timeline` | `<tedi:timeline>`, `<tedi:timeline-item>` |
| `layout/footer` | `<tedi:footer>` + `.body`, `.section`, `.side`, `.bottom` |
| `layout/header` | `<tedi:header>` + `.top`, `.bottom`, `.content`, `.logo`, `.login`, `.logout`, `.profile`, `.role`, `.search`, `.language`, `.actions`, `.toggle`, `.mobile-button` |
| `loader/progress-bar` | `<tedi:progress-bar>` |
| `loader/spinner` | `<tedi:spinner>` |
| `navigation/link` | `<tedi:link>` |
| `navigation/pagination` | `<tedi:pagination>` |
| `navigation/tabs` | `<tedi:tabs>`, `<tedi:tabs.list>`, `<tedi:tabs.trigger>`, `<tedi:tabs.content>` |
| `notifications/alert` | `<tedi:alert>` |
| `notifications/toast` | `<tedi:toast>` ³ |
| `tags/status-badge` | `<tedi:status-badge>` |
| `tags/status-indicator` | `<tedi:status-indicator>` |
| `tags/tag` | `<tedi:tag>` |

¹ Kept under `form/` rather than flattened, because `label` is the most generic
name in the library and a future collision is likely.
² Named after the CSS classes it emits (`.tedi-row` / `.tedi-col`) rather than
after the Angular directory.
³ Visual markup only — overlay placement is out of scope (see divergences).

### Shipped as a documented subset

| Component | What you get | What's missing |
|---|---|---|
| `<tedi:select>` | Native `<select>` styled with `tedi-input` | Searchable / multi-select custom combobox (needs CDK Overlay) |
| `<tedi:ellipsis>` | CSS line-clamped truncation | Reveal-on-hover tooltip (needs `ResizeObserver` measurement) |
| `<tedi:scroll-fade>` | Static markup | Scroll-driven fade state |

### Not in this phase

The remaining 28 Angular components need form-value binding, overlay
positioning, or open/close state: `button-group`, `collapse`, `collapse-button`,
`calendar`, `table`, `checkbox-group`, `date-field`, `date-picker`,
`number-field`, `radio-group`, `search`, `slider`, `text-field`, `textarea`,
`time-field`, `time-picker`, `toggle`, `sidenav`, `breadcrumbs`,
`horizontal-stepper`, `dropdown`, `info-tooltip`, `modal`, `popover`, `tooltip`.

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
All 38 components have a `README.md`; 54 stories have a per-story description,
which is every Angular story that carries one.

Two known gaps. `Community/Form/FormField` has no description because the Angular
story has none — its `README.md` is an HTML comment that renders nothing and
exists only to switch the Docs page on. And the props table on a Docs page is
Storybook's, built from the component's *first* story; because that ordering is
alphabetical (above) rather than Angular's export order, 13 of the 38 pages land
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

Two Angular groups have no Blade counterpart at all and so are absent:
`TEDI-Ready/Components/Overlay/*` and the components listed under "Not in this
phase" above. `Community/Form/FormField` is present because that is where
Angular files the form-field stories.

### Angular stories with no Blade equivalent

274 stories across 38 components are ported. The port is template-only, so some
Angular stories exist purely to demonstrate behaviour this package documents as
not ported (see the divergences table above). Those are deliberately absent
rather than faked:

| Skipped Angular story | Why |
|---|---|
| `Checkbox`: Vertical, Horizontal, VerticalTree, Group, WithReactiveForms · `Radio`: Vertical, Horizontal, Group, WithReactiveForms | Managed-group `ControlValueAccessor` / reactive forms — use `wire:model` instead |
| `Select`: ValueType, EllipsisTags, Examples, Tooltip, ReactiveForms, CustomSearchFunction, Outputs, VirtualScroll | The searchable/multi-select combobox; the port is the native-`<select>` subset |
| `ClosingButton`, `StatusBadge`: WithTooltip · `InfoButton`: UsageWithTooltipAndPopover · `Ellipsis`: NoTooltip | Overlay positioning |
| `Toast`: Positions, HoverBehavior, CustomTimerForAutoclose, PersistentToast | Overlay placement and the JS auto-close timer |
| `Toast` Docs page: the "Usage" section | Documents Angular's `ToastService`, which spawns toasts into a CDK Overlay container. `<tedi:toast>` renders markup only and leaves placement to the consumer, so the section is replaced by a note saying so |
| `Tabs`: OverflowBehavior, WithSubTabs · `Pagination`: ResponsiveVisibility, ShowAll | Overflow "More" dropdown and the option-picker modal |
| `Card`: BreakpointProps · `TextGroup`, `ProgressBar`: Responsive | Breakpoint props |
| `ProgressBar`: Animated · `InputGroup`: StartDynamic, EndDynamic, AllControls · `Attachment`: LabeledActions | Runtime state / `output()` events |
| `Header`: LoggedInWithSidenav · `FormField`: WithTextarea · `Card`: WithDottedSeparator, PrescriptionExample | Compose a component not in this phase (`sidenav`, `textarea`) or a style this port doesn't emit |

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
