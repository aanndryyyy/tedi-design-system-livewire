# Story authoring contract

Every file under `storybook/resources/views/stories/` follows the rules below.
They exist so the Blade Storybook comes out structurally identical to the Angular
one at [TEDI-Design-System/angular](https://github.com/TEDI-Design-System/angular)
(`.storybook/main.ts` + the `title` of each `*.stories.ts`).

## 1. Where a story file goes

Blast derives the Storybook **title from the directory path** and the **story
name from the file name**. So the directory path must be the Angular story's
`title`, verbatim, including capitalisation:

```
Angular:  title: "TEDI-Ready/Components/Buttons/Button"
          export const Default / export const FullWidth

Blade:    storybook/resources/views/stories/TEDI-Ready/Components/Buttons/Button/default.blade.php
          storybook/resources/views/stories/TEDI-Ready/Components/Buttons/Button/full-width.blade.php
```

One directory per component, one file per Angular story export. File names are
kebab-case of the export name.

## 2. The `@storybook` directive

Every file starts with it. Keys used in this project:

| Key | Rule |
|---|---|
| `name` | The story label, matching how Storybook auto-formats the Angular export name — `FullWidth` → `'Full Width'`. Always set it; Blast would otherwise title-case the file name. |
| `order` | 1-based position of the export in the Angular story file, so the sidebar order matches. Blast sorts the generated `.stories.json` by it; `.storybook/main.js` + `preview.js` then restore that sequence in the sidebar, which Storybook would otherwise show alphabetically (see the comment in `main.js`). Because `main.js` reads the JSON once at boot, a new story or a changed `order` needs a Storybook restart, not just the watcher. |
| `status` | `'stable'` when the port has no divergence, `'subset'` when the component is a documented subset (see §5). |
| `design` | The Figma URL from the Angular story's doc comment, when it has one. |
| `args` | Every variable the template reads. No exceptions — see §3. |
| `argTypes` | Ported from the Angular `argTypes`: same `control`, same `options`, same `description`, same `table.category` / `table.defaultValue` / `table.type.summary`. Drop `table.type.detail`. |

## 3. The template

- Use the short syntax: `<tedi:button>`, `<tedi:header.profile>`, `<tedi:form.label>`.
- **Every variable the template reads must appear in `args`.** Storybook only
  passes declared args, and an undefined variable is a 500 in the preview.
- Booleans arrive as real booleans; cast defensively where the component expects
  one: `:disabled="(bool) $disabled"`.
- Empty-string args standing in for "no value" get normalised in the template:
  `:icon-start="$iconStart ?: null"`.
- **Never write `null` as an `args` value — use `''`.** Blast serialises `args`
  into the `.stories.json`, and the JSON-to-CSF loader that Storybook compiles
  it with (`@storybook/preset-server-webpack/dist/loader.js`) calls
  `Object.keys()` on every object-typed value. `typeof null === 'object'`, so a
  null throws, and the loader catches the error and silently hands webpack the
  raw JSON instead. The dev server merely logs it; `blast:publish` fails the
  whole preview bundle, so the deployed Storybook renders no stories at all.
  `''` is falsy exactly where `null` was, so the rendering is unchanged.
- Prefer one `args` entry per control the Angular story exposes. Composition-only
  stories (a card with a header and three rows) don't need controls for every
  slot — hardcode the composition and expose only what the Angular story exposed.
- Keep the Estonian sample copy the Angular stories use where they use it.

## 4. Worked example

`TEDI-Ready/Components/Buttons/Button/default.blade.php` is the reference
implementation. Read it before writing anything else.

## 5. What NOT to port

This package is a **template-only port**; several Angular behaviours have no
Blade equivalent. They are listed in the README's divergence table and
CONVENTIONS.md §7. When an Angular story exists only to exercise one of them:

**Skip the story. Do not fake it.** Do not write a popover story that silently
renders the modal branch, and do not invent props the Blade component doesn't
declare.

The dropped areas, in short:

- Breakpoint props (`xs`/`sm`/`md`/`lg`/`xl`/`xxl`) — not ported anywhere.
- `output()` events — no event stories.
- Toast placement — `<tedi:toast>` renders markup only and leaves placement to
  the consumer.
- Open/close state driven from Angular — `pagination`'s option-picker modal, the
  `tabs` overflow "More" dropdown, `header.profile` / `header.role` popover
  branch, `footer` mobile layouts.
- Documented subsets: `<tedi:select>` (native `<select>` only), `<tedi:ellipsis>`
  (CSS clamp, no hover reveal), `<tedi:scroll-fade>` (static markup).
Report every skip with its reason — the skips are documented in the repo README.

### Hover / Active / Focus matrix stories

These **are** portable: `storybook-addon-pseudo-states` is installed (by
`start.sh` — see the comment there and in `.storybook/main.js` for why it can't
be an ordinary dependency). Port the matrix in full.

Blast's `@storybook` directive reads a fixed set of keys and has no
`parameters` passthrough, so the addon's `parameters.pseudo` can't come from the
blade file directly. `args` is the one key that reaches the story untouched, so
a matrix story opts in with:

```blade
'args' => ['pseudoStates' => true],
'argTypes' => ['pseudoStates' => ['table' => ['disable' => true]]],
```

`true` means the default selector map — `#Hover`, `#Active`, `#Focus` — which
`.storybook/preview.js` turns into `parameters.pseudo`. Put the matching `id` on
the element of each row:

```blade
@php $states = ['Default', 'Hover', 'Active', 'Focus']; @endphp

@foreach ($states as $state)
    <tedi:button id="{{ $state }}" variant="primary">Create</tedi:button>
@endforeach
```

When the Angular story's `pseudo` map targets something else — two ids per row,
or a descendant of a wrapper — pass that map instead of `true`, copying the
Angular selectors verbatim:

```blade
'args' => ['pseudoStates' => [
    'hover' => ['#PrimaryHover', '#SecondaryHover'],
    'active' => ['#PrimaryActive', '#SecondaryActive'],
    'focusVisible' => ['#PrimaryFocus', '#SecondaryFocus'],
]],
```

The reference implementations are
`TEDI-Ready/Components/Buttons/Button/primary.blade.php` (default map) and
`TEDI-Ready/Components/Form/Radio/radio-card-states.blade.php` (custom map).

Two things the addon does **not** cover, so they stay skips: rows that are
`Selected`/`Disabled` are ordinary markup (`default-value`, `:disabled`), not
pseudo-classes, and Angular's per-story `globals.backgrounds` has no Blast
passthrough — reproduce the brand backdrop with a wrapper div.

## 6. Docs pages

A component gets a Storybook **Docs** page as soon as its directory holds any
`.md` file. Two files feed it, both ported from the same Angular story file the
stories came from:

| File | Becomes | Source in Angular, in precedence order |
|---|---|---|
| `README.md` | the component description | `parameters.docs.description.component` on the meta; otherwise the last doc comment before `export default` (a blank line may sit between them) |
| `<story>.md` | that story's description | `parameters.docs.description.story` on the `export const`; otherwise the doc comment directly above it |

`<story>.md` is named after the blade file — `full-width.blade.php` →
`full-width.md`.

**Copy the text verbatim.** Strip the doc-comment syntax (`/**`, `*/`, ` * `) or
unescape the string literal (`\n`, `\"`), and change nothing else. Keep the
inline HTML — the Figma/Zeroheight `<a>` links render as-is. A README that is
only those two links is complete; don't pad it.

If the Angular story has no description, **write no prose of your own.** Leave
the file out, or — when the component would otherwise get no Docs page at all —
make it an HTML comment saying why (see `Community/Form/FormField/README.md`).

§5 applies here too: when the Angular text documents something this package
doesn't have (Angular services, CDK re-parenting into an overlay container),
don't port it as if it worked. Replace it with a note and add a row to the README's divergence table.

## 6a. Never write an angle-bracketed `<tedi:…>` inside `@storybook([...])`

Blade's component-tag compiler runs over the **whole file**, including the text
inside the `@storybook` block. A `<tedi:modal-header>` written in an `argTypes`
description is compiled as a real component tag, not read as prose.

Two failure shapes, neither of which names the real cause:

| What you wrote in a description | What you get |
|---|---|
| A paired tag (`<tedi:x>…</tedi:x>`) | `PHP Parse error: unexpected token "endif", expecting end of file` |
| An unpaired opening tag | `Undefined variable $component` — in a file that has no `$component` |

The second is worse when the template further down contains a genuine closing
tag of the same name: the compiler pairs the *description's* opening tag with
that real closing tag, the pairing straddles an `@if`, and the `@endif` is
consumed.

Write the tag without angle brackets — `` `tedi:modal-header` `` — in every
`description`, `summary` and other prose string.

## 6b. Never write `]` immediately followed by `)` inside `@storybook([...])`

Blast finds the block with an **ungreedy** regex — `/@storybook[ \t]*\(\[(.*)\]\)/sU`
in `GenerateStories.php` — so it ends the block at the *first* `])` in the file,
not at the matching one. The most natural way to hit this is a TypeScript array
type inside parentheses:

```php
'description' => 'Toggled on (value becomes string[]).',   // ← ends the block here
```

Everything after that point is discarded, and `eval()` chokes on the truncated
array with a message that points at a line number inside the *block*, never at
the string that caused it:

| What you wrote | What you get |
|---|---|
| `string[])` mid-description | `syntax error, unexpected string content "…"` |
| the same, with a `[` still open above it | `Unclosed '[' on line N` |

Spell the type out — "an array of strings" — or move the closing paren away from
the bracket. The same applies to prose in a `summary`, and to comments: a comment
that *quotes* the offending sequence breaks the block just as a description does.

## 7. Before you finish

Check the props you used actually exist. The component source is the authority:
`resources/views/components/<name>.blade.php`, `@props([...])` at the top.
