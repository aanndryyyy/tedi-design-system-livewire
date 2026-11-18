# date-field-modal — doc changes for the parent to apply

Branch `date-field-modal`. Ports Angular 10.0.0-rc.21's
`form/date-field/date-field-modal/` plus the `modal`, `fullscreen` and
`calendarFullWidth` inputs onto `<tedi:date-field>`.

## Brief vs. repo reality (read first)

The brief says to have Confirm write the value into the date input "the same way
the existing popover selection does (fire `input`/`change` so `wire:model`
follows)". The popover has **no client-side selection**. The calendar is static
server markup, and its day buttons carry `data-date-key` for the consumer to bind
`wire:click` on. `display` is formatted text, and `wire:model` on
`<tedi:date-field>` lands on the wrapper `<div>`. So there was nothing to
copy. I kept the existing server-driven design rather than writing a JS
calendar selection engine:

- The draft is a new explicit prop, `draft` (CONVENTIONS §5, like `open`). The
  modal's calendar shows `draft ?? value`.
- **Confirm** dispatches a bubbling `tedi-date-field-confirm` DOM event, then
  closes with the unguarded `close()`.
- **Cancel** (`requestClose('programmatic')`, which is upstream's
  `ref.close(undefined)`), the header close button, the backdrop and Escape all
  go through `tediModal`'s existing cancelable `tedi-modal-close`.
- Both events bubble to the date-field root, where `$attributes` live. A
  consumer binds `wire:tedi-date-field-confirm="…"` (commit `draft`, set
  `open=false`) and `wire:tedi-modal-close="…"` (discard, `open=false`).
- Not verified with a live Livewire component: the storybook app is not
  installed in the worktree. Livewire 3 documents `wire:` on custom events, so
  this should work.

## README.md

**Divergence table, row at ~l.161.** Old row:

> | `date-field`, `time-field` | The panel renders under an explicit `open` prop, anchored with inline styles at upstream's first position — below the field, start-aligned for an input trigger and end-aligned for the button, 4px gap (`FIELD_OVERLAY_GAP`). CDK's flip and fallback positions, re-parenting and `hideOnScroll` are not ported. The modal branch, `useNativePicker`, `fullscreen` and `modal` are not ported |

Replace with two rows:

> | `date-field`, `time-field` | The panel renders under an explicit `open` prop, anchored with inline styles at upstream's first position — below the field, start-aligned for an input trigger and end-aligned for the button, 4px gap (`FIELD_OVERLAY_GAP`). CDK's flip and fallback positions, re-parenting and `hideOnScroll` are not ported. `useNativePicker` is not ported; `time-field`'s modal branch is not ported |
> | `date-field` | The modal branch is composed from the standalone `<tedi:modal>` (not `ModalService`), under the same `open` prop. `modal` takes `true`/`false`/`sm`/`md`/`lg`/`xl`; a breakpoint name is resolved in the browser by `tediDateField` (both branches render; without JS the popover shows). The selection is an explicit `draft` prop; Confirm dispatches `tedi-date-field-confirm`, every other close `tedi-modal-close`. `fullscreen` is declared but inert — the standalone modal has no fullscreen rule. The width cap is `tedi:modal`'s custom `width`, not a pane `maxWidth`. Single-mode "confirm on select" (`closeOnSelect`) is left to the consumer's day handler. The modal renders inside the field, so the surface's `:focus-within` shows while it is open; a breakpoint flip while open swaps branches instead of closing |

**Component table, row at ~l.438.** It is unchanged (`<tedi:date-field>`, `<tedi:date-input>` ⁴),
but **footnote ⁴ at ~l.499** changes from

> ⁴ Angular's `date-field-modal` and `time-picker-modal` are still not ported.

to

> ⁴ Angular's `date-field-modal` is ported as part of `<tedi:date-field>` (`modal`); `time-picker-modal` is still not ported.

**"What is ported" table, row at ~l.518.** Old row:

> | `<tedi:date-field>`, `<tedi:time-field>` | Input, tags, trigger, and the panel under an `open` prop, anchored below the field | Open/close behaviour, CDK's flip/fallback placement, and the mobile modal branch |

New row:

> | `<tedi:date-field>`, `<tedi:time-field>` | Input, tags, trigger, and the panel under an `open` prop, anchored below the field; `date-field`'s modal branch (`modal`, `calendar-full-width`, `draft`) | Open/close behaviour, CDK's flip/fallback placement, `date-field`'s `fullscreen` (inert), and `time-field`'s mobile modal branch |

**"Would gain" table, row at ~l.653.** Old row:

> | `date-field`, `time-field`, `pagination` | Their overlay / modal branches |

New row:

