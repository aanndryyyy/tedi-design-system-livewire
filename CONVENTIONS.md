# TEDI → Blade porting conventions

This document is the **single source of truth** for porting TEDI components from
`@tedi-design-system/angular` to Blade. Every component must follow it so the
library reads as one system rather than 42 individual translations.

Read this **before** writing any component. When the Angular source does
something this document doesn't cover, extend this document first, then write
the component.

---

## 1. Why Angular is the source (not React)

| Repo | Styling | Usable as port source? |
|---|---|---|
| `angular` | `ViewEncapsulation.None` on **all 144** components → plain global BEM classes (`tedi-button`, `tedi-button--primary`) | **Yes — this is the source** |
| `react` | CSS Modules (`*.module.scss`) → hashed class names **in the built dist**; the SCSS *source* is plain BEM | **Additive source** — see §13 |
| `core` | Design tokens, base/reset, typography, fonts, icons, utilities. **No component styles.** | Yes — as the token/base layer only |

The port therefore reads markup + class logic from `angular/tedi/components/**`,
and gets its tokens from `@tedi-design-system/core`. Use the React repo only as a
secondary reference for prop documentation.

---

## 2. File layout

```
resources/views/components/<group>/<component>.blade.php   # anonymous Blade component
resources/scss/components/<group>/<component>/*.scss       # copied verbatim from Angular
```

`<group>` mirrors the Angular tree: `base`, `buttons`, `content`, `form`,
`helpers`, `layout`, `loader`, `navigation`, `notifications`, `overlay`, `tags`.

Usage is the Flux-style short syntax, registered via
`Blade::anonymousComponentNamespace()`:

```blade
<tedi:button variant="primary">Salvesta</tedi:button>
<tedi:tags.tag type="danger">Vigane</tedi:tags.tag>
```

**Flattening rule:** components whose name is unambiguous across the whole
library are additionally exposed at the top level (`<tedi:button>`,
`<tedi:icon>`, `<tedi:tag>`). Do this by placing the file at
`components/<name>.blade.php` and keeping the group folder only when the name
would collide (e.g. `form/label` vs `content/label`).

**Never edit the copied SCSS.** It is a verbatim vendored copy so it can be
re-synced when TEDI releases. Divergences belong in the Blade template.

### Two Blade compiler traps the composed components hit

