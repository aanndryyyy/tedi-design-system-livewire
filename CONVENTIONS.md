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
| `react` | CSS Modules (`*.module.scss`) → hashed class names at build time | No — class names aren't stable |
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
3. **Tooltip/dropdown/modal positioning** (CDK Overlay / floating-ui) is out of
   scope for the template-only phase.
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
   comment for the full rationale per prop).

---

## 8. Interactivity policy

For this phase, components are **template-only** unless listed below. Where a
component is inert without JS (tabs, carousel, accordion, pagination), ship the
correct TEDI markup **plus minimal Alpine** in
`resources/js/tedi.js`. Alpine ships with Livewire, so this adds no dependency.

Alpine usage must be additive: the markup and classes stay identical to Angular,
with `x-data` / `x-on` layered on top. A consumer who strips the JS still gets
correct static markup.

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