> | `date-field`, `time-field`, `pagination` | Their overlay placement; `time-field`'s and `pagination`'s modal branches; `date-field`'s `fullscreen`, once `tedi:modal` gains standalone fullscreen styles |

**Skipped stories, row at ~l.761.** Old row:

> | `DateField`, `TimeField`: NativePicker, MobileModal · `DateField`: CustomFormatAndParse, CustomLocale | Breakpoint props, the unported modal branch, JS format/parse callables, and `localeCode` |

New row (`DateField: MobileModal` removed):

> | `DateField`, `TimeField`: NativePicker · `TimeField`: MobileModal · `DateField`: CustomFormatAndParse, CustomLocale | Breakpoint props, the unported time-field modal branch, JS format/parse callables, and `localeCode` |

**Counts.**
- ~l.741: "661 stories across 87 components" becomes **662** stories (+1, `DateField/mobile-modal`). The component count is unchanged.
- ~l.705: "188 stories have a per-story description" becomes **189** (+1, `mobile-modal.md`).

**Optional, "Differences you will notice" list at ~l.332.** Add:

> - **`date-field`**: `modal` opens the standalone `<tedi:modal>` under the same `open` prop; confirm/cancel are DOM events (`tedi-date-field-confirm` / `tedi-modal-close`) and the selection-in-progress is the `draft` prop.

## CHANGELOG.md, "Unreleased"

- `date-field`: the modal branch (Angular `date-field-modal`). New props: `modal`
  (`true` | `false` | `sm`/`md`/`lg`/`xl`, a breakpoint resolved client-side by
  the new `tediDateField` behaviour), `calendar-full-width`, `draft`, and an
  inert `fullscreen`. Confirm dispatches `tedi-date-field-confirm`; Cancel,
  Escape, the backdrop and the close button dispatch `tedi-modal-close`. New
  story: DateField · Mobile Modal; Default gains the `modal`, `fullscreen` and
  `calendarFullWidth` controls.

No breaking change: `modal` defaults to `false`, and that output is
byte-identical to before. Before this change `modal`, `fullscreen`, `draft` and
`calendar-full-width` leaked onto the root as stray attributes; now they are
props.

## CONVENTIONS.md

**§8, list of `Alpine.data()` behaviours.** Add `tediDateField`.

**§8 JS-layout table.** Add a row:

> | `src/date-field.js` | `dateField` | Resolves a breakpoint-name `modal` on `tedi:date-field`: one `matchMedia` query off `breakpoint.js`'s BREAKPOINTS sets `modalActive`. The popover is `x-show`n off it and the modal's `tediModal` follows it with an `x-effect`. It holds viewport state only, never open state, so a Livewire morph cannot leave a stale flag |

**§7 item 1** could note the exception: `date-field`'s `modal` is a breakpoint
prop resolved client-side, because it chooses between two whole branches
rather than a class.

## Follow-ups / left out

- **`fullscreen`**: upstream's rules are `.tedi-modal-dialog--fullscreen[-bp]`,
  which belong to the `ModalService` pane. The standalone `.tedi-modal__dialog`
  has no fullscreen rule, and `.tedi-modal--sm .tedi-modal__dialog` would beat
  one anyway. This needs `tedi:modal` to support fullscreen first; the modal is
  outside this stream's ownership.
- Not ported, per the brief: `useNativePicker`, time-field's
  `time-picker-modal`, and pagination's option-picker modal.
- `closeOnSelect` (upstream confirms on a day click in single mode) is
  runtime-only.
- The calendar footer slot is not forwarded into the modal, as upstream says.

## Test-running caveat for the parent

With `vendor/` symlinked from the main checkout, Composer's `$baseDir`
resolves to the **main checkout**, so a plain `vendor/bin/phpunit` in the
worktree tests the main checkout's `src/` and `tests/`, not the worktree's.
I ran with a bootstrap that prepends the worktree's PSR-4 paths:
`vendor/bin/phpunit --bootstrap <bootstrap.php>`, where the bootstrap does
`$l = require 'vendor/autoload.php'; $l->addPsr4('Tedi\\Livewire\\', WT.'/src', true); $l->addPsr4('Tedi\\Livewire\\Tests\\', WT.'/tests', true);`.
Result after `npm run build`: 1439 tests, all passing. Against the
**committed** `dist/`, `IntegrityTest` fails 2 tests (the source-literal scan
and the matrix harvest). The reason is that `dist/` is not committed by rule
and so lacks `.tedi-date-field-modal`, `.tedi-date-field-modal--full-width`
and `.tedi-date-field-modal__content`. Rebuild `dist/` when integrating.
Other streams' workers probably hit the same trap.