**Always leave whitespace after `</x-slot:…>`.** It compiles to `@endslot`, and
Blade matches directives on a word boundary — so a word character immediately
after the closing tag (`</x-slot:trigger>x`) is read as the non-existent
directive `@endslotx`. The failure is silent and ugly: a literal `@endslot`
survives into the output, the named slot's content lands in the **default** slot
instead, and an output buffer is left open (PHPUnit reports "Test code or tested
code did not close its own output buffers"). A single space or newline fixes it.
This is generic Blade behaviour, not a quirk of one component — it reproduces on
`<tedi:card>` as readily as on the overlay components.

**Never write an angle-bracketed `<tedi:…>` inside a `{{-- --}}` docblock.**
`BladeCompiler::compileString()` runs the component-tag compiler over the whole
file *before* it strips comments, so a tag in prose is compiled as a real tag.
An unpaired one — `<tedi:vertical-stepper>'s defaults` in a header comment —
swallows the next `@endif` it can reach, and the file dies with
`syntax error, unexpected end of file, expecting "elseif" or "else" or "endif"`
pointing at nothing in particular. Write `` `tedi:vertical-stepper` `` instead.
This is the same trap `storybook/CONTRACT.md` §6a documents for `@storybook`
blocks; it applies to every Blade file, comments included.

**Never open a component tag in one `@if` branch and close it in another.** The
component-tag compiler pairs tags before any conditional is evaluated — but it
does **not** error, which is what makes this dangerous. The compiler emits the
component's own `if ($component->shouldRender()):` guard between your two
branches, so your first `@endif` closes *that* rather than your own `@if`. The
result rebalances by accident and everything between the two branches ends up
inside the condition, so **the false branch silently renders nothing at all**.
No error, no warning, and no class-parity test can see it. Duplicate the small
amount of markup, or move the condition inside the component.

---

## 3. Mapping table — Angular → Blade

| Angular | Blade |
|---|---|
| `foo = input<Type>('default')` | `@props(['foo' => 'default'])` |
| `foo = input.required<T>()` | `@props(['foo'])` + `@if(! $foo) throw @endif` guard where cheap |
| `host: { '[class]': 'classes()' }` | `$attributes->class([...])` on the **root element** |
| `<ng-content />` | `{{ $slot }}` |
| `<ng-content select="[header]" />` | named slot `{{ $header }}` |
| `@if (cond) { … }` | `@if ($cond) … @endif` |
| `@for (x of xs; track x)` | `@foreach ($xs as $x)` |
| `inject(_IdGenerator)` | `Tedi::id('tedi-tag')` |
| `\| tediTranslate` | `__('tedi::tedi.remove')` |
| `inject(ParentComponent)` | `@aware(['size' => 'default'])` — see the rule below |
| `output<Event>()` | **nothing** — let the consumer bind `wire:click` / `x-on:click` via `$attributes` |
| `inject(ElementRef)` + `AfterContentChecked` | an **explicit prop** (see §5) |
| `inject(BreakpointService)`, `xs`/`sm`/`md`… inputs | **not ported** (see §7) |

### `:prop="null"` cannot switch off a non-empty default

Blade resolves `@props` defaults with `isset()`, so an explicitly-passed `null`
is indistinguishable from "not passed" and falls back to the default. Angular
inputs that accept `null` to *suppress* something (`empty-state`'s `icon`) can
therefore not be turned off that way.

Give such props an empty-string escape hatch and document it on the prop:
`icon=""` hides the icon, `:icon="null"` does not. Applies to any nullable prop
with a non-empty default.

### `@aware` only sees what the consumer explicitly passed

`@aware` reads the **parent's attribute bag**, which contains only attributes the
consumer actually wrote. A `@props` default on the parent that the consumer
omitted does **not** reach the child.

So the child's `@aware` fallback **must equal the parent's `@props` default**:

```blade
{{-- parent: radio-card-group.blade.php --}}
@props(['grouped' => false])

{{-- child: radio-card.blade.php --}}
@aware(['grouped' => false])   {{-- same default — both paths now agree --}}
```

If the two disagree, `<tedi:radio-card-group>` and
`<tedi:radio-card-group :grouped="false">` render differently, which is a bug.
`tests/AwareTest.php` pins this for each parent/child pair — add a case there
whenever you introduce one.

### Never `@aware` a prop name the child also declares

`@aware` does not read the parent's bag first. Laravel's
`getConsumableComponentData()` checks `currentComponentData` — the **current**
component's own data — before walking up. So when a child both declares
`value` and `@aware`s `value`, it resolves **its own**, not the parent's.

The failure is silent and total: `<tedi:dropdown value="b">` with items that each
carry their own `value` would have every item compare itself against itself, so
every item renders selected. No class-parity test catches it, because each
item's class list is individually plausible.

Where Angular reads a parent's state to decide a child's appearance, and the
child has its own prop of that name, use an **explicit prop** (§5) instead —
`<tedi:dropdown-item :selected="…">`. Reserve `@aware` for names the child does
not own (`containerId`, `dropdownRole`, `size`).

---

## 4. The class-list rule (most important)

Angular computes a class string in `classes()`. Blade must produce **the exact
same class list** for the same inputs. Always build it with
`$attributes->class([...])` so consumer classes merge instead of being dropped:

```blade
@props([
    'variant' => 'primary',
    'size'    => 'default',
])

<button {{ $attributes->class([
    'tedi-button',
    'tedi-button--'.$variant,
    'tedi-button--'.$size,
]) }}>
    {{ $slot }}
</button>
```

Conditional classes use the `'class' => $bool` form:

```blade
'tedi-tag--loading' => $loading,
'tedi-tag--closable' => $closable,
```

Do **not** hand-concatenate class strings, and do **not** put `class="..."`
alongside `$attributes->class()` — that silently drops consumer classes.

### Ruling: classes Angular emits but TEDI never styles

Several Angular components emit modifier classes the vendored SCSS has no rule
for (`tedi-accordion__item--disabled`, `tedi-pagination--no-arrows`,
`tedi-progress-bar--value-bottom`, …). Strict class parity says emit them;
the stylesheet guardrail says don't. **The guardrail wins — drop them.**

Rationale: a class with no rule is not styling, it is DOM noise a consumer may
mistake for a hook. Keeping the guardrail strict is what catches genuinely
invented names, and every exception weakens it. Where dropping is *not*
behaviourally identical, keep the class and add it to `$deadUpstream` in
`tests/IntegrityTest.php` with a comment — that list is the audited record, and
a test fails if an entry ever goes stale.

Always record the omission in the component's docblock so a future re-sync (if
TEDI ships the missing rule) can restore it.

### Ruling: element selectors in the vendored SCSS

Some TEDI styles target the Angular **tag name**, not a class —
`.tedi-button tedi-icon { … }`, `tedi-timeline-title { … }`,
`tedi-carousel { … }`. A `<span class="tedi-icon">` matches none of them.

**Render the literal custom element** (`<tedi-icon>`, `<tedi-timeline-item>`),
keeping the class list as well, so both selector forms match. Custom elements
are valid HTML; TEDI's own class rules supply `display`, so no fallback is
needed.

The rule is mechanical, and stronger than "grep this component's own SCSS":

> **The Blade root element must be the Angular selector's element name whenever
> that selector is an element** (`selector: "tedi-foo"` → root `<tedi-foo>`), and
> must carry the literal attribute whenever the selector is attribute-based
> (`input[tedi-checkbox]` → `<input tedi-checkbox>`). **A class alone is never
> sufficient**, because sibling and descendant rules in *other* components key on
> the element.

That last clause is why grepping only the component's own file misses defects.
Real examples, all found this way:

| Rule | Lives in | Breaks |
|---|---|---|
| `&__spinner-wrapper { tedi-spinner { --tedi-spinner-size: 12 } }` | `tag.component.scss` | a 48px spinner inside a 24px tag |
| `.tedi-toast__wrapper tedi-alert { box-shadow: … }` | `toast.component.scss` | the toast drop shadow |
| `label:has(input[tedi-checkbox]) + tedi-feedback-text { … }` | `checkbox.component.scss` | hint text not indented under the label |
| `tedi-header-profile tedi-icon.tedi-header-profile__icon { font-size: var(--icon-06) }` | `header-profile.component.scss` | profile icon at 18px instead of 36px |

**Also check `display`.** Unknown elements default to `display: inline`. Most
TEDI rules set the display themselves (`.tedi-spinner { display: flex }`,
`tedi-toast { display: block }`), which makes the swap safe. Where neither the
element rule nor the class rule sets one — `carousel-slide.component.scss` is
the known case — verify what Angular actually computes before swapping.

**Check for the whole library at once**, rather than per component: grep the
vendored SCSS for element selectors, grep the rendered story HTML for the
corresponding tags, and diff the two lists. Worth wiring into CI.

### Ruling: Alpine `:class` must come *after* `$attributes->class()`

An `x-bind:class="{…}"` written before `{{ $attributes->class([…]) }}` puts a JS
expression in an attribute whose name ends in `class`. Test helpers and any
naive DOM scraping can pick that up instead of the real class list.
`classesOf()` now rejects bound attributes, but keep the real `class` first
anyway — it is the attribute that matters.

---

## 5. DOM introspection → explicit props

Several Angular components inspect their own projected content at runtime.
`BaseButtonDirective` is the canonical case: it counts child nodes to decide
`tedi-button--icon-only`, `--pl` (pad left) and `--pr` (pad right).

Blade renders once on the server and cannot inspect its own slot reliably.
**Translate every such case to an explicit prop**, uniformly:

| Angular runtime detection | Blade prop |
|---|---|
| icon is the only child | `:icon-only="true"` |
| first child is an icon | `icon-start="add"` |
| last child is an icon | `icon-end="arrow_forward"` |

When `icon-start` / `icon-end` are given, the component renders the
`<tedi:icon>` itself, so it also knows the padding modifiers without guessing.

---

## 6. Attribute merging & the root element

- Exactly **one** root element per component, and `{{ $attributes }}` goes on it.
- Use `$attributes->class([...])` for classes; plain `{{ $attributes }}` already
  includes them, so never emit both.
- Pull props that must not leak to the DOM through `@props`, never read them off
  `$attributes`.
- For components rendering a native form control, forward `wire:model`
  automatically — it lands via `$attributes` on the `<input>`, so put
  `{{ $attributes }}` on the **control**, not the wrapper, and give the wrapper
  static classes.

### Computed non-class host attributes (`role`, `aria-live`, `aria-label`, …)

Angular's `host: { '[attr.role]': ..., '[attr.aria-live]': ..., '[attr.aria-label]': ... }`
bindings become computed non-class attributes on the root element (`alert`,
`status-badge`). Emit them via `$attributes->merge([...])`, chained after
`->class()`, **never** as raw `attr="{{ $value }}"` alongside `$attributes`/
`{{ $attributes }}` — raw emission plus `$attributes` on the same element
produces two attributes of the same name in the output if the consumer also
passes one (e.g. their own `aria-label`), which is invalid HTML. `merge()`
lets a consumer-supplied value win while falling back to the computed
default, matching how `class`/`style` already merge instead of duplicate:

```blade
{{ $attributes->class([...])->merge(array_filter([
    'role' => $role !== 'none' ? $role : null,
    'aria-live' => $ariaLive,
    'aria-label' => $ariaLabel,
]))->style([...]) }}
```

`array_filter` drops `null` entries so the attribute is omitted entirely
(matching Angular's `[attr.x]="null"` removing the attribute) rather than
rendering `role=""`.

---

## 7. Known divergences from Angular (document, don't fake)

1. **Breakpoint props are not ported.** Angular's `xs`/`sm`/`md`/`lg`/`xl`/`xxl`
   inputs are resolved *at runtime in JavaScript* by `BreakpointService`, which
   picks the value for the currently-matched breakpoint. Server-rendered Blade
   has no viewport, and TEDI's CSS has no breakpoint-variant classes to emit
   instead. Components accept the **base props only**. Consumers who need
   responsive behaviour use TEDI's utility classes or their own CSS.
   **Ruling on declaring them anyway:** do **not** declare `xs`…`xxl` as inert
   `@props`. They are omitted entirely, so a consumer passing one gets a visible
   stray HTML attribute rather than silent no-op — a louder, more honest failure.
   The single exception is a breakpoint-ish prop whose *name* does not signal
   this (`attachment`'s `verticalBelow`), which is declared and documented as
   inert precisely so it does not leak into the DOM looking like a real
   attribute. If in doubt, omit.
2. **`output()` events are not re-emitted.** Consumers bind Livewire/Alpine
   listeners directly to the rendered element.
3. **Tooltip/dropdown/modal positioning** is ported, but by this package's own
   anchoring engine rather than CDK Overlay — see §11. Anything that was
   documented as "blocked on overlay positioning" before §11 existed is no
   longer blocked; a component still carrying that note is stale.
4. **`form/select` ports as a native-`<select>` subset, not the Angular
   combobox.** Angular's `tedi-select` is a custom combobox built on CDK
   Overlay (a div trigger + an overlaid `<ul cdkListbox>`), with virtual
   scrolling, multiselect tags, a search input, and custom option/value
   templates — none of it native `<select>` markup. Per divergence #3 above,
   CDK Overlay positioning is out of scope, so the port renders a genuine
   native `<select>` instead: it works with `wire:model` out of the box, needs
   no ControlValueAccessor reimplementation, and only emits classes the
   vendored SCSS actually defines (`tedi-select`, the shared
   `tedi-input`/`tedi-input--disabled|small|error|valid` modifiers, and
   `tedi-select--multiselect`). Dropped as a result: `searchable`, `groupBy`,
   virtual scroll, multiselect tag rendering, custom option/value templates,
   `tooltip`, `ellipsis`, and `clearable` (see `select.blade.php`'s header
   comment for the full rationale per prop). **The "CDK Overlay is out of
   scope" half of this rationale is stale** — §11 supplies the anchoring, and
   `community`'s `multiselect` is ported on top of it. What still keeps
   `tedi/`'s own select native is the rest: virtual scroll, custom
   option/value templates, and a ControlValueAccessor a native `<select>` +
   `wire:model` gets for free.

---

## 8. Interactivity policy

Where a component is inert without JS (tabs, carousel, accordion, pagination),
ship the correct TEDI markup **plus minimal Alpine**. Alpine ships with Livewire,
so this adds no dependency.

Alpine usage must be additive: the markup and classes stay identical to Angular,
with `x-data` / `x-on` layered on top. A consumer who strips the JS still gets
correct static markup — **including the classes that only appear when open**.
An `x-show`n panel must be in the DOM with its real class list, not conjured by
JS, or the parity tests in §10 have nothing to assert against.

**Where the behaviour lives.** Inline in the template by default. Move it into
`resources/js/` as an `Alpine.data()` only when it is too large to read inline
or is shared by several components — currently `tediCarousel`, `tediOverlay`
and `tediDropdown` (§11), `tediFilter`, `tediMultiselect`, `tediModal`,
`tediTableOfContents` and `tediBreakpoint`.

**The JS layout.** `resources/js/tedi.js` is the entry point and holds nothing
but imports and the `Alpine.data()` registrations. Each behaviour is a module
under `resources/js/src/`, exporting a factory of the same name:

| File | Exports | Notes |
|---|---|---|
| `src/breakpoint.js` | `breakpoint` | Shared by `hide-at` and `show-at`; one `matchMedia` query off core's `$grid-breakpoints`, with `mode` choosing which side is visible |
| `src/position.js` | placement maths | Pure functions over rectangles — no Alpine, no DOM writes |
| `src/overlay.js` | `overlay` | Open/close, dismissal, and the DOM writes that apply `position.js` |
| `src/dropdown.js` | `dropdown` | Composes `overlay` and adds the ARIA menu keyboard layer (§11) |
| `src/filter.js` | `filter` | Composes `overlay` and adds the filter's selection state plus its `aria-activedescendant` listbox layer — deliberately **not** `dropdown`, whose roving-tabindex menu is a different ARIA pattern |
| `src/multiselect.js` | `multiselect` | Composes `overlay` and adds the community multiselect's selection state plus its `aria-activedescendant` listbox layer — the same ARIA pattern as `filter`, not `dropdown`'s menu |
| `src/modal.js` | `modal` | |
| `src/carousel.js` | `carousel` | |
| `src/table-of-contents.js` | `tableOfContents` | |
| `src/scroll-visibility.js` | `scrollVisibility` | Scroll-threshold visibility for `scroll-visibility`; owns a `scroll` listener, so it is a module rather than inline |
| `src/hash-trigger.js` | `hashTrigger` | `hashchange` + `scrollIntoView` for `hash-trigger` |

A new behaviour is a new file in `src/` plus two lines in `tedi.js` (an import
and an `Alpine.data()` call). Do not add a second `Alpine.data()` call site.

`npm run build:js` bundles them with **esbuild** into a single classic (IIFE,
non-module, es2017) `dist/tedi.js`. That output is unchanged in kind from
before the split: consumers still load one plain `<script>`, and `dist/` is
committed so Composer consumers never run npm. npm was already required for
the sass build, so esbuild adds a devDependency, not a new prerequisite.

Two consequences worth knowing. The bundle is **comment-stripped**, so the
prose that documents each behaviour lives in `resources/js/` and not in the
shipped file — a banner at the top of `dist/tedi.js` says so. And the modules
are real ES modules: `import`/`export` between them is fine, but nothing may
rely on a shared implicit scope the way the single-IIFE version could.

---

## 9. Definition of done, per component

1. Blade file exists at the mapped path, one root element, `$attributes` merged.
2. Every Angular `input()` is a `@props` entry with the **same default**.
3. Emitted class list matches Angular's `classes()` + `host` bindings exactly
   for every value of every prop union.
4. A parity test exists in `tests/` asserting the class strings (see §10).
5. The component has a story per Angular story export under
   `storybook/resources/views/stories/` (see `storybook/CONTRACT.md`), and a
   variant matrix fixture in `tests/fixtures/matrices/` covering each prop union.

---

## 10. Parity testing

Fidelity is proven by **class-string parity**, not by "it looks right".
For each component, enumerate the cartesian product of its prop unions, render
the Blade component, and assert the class list equals what Angular's `classes()`
would compute.

### NEVER assert class names with `assertStringContainsString()`

Class names are prefixes of one another. `tedi-button--pr` is a **substring of
`tedi-button--primary`**, so a substring assertion reports a class as present
when it is not — and a `assertStringNotContainsString` fails when the class is
genuinely absent. Every such assertion is silently unsound.

Use the token-based helpers on `Tedi\Livewire\Tests\TestCase`, which parse the
`class` attribute and compare exact tokens:

```php
public function test_button_variant_classes(): void
{
    foreach (['primary', 'secondary', 'neutral', 'danger'] as $variant) {
        $html = Blade::render('<tedi:button variant="'.$variant.'">x</tedi:button>');
        $this->assertHasClass('tedi-button--'.$variant, $html);
    }
}

public function test_padding_modifier_is_dropped(): void
{
    $html = Blade::render('<tedi:button icon-end="arrow_forward">x</tedi:button>');
    $this->assertHasClass('tedi-button--pl', $html);
    $this->assertMissingClass('tedi-button--pr', $html);
}
```

Both helpers take an optional third argument to disambiguate when a component
renders several elements with classes:

```php
$this->assertHasClass('tedi-spinner--size-48', $html, on: 'tedi-spinner');
```

`assertStringContainsString()` remains fine for non-class assertions (attribute
values, slot text, `wire:model` presence).

Extract the unions directly from the Angular `export type` declarations — that
is the authoritative list, and it catches variant-name typos mechanically.

---

## 11. Overlay positioning

Angular anchors `dropdown`, `tooltip` and `popover` with CDK Overlay. This
package has no CDK, and — importantly — **TEDI ships no placement CSS**. Its
stylesheet positions nothing: `.tedi-tooltip__container` and
`.tedi-popover__container` are `position: relative`, and every rule that reacts
to placement keys off a `data-placement` attribute to rotate the arrow. The
pane's x/y and the arrow's `left`/`top` are expected to arrive as **inline
styles**. So the placement maths cannot be dropped the way breakpoint props
were — without it the panel renders in the document flow and the arrow points
nowhere.

### The engine

`Alpine.data('tediOverlay')` in `resources/js/src/overlay.js` (with the maths in `src/position.js`) is a **direct port of
`tedi/components/overlay/overlay-position.util.ts`**, not an invention. It keeps
the upstream algorithm's observable behaviour:

| Upstream | Ported |
|---|---|
| `POSITION_MAP` — 12 `side[-align]` placements, each with a ±8px base gap | yes, as `BASE_GAP` + the component's own `offset` |
| `auto` / `auto-start` / `auto-end` — try top, bottom, right, left | yes, first side with room wins |
| `preventOverflow` → append the opposite-direction fallback | yes, flip when the preferred side does not fit and the opposite does |
| `applyHorizontalPush` — horizontal-only shift back into the viewport | yes; the cross axis is deliberately left alone so the panel scrolls with its trigger |
| `calculateArrowOffset`, incl. the `padding + size * 0.7` edge margin | yes, verbatim |
| `getPlacementFromPositionChange` → `data-placement` | yes, as the `side` property |

**Deliberately not ported:** focus trapping inside the panel. Escape-to-close,
outside-click dismissal and focus-return-to-trigger **are** ported — they are
what makes the component usable rather than merely correct.

### The dropdown keyboard layer (`tediDropdown`)

`overlay()` positions and dismisses; it does not know what is inside the panel.
Tooltip and popover need nothing more. The dropdown does, because ARIA's
menu/listbox pattern puts the items outside the tab order and expects the
component to move focus between them — and the port emitted exactly that markup
(`role="menuitem"`, `tabindex="-1"` on every item) while shipping nothing to
drive it, which left keyboard users unable to reach the items at all.

`Alpine.data('tediDropdown')` composes `overlay()` and adds the focus half of
upstream's dropdown, spread across three files there:

| Upstream | Ported |
|---|---|
| `dropdown.component.ts` — `activeIndex`, `updateTabindexes()`, `focusFirst/Last/Active/Next/PrevItem()`, `setActiveToSelectedOrFirst()` | yes |
| `dropdown-item.component.ts` — `@HostListener('keydown')`: Arrow / Home / End / Enter / Space / Tab, and the disabled-`mousedown` guard | yes |
| `dropdown-trigger.directive.ts` — ArrowDown/ArrowUp open-and-focus | yes |
| `tabOutOfDropdown` + `getFocusableElements` (`tedi/utils/elements.util.ts`) | yes, verbatim |
| Focus **trap** inside the panel | no — and upstream has none either. Tab leaves the dropdown by design |

Behaviours that are easy to "improve" by accident, and must not be: arrow keys
do **not** wrap at the ends; disabled items keep their roving `tabindex` in a
menu but lose it in a listbox; opening focuses the selected (or first enabled)
item **even when opened by mouse**. All three are upstream's.

Two divergences, both because Blade has no component instances to query:

1. Angular reads items from `contentChildren(DropdownItemComponent)` and their
   `disabled()` / `value()` signals. The engine reads the DOM instead — the
   registry is `li[tedi-dropdown-item]`, disabled is `aria-disabled="true"`,
   and the listbox selection `setActiveToSelectedOrFirst` keys on is
   `aria-selected="true"`. Those attributes are already emitted, so this adds
   no markup; it does mean **the item template's ARIA is now load-bearing**.
2. Angular binds `keydown` per item; here one delegated listener sits on the
   panel (`x-on:keydown="menuKeydown($event)"`) and resolves the item with
   `closest()`. An anonymous Blade component has nowhere to hang per-instance
   Alpine state, and the handler needs the sibling list anyway.

**Enter/Space call `item.click()`.** Upstream invokes its own `onItemSelect()`;
this port must not, because the item's behaviour lives in the click handlers —
`x-on:click="hide(true)"` and whatever `wire:click` the consumer bound.
Synthesising the click is what keeps keyboard activation and mouse activation
on one path. Any future keyboard layer on a component whose actions are bound
in the template should do the same.

**Escape stays in `overlay()`.** It is already a document-level listener there
that closes and returns focus to the trigger, so neither the trigger's nor the
item's keydown handler re-binds it — a second handler would fire `hide` twice.

**One structural divergence.** CDK re-parents the pane into a
`.cdk-overlay-container` at `<body>`. The Blade panel stays where it was
written and is positioned `fixed` in viewport coordinates. That keeps the markup
a single tree and each component one root element (§6) — but a panel inside a
`transform`ed ancestor is then positioned relative to that ancestor. Document it
on the component; do not work around it by re-parenting.

### The markup contract

Every anchored component wires the same three refs, and nothing else:

```blade
{{-- tediDropdown for the dropdown; tooltip and popover use tediOverlay directly. --}}
<tedi-dropdown x-data="tediDropdown({ placement: 'bottom-start', offset: -4, matchTriggerWidth: true })">
    <tedi-dropdown-trigger x-ref="trigger" x-on:click="toggle()"
                           x-on:keydown="triggerKeydown($event)" ...>…</tedi-dropdown-trigger>

    <div class="tedi-dropdown__panel" x-ref="panel" x-show="open" x-cloak
         x-on:keydown="menuKeydown($event)"
         x-bind:data-placement="side">
        <div class="tedi-dropdown__arrow" x-ref="arrow"></div>
        …
    </div>
</tedi-dropdown>
```

- `x-ref="trigger"` — the anchor. `x-ref="panel"` — what gets `position/top/left`.
  `x-ref="arrow"` — optional; gets `left`/`top`.
- `x-show` (not `x-if`): the panel must exist in the DOM with its real class
  list even while closed, per §8.
- `x-cloak` on the panel, so it does not flash before Alpine boots. The rule
  behind it ships in `resources/scss/_alpine.scss` — Alpine provides no
  stylesheet of its own.
- **The panel and its arrow must be phrasing content — use `<span>`, never
  `<div>`.** An overlay trigger is often inline in running text, and `<p>` may
  only contain phrasing content, so the HTML parser auto-closes the paragraph
  and **hoists a `<div>` panel out of the component entirely** — out of the
  Alpine scope with it. The result is a panel whose `open` flips to `true` while
  it stays `display: none` forever, and a paragraph that breaks across two
  lines. Server-rendered HTML is well-formed, so *every class assertion still
  passes*; only a browser reveals it. Pack the wrapper tags with **no whitespace
  between them** — a text node renders as a visible gap inside a `<p>`. The SCSS
  keys on classes rather than elements and the panel is positioned, which
  blockifies it regardless of the `span` default, so nothing is lost.
  (Custom elements like `<tedi-tooltip-content>` are parsed as phrasing content
  already and need no change.)
- `data-placement` is **bound**, so per §4 it must come after
  `$attributes->class()` — as must every other `x-bind`.
- The engine's `offset` is *extra* px on top of the 8px base gap. Upstream
  dropdown replaces the base rather than adding to it (`Math.sign(offsetY) *
  offset`, default 4), so a dropdown passes `offset: -4` to land on the same 4px.
  Tooltip passes its own `offset` (default 4) and popover passes
  `withArrow ? 12 : 0` — both of which upstream adds on top, as here.

Every `tediOverlay` config key is documented at the function in `src/overlay.js`. Do
not add per-component positioning code to a template.

---

## 12. The `community/` namespace

§1 names `angular/tedi/components/**` as the port source. `angular/community/**`
is a **second, additive source** and everything above applies to it unchanged:
its components are `ViewEncapsulation.None` too, so their CSS is global and
BEM-classed exactly like `tedi/`'s.

Three things differ, and only these three:

1. **Group mapping.** The community tree's own grouping (`components/buttons`,
   `components/form`, `components/navigation`) maps onto the *existing*
   `resources/scss/components/<group>/` tree — there is no `community/` folder in
   this package. The Blade file follows §2's flattening rule as usual.
2. **Storybook title prefix is `Community/…`, not `TEDI-Ready/…`** — take it
   verbatim from the community story's `title`, as `Community/Form/FormField`
   already does.
3. **Path-only SCSS edits are allowed.** §2 forbids editing vendored SCSS, but a
   community stylesheet may `@use` a `tedi/` one by relative path
   (`floating-button.component.scss` reaches four levels up into
   `tedi/components/buttons/button/button.component.scss`). That path does not
   survive the move into `resources/scss/`. Rewrite **only the path**, leave
   every declaration untouched, and note the rewrite in a comment at the top of
   the vendored file so a re-sync knows what to redo.

Not every community class is `tedi-`-prefixed — `table-of-contents` is not. That
is upstream's naming, so it ports verbatim; note that the two class-existence
guardrails in `tests/IntegrityTest.php` only harvest `tedi-*` tokens, so an
unprefixed component's classes are covered by its parity test alone.

Upstream's own `CLAUDE.md` warns that `community/` is "not a reference for TEDI
patterns" — that is a warning about *their* code style, not about the rendered
markup. The port still mirrors the markup and class list exactly, per §4.

---

## 13. The `react/` namespace

§1's table used to rule the React package out as a port source, because CSS
Modules hash its class names. That reasoning holds for the **built dist** and
only for it. In the source it does not:

```scss
/* react/src/tedi/components/loaders/skeleton/skeleton.module.scss */
.tedi-skeleton { … }
```

```tsx
const SkeletonBEM = cn(styles['tedi-skeleton'], className);
```

The selectors are written as ordinary BEM, and the TSX looks them up by their
literal name. Hashing happens in the Vite build, downstream of both. A
`*.module.scss` therefore vendors into `resources/scss/` exactly like an Angular
`*.component.scss`, and everything in §§2–11 applies to a React port unchanged.

React is a **third, additive source**, after `tedi/` and `community/`. Use it for
components that exist *only* there — where a component exists in Angular too,
Angular remains the source, because that is what the two other trees were ported
from and re-syncing against two upstreams is how a port drifts.

Six things differ, and only these six.

### 13.1 The token check is the real feasibility gate

Because these styles were never part of the Angular build this package compiles,
nothing guarantees the installed `@tedi-design-system/core` defines the custom
properties they reference. **Before porting, grep core for every `var(--…)` the
stylesheet uses.** `skeleton.module.scss` wants `--loader-skeleton-radius` and
`--loader-skeleton-color`; both are in core 6.5, so it ports. A component whose
tokens are missing does not — and it fails silently, as an unstyled element
rather than a build error.

This replaces "are the class names stable" as the question to ask first.

### 13.2 Vendored filenames keep their `.module` suffix

`skeleton.module.scss` is vendored as `skeleton.module.scss`, not
`skeleton.component.scss`. §2's "never edit the copied SCSS" exists so a re-sync
is a plain file copy and a plain diff; renaming the file on the way in defeats
that for no gain. The suffix also reads as the provenance marker it is — a
`.module.scss` under `resources/scss/components/` came from React.

`@use` statements in `resources/scss/index.scss` therefore name the file in full.

### 13.3 `:global` blocks and React-only class names port verbatim, and are dead

React stylesheets occasionally reach outside their module with CSS Modules'
`:global`, and what they reach for is React's *own* class vocabulary — which is
not always Angular's:

```scss
/* date-time-field.module.scss */
:global .tedi-btn--link .tedi-btn__icon--left { … }
```

React's button is `tedi-btn`; Angular's — and therefore this package's — is
`tedi-button`. Sass compiles the block happily, and the resulting selector
matches nothing.

**Vendor it anyway, unedited, and note it in a comment at the top of the vendored
file.** Rewriting the class name would be inventing a rule TEDI does not ship,
and dropping the block would make the next re-sync diff lie. Where the dead rule
carried something the Blade component actually needs, that belongs in the
component, documented — not in the stylesheet.

### 13.4 Storybook titles: verbatim, except for case

Take the React story's `title` as-is, the way §12.2 does for `community/`. One
mechanical exception: React's own titles are inconsistently cased —
`TEDI-Ready/…`, `Tedi-Ready/…` and `Tedi-ready/…` all occur. Left alone they
would be three sidebar roots, and on a case-insensitive filesystem (macOS
default) the directories collide outright. **Normalise the case to `TEDI-Ready`,
change nothing else.**

Do not "fix" the rest. `TEDI-Ready/Content/Section` has no `Components/` segment
and `TEDI-Ready/Layout/TopNav` opens a new group beside it; both are upstream's
structure, and mirroring it is a smaller divergence than inventing a tidier one.

A React component with no `*.stories.tsx` (`multi-value-field`) gets no story
directory, and so — because Blast derives everything from directories — does not
appear in Storybook at all. That is the honest outcome; do not write stories
upstream does not have.

### 13.5 React's runtime introspection is wider than Angular's

§5 already says to translate DOM introspection into explicit props. React needs
it more often, because `Children.map` + `cloneElement` is idiomatic there and has
no server-side equivalent at all:

| React pattern | Blade |
|---|---|
| `cloneElement(child, { className })` — `print`, `scroll-visibility`, `hash-trigger` | a **wrapper element** carrying the classes, as `choicegroup` already does for an Angular directive |
| `Children.map` over typed children — `top-nav` reading its items to build the mobile nav | an explicit prop or slot; where the derived structure cannot be reconstructed, a documented gap |
| `ResizeObserver` / `useElementSize` measurement — `multi-value-field`'s `+N`, `affix`'s header offset | an explicit prop (`:visible-count`), documented as consumer-supplied |

### 13.6 Labels that Angular has no key for

`lang/*/tedi.php` is generated from Angular's `services/translation/translations.ts`.
React's `providers/label-provider/labels-map.ts` is a superset: it carries keys
for React-only components, in all three languages.

Add the missing keys **under the marked React block at the foot of each language
file**, copied from `labels-map.ts` — all three of `en`, `et` and `ru`, never a
subset and never a translation of your own. A label-map entry that is a function
of a count (`(count) => \`Veel ${count}\``) becomes a `:count` placeholder.

Keep the Angular-generated block above untouched, so regenerating it stays a
mechanical overwrite.
