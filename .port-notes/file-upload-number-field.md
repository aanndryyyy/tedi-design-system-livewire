# Port notes — `file-upload-number-field`

Branch `file-upload-number-field`, on 8553d19. Number field: `3b896ff`
(the port) and `1a055fa` (input found by class, not `x-ref`). File upload:
`0fcd7c6` (the pipeline and the five stories) and a docs/matrix follow-up.
Squash per component as you like.

`dist/` is not committed. Rebuild with `npm run build:js` after merging (the
CSS is unchanged). `node scripts/generate-lang.cjs <angular>/src/tedi/services/translation/translations.ts`
produced no diff: every key both ports need (`file-upload.extension-rejected`,
`-size-rejected`, `-duplicates-skipped`, `-added`, `-removed`, `-cleared`,
`numberField.quantityUpdated` and `.suffix`) was already generated. (In a
worktree the script needs the source path as an argument, because its default
`.reference/` path is relative to the checkout.)

---

## README.md

### Divergences table (around line 154): change the row

Old:

```
| `number-field` | Increment/decrement buttons are inert; attach handlers via `decrement-attributes` / `increment-attributes`. `LiveAnnouncer` and wrapper click-to-focus not ported |
```

New:

```
| `number-field` | The step buttons, their live disabled / invalid state, the value announcement and wrapper click-to-focus need the bundled `tediNumberField` behaviour; without it the buttons keep their server-rendered state and do nothing. A step is clamped to `min` / `max` in the direction of travel and rounded to the step's decimals (upstream steps past a bound from an off-step value, and adds raw floats). The announcement uses the package's root-level announcer, not CDK's `LiveAnnouncer`. `decrement-attributes` / `increment-attributes` still merge onto the buttons; an `x-on:click` there runs after the step |
```

### Divergences table: add a row for `file-upload`

There is no `file-upload` row in the divergences table today (it lives only in
the subset table). Add:

```
| `file-upload` | The list is server state (`:files`). The selection pipeline runs in the bundled `tediFileUpload` before Livewire sees the files: it rewrites `input.files` to the accepted files, appended for `multiple` (so Livewire re-uploads the earlier files with the new ones), and stops a selection that adds nothing. `validator` is a JavaScript expression evaluated by Alpine (not a PHP callable; not CSP-safe; never user input). `keep-rejected-files` works in multiple fields only, and kept files never reach the input; their tags follow the server's. A selection that is stopped never reaches a consumer's `x-on:change`. The client state clears when the server list goes from some files to none, not on a server reset of an already-empty list |
```

### "Upgrading" → "Breaking changes in the Angular 10 sync" (line ~216): add

```
- **`number-field`**: the increment and decrement buttons now step the value
  themselves (bundled `tediNumberField`): they write the input and fire `input`
  and `change`, so `wire:model` / `x-model` follow. If you wired the buttons
  through `decrement-attributes` / `increment-attributes` with a `wire:click`
  (or `x-on:click`) that changes the value, **remove it** — the value would now
  step twice. Handlers that do something else can stay; an `x-on:click` there
  runs after the step. The root `tedi-number-field` now carries the `x-data`,
  so an `x-ref` you put on the input resolves inside the number field's scope:
  a parent's `$refs.<name>` no longer reaches it.
- **`file-upload`**: `accept`, `max-size` and the new `validator` now filter the
  selection in the browser before `wire:model` uploads it, and a `multiple`
  field appends each selection to the previous one (Livewire re-uploads the
  whole list, so merge by name or replace the property). Files the field
  rejects are never uploaded. The input no longer carries an
  `x-on:change="handleSelection($event)"`; a selection that adds nothing never
  reaches a consumer's `x-on:change`. New props: `validator`,
  `keep-rejected-files`.
```

