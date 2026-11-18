# Port notes: toast-container

Ports Angular's `tedi-toast-container` and `ToastService` (with
`ToastAnnouncerService`, already ported as `resources/js/src/announcer.js`).
New: `<tedi:toast-container>`, the `tediToastContainer` behaviour in
`resources/js/src/toast-container.js`, `window.TediToast`, the `tedi-toast`
window event, and a `position` prop on `<tedi:toast>`.

These are the doc edits the parent should make. This stream did not touch
README.md, CHANGELOG.md, CONVENTIONS.md or storybook/CONTRACT.md.

## README.md

### §"Known divergences", item 3 (around line 124)

Old sentence:

> (CONVENTIONS.md §11). The toast's placement is still the consumer's job.

Replace with:

> (CONVENTIONS.md §11). Toasts are placed by `<tedi:toast-container>` (see
> its row below).

### Divergence table row (around line 148)

Old:

> | `toast` | Placement/animation, the auto-close timer (hover/focus pause) and `ToastService`'s announcer not ported; `role` and `pauseOnHover` accepted but inert — the toast is never a live region |

Replace with:

> | `toast`, `toast-container` | `ToastService` is `window.TediToast` plus the `tedi-toast` window event (`$this->dispatch('tedi-toast', …)`), and the container is placed once in the layout instead of being created on demand in a CDK overlay. Toasts in the container's slot render in the bottom-right group and are moved to their `position` (a Blade extension on `<tedi:toast>`), timed and announced when the bundled `tediToastContainer` boots. The container is `wire:ignore`d, so its slot is read on the first render only. Only `TediToast.danger()` defaults `role` to `alert`, as upstream; the event calls `show()`, so pass `role: 'alert'` yourself. Every container on a page shares one service, and toasts go to the most recently initialised one. The vendored stylesheet's `--z-index-toast` is not defined by core 6.13, so the container's `z-index` is `auto` (upstream gets its stacking from CDK's overlay container); define the token to raise it. A `<tedi:toast>` outside a container is static markup |

### Component mapping table (around line 472) and footnote ³ (line 498)

Old:

> | `notifications/toast` | `<tedi:toast>` ³ |

Replace with:

> | `notifications/toast` | `<tedi:toast>`, `<tedi:toast-container>` ³ |

Old footnote:

> ³ Visual markup only — overlay placement is out of scope (see divergences).

Replace with:

> ³ Placement, the timer and announcements need `<tedi:toast-container>` and the bundled `tediToastContainer` (see divergences).

### "Not ported" table (around line 656): delete this row

> | `toast` | Its `tedi-toast-container`. Angular's `ToastService` spawns toasts into a CDK-positioned container; `<tedi:toast>` renders one toast's markup and leaves placement to the consumer |

The paragraph above that table says "plus four places". It listed five rows
before this change and lists four after the deletion, so the wording becomes
correct as it stands.

### Skipped-stories table: delete both rows (around lines 751–752)

> | `Toast`: Positions, HoverBehavior, CustomTimerForAutoclose, PersistentToast, WCAGCompliance | Overlay placement, the JS auto-close timer and `ToastService`'s announcer |

> | `Toast` Docs page: the "Usage" section | Documents Angular's `ToastService`, which spawns toasts into a CDK Overlay container. `<tedi:toast>` renders markup only and leaves placement to the consumer, so the section is replaced by a note saying so |

### Counts

- Line 741: "661 stories across 87 components are ported" → **666** (+5:
  Positions, HoverBehavior, CustomTimerForAutoclose, PersistentToast,
  WCAGCompliance). The component count stays 87: the container shares the Toast
  story directory, as upstream's container has no story file of its own.
- Line 705: "188 stories have a per-story description" → **193** (+5 new `.md`
  files).
- Line 410 (component folder count, 75) does not change. The container lives in
  upstream's `notifications/toast` folder.

### Upgrading, Angular 9 list (around line 337), optional

> - **`toast`**: the inner alert is `role="none"`; the toast is never a live
>   region, and its `role` prop is inert.

The first half is still true. "and its `role` prop is inert" no longer is: it
picks the container's announcement politeness. Either leave the historical entry
as written or add "(live again with `tedi:toast-container`)". This is not a new
breaking change.

## CHANGELOG.md, under "Unreleased"

- New: `tedi:toast-container`, upstream's `tedi-toast-container` plus
  `ToastService`. Place it once in the layout, then show toasts with
  `TediToast.info|success|warning|danger(title, content, options)` /
  `TediToast.show(config)` / `TediToast.close(id)`, or from Livewire with
  `$this->dispatch('tedi-toast', title: …, type: …)`. It provides the four
  positions, the `duration` auto-close with progress bar, pause on hover and
  focus (`pauseOnHover`), the slide-in/out animations, the close button, and
  screen-reader announcements through the shared announcer regions
  (`role` status → polite, alert → assertive, none → silent). Server-rendered
  `tedi:toast`s in its slot are adopted on load. Runs in the new
  `tediToastContainer` behaviour.
- `tedi:toast`: `role`, `duration` and `pauseOnHover` are live inside a
  container. New `position` prop (Blade extension). The toast now emits
  `data-duration`, `data-role`, `data-pause-on-hover` and, when set,
  `data-position`.
- Storybook: the Toast stories Positions, HoverBehavior,
  CustomTimerForAutoclose, PersistentToast and WCAGCompliance are ported. The
  Docs page's Usage section is restored. Default and WithIcon are upstream's
  button demos again.

No Upgrading (breaking) entry. Existing `<tedi:toast>` markup is unchanged
apart from the added `data-*` attributes.

## CONVENTIONS.md

§8, the sentence listing the `resources/js/` behaviours: add
`tediToastContainer` (for example after `tediRating`).

§8 JS layout table, new row (after `src/rating.js`):

> | `src/toast-container.js` | `toastContainer`, `TediToast` | Upstream's `ToastService` + `toast-container.component.ts`. Clones a `tedi:toast` `<template>` prototype per toast, keeps the position groups, and runs the timer, pause on hover/focus, exit and close as upstream. Announces through `announcer.js`. The window `tedi-toast` listener and `window.TediToast` are registered once at module load and route to the most recent container, so several containers on a page never duplicate a toast. Slotted server toasts are adopted on init; the root is `wire:ignore`d |

Also update the `src/announcer.js` row's note: "(file-upload)" → "(file-upload,
toast-container)" if the row names its users. It doesn't today. The
`resources/js/tedi.js` header comment already lists the new module, so there is
nothing to do there.

## storybook/CONTRACT.md §5

Delete this bullet under "The dropped areas, in short:"

> - Toast placement — `<tedi:toast>` renders markup only and leaves placement to
>   the consumer.

## Verification note

The full phpunit suite passes (1429 tests). This worktree's `vendor/` is a
symlink to the main checkout, and Composer's autoloader resolves `$baseDir`
through it to the main checkout's `src/` and `tests/`. So a plain
`vendor/bin/phpunit` in a worktree tests the **main** checkout's sources. The
run above used a scratch bootstrap that re-points the `Tedi\Livewire\` PSR-4
roots at the worktree.