Also amend the existing Angular-10 `file-upload` bullet in that section if it
says the selection pipeline is not ported (line ~261 onwards describes the
re-port; it ends "The add button needs `dist/tedi.js` (`tediFileUpload`)." —
change that sentence to: "The add button and the client-side selection
pipeline need `dist/tedi.js` (`tediFileUpload`).").

### "Shipped as a documented subset" table (lines ~519–520)

Old (delete — the number field is no longer a subset):

```
| `<tedi:number-field>` | Full markup with correct disabled states | Button behaviour — wire it via `increment-attributes` / `decrement-attributes` |
```

Old (change):

```
| `<tedi:file-upload>` | The full markup and ARIA, a native `<input type="file">` that `wire:model` binds, the file list rendered from `:files` (`isValid`, `isLoading`), the restrictions hint, error / success / hint texts, the form-field context, and Alpine for the picker, label naming and announcements | The client-side selection pipeline — appending to the list, `accept` / `maxSize` / `validator` checks, duplicate skipping, `keepRejectedFiles`, the rejection summary — and the ControlValueAccessor. The below-`md` switch is the explicit `stacked` prop |
```

New:

```
| `<tedi:file-upload>` | The full markup and ARIA, a native `<input type="file">` that `wire:model` binds, the file list rendered from `:files` (`isValid`, `isLoading`), the restrictions hint, error / success / hint texts, the form-field context, and Alpine for the picker, label naming, announcements and the client-side selection pipeline: appending, `accept` / `max-size` / `validator` checks, duplicate skipping, `keep-rejected-files` and the rejection summary | The ControlValueAccessor (`rejectedFiles` error, touched / dirty) and the `fileRemove` / `filesChange` outputs; `keep-rejected-files` in a single-file field; `validator` as a function (it is a JS expression). The below-`md` switch is the explicit `stacked` prop |
```

### "Angular stories with no Blade equivalent" (line ~741, ~762)

- Delete the row:

  ```
  | `FileUpload`: DiscardingRejectedFiles, KeepingRejectedFiles, WithValidator, ControlledClearing, WithReactiveForms | The unported client-side selection pipeline and Angular reactive forms — validate on the server and use `wire:model` |
  ```

- Count: "661 stories across 87 components are ported." → **666 stories across
  87 components** (+5 FileUpload stories; components unchanged).
- Docs section (line ~705): "188 stories have a per-story description" →
  **193** (+5: `discarding-rejected-files.md`, `keeping-rejected-files.md`,
  `with-validator.md`, `controlled-clearing.md`, `with-reactive-forms.md`).
  `with-reactive-forms.md` and `controlled-clearing.md` replace upstream's
  text with a note (CONTRACT.md §6), so add two rows to the skipped-stories
  table, beside Toast's Docs-page row:

  ```
  | `FileUpload` Docs page: the "With Reactive Forms" description | Documents Angular reactive forms (`FormControl`, `Validators.required`, the `rejectedFiles` error). Replaced by a note describing the Livewire equivalent (`wire:model` plus server validation) |
  | `FileUpload` Docs page: the "Controlled Clearing" description | Documents Angular's two-way `[(files)]` binding, and says a one-way binding never reaches the field again — untrue for `:files`, which re-renders on every request. Replaced by a note |
  ```

`ReadmeTest` passed without these edits, so it does not pin either count.

### Storybook section: a note on the two Livewire-backed stories

`FileUpload` "Controlled Clearing" and "With Reactive Forms" render real
Livewire single-file components
(`storybook/resources/views/livewire/file-upload-controlled-clearing.blade.php`,
`…/file-upload-with-validation.blade.php`; Livewire 4 finds them through its
default `resources/views/livewire` location). Their first render works in
Storybook, and so does the client-side pipeline, but **every Livewire round
trip answers 419**: Blast registers its `/storybook_preview` routes without the
`web` middleware, so the page has no session and `@livewireScripts` emits an
empty CSRF token (verified). In the dev Storybook (`:6006`) the request is also
cross-origin. Both stories' `.md` say so. Suggested README sentence, under
"Storybook":

> Two stories (`FileUpload`: Controlled Clearing, With Reactive Forms) are backed
> by Livewire components. Blast's preview routes run without the `web`
> middleware, so their server round trips fail with 419 in Storybook; they work
> in an app that serves them with a session.

Making them work in Storybook would mean putting the Blast preview route in the
`web` group (storybook app config, outside this stream's ownership). I verified
both components end to end (real uploads, morphs, clearing, validation)
through a temporary `web`-middleware route that is not committed.

---

## CHANGELOG.md — Unreleased (Angular 10 sync section)

Replace the last sentence of the existing `file-upload` bullet ("The selection
pipeline (accept / max-size / duplicate validation, rejected files) stays
server-side.") with: "The selection pipeline — `accept` / `max-size` /
`validator` checks, duplicate skipping, appending, `keep-rejected-files` and
the rejection summary — runs in `tediFileUpload` before `wire:model` uploads."

Add:

```
- **BREAKING** `number-field`: the step buttons work (`tediNumberField`): ±step,
  clamped to min/max, `input` + `change` fired so `wire:model` follows, live
  disabled / invalid state, the `numberField.quantityUpdated` announcement and
  wrapper click-to-focus. A `wire:click` that stepped the value through
  `increment-` / `decrement-attributes` now double-steps: remove it.
- `file-upload`: client-side selection pipeline (see above), new `validator`
  (JavaScript expression) and `keep-rejected-files` props, and the five
  previously skipped stories (Validation: discarding / keeping rejected files,
  With Validator, Controlled Clearing, With Reactive Forms).
```

---

## CONVENTIONS.md §8

In the behaviour list ("currently `tediBreakpoint`, … `tediProcessTimeline`
and `tediRating`"), add `tediNumberField`.

Change the `src/file-upload.js` row:

Old:

```
| `src/file-upload.js` | `fileUpload` | Opens the picker, names the add button (and the read-only list) by the `label[for]` it finds, re-applied after a Livewire morph, and the added / removed / cleared announcements. The file list itself is server state |
```

New:

```
| `src/file-upload.js` | `fileUpload` | The selection pipeline in a capture-phase `change` listener on the root (so it runs before Livewire's `wire:model` handler): duplicates, `accept` / `maxSize` / `validator`, and `input.files` rewritten with a `DataTransfer` (appended for `multiple`); kept rejected files, the rejection summary and a client clear button cloned from an inert `<template>` of prototypes and re-applied after every Livewire morph from the template's `data-state`. Also the picker, label naming and announcements. The file list itself is server state |
```

Add a row (after `src/rating.js`):

```
| `src/number-field.js` | `numberField` | The step buttons (±step, clamped, decimal-safe; fires `input` and `change`), the live disabled / invalid state recomputed on input, after a step and after a Livewire morph, and the polite value announcement |
```

Change the `src/announcer.js` row's notes if it names its users: it is now used
by `file-upload` and `number-field` (the `tedi.js` header already says so).

Worth recording somewhere in §8/§11 (a trap hit in this stream): inside an
`Alpine.data()` method invoked from a **child's** directive (`x-on:click` on a
cloned button), `this.$el` is that child, not the component root. Keep the root
from `init()` (`this._root = this.$el`) in any behaviour whose methods query
the DOM.

---

## Verification

- `vendor/bin/phpunit`: 1432 tests, all passing (no README-coupled failure).
- `npm run build:js` builds; CSS unchanged (`npm run build:css` not affected).
- Browser-verified on the storybook host app (`php artisan serve`, previews at
  `/storybook_preview/...`) and, for Livewire behaviour, a temporary session
  route: number field stepping (decimal step, clamping, disabled / invalid
  state, events, announcements, wrapper focus, re-sync after a server-side
  value change and a server `invalid` toggle); file upload discarding /
  keeping / validator stories, duplicates, appending, rejected-tag removal,
  client clear, real Livewire uploads with append, outside clearing, server
  validation, and client tags + summary surviving a Livewire morph.
- Not verified inside the Storybook UI (`:6006`) itself — only the preview
  routes it renders.

## Known limits (documented in the component docblocks)

- `keep-rejected-files` in a single-file field discards instead (upstream
  replaces the listed file with the rejected one).
- Client tags always follow the server's tags.
- Under Livewire, appending re-uploads earlier files.
- A server reset of an already-empty list does not clear kept rejected files
  (the client clears on a server transition from some files to none).
- Tooling: my worktree's `vendor/` (and `storybook/vendor/`) are real
  directories with `autoload.php`, `composer/` and `bin/` copied and every
  other entry symlinked to the main checkout, because a plain `vendor` symlink
  makes Composer's autoloader resolve `Tedi\Livewire\` to the main checkout's
  `src/` (and so its views). Nothing was installed.
