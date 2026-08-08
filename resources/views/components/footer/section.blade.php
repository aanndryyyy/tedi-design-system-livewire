{{--
    TEDI Footer Section.
    Port of angular/tedi/components/layout/footer/footer-section/footer-section.component.{ts,html}

    Divergences (CONVENTIONS.md §7 — breakpoint state is not ported):
    - `hideIcon` (`isBelowBreakpoint('lg')`) is dropped, so `icon` always renders
      when given.
    - `applyCollapse` was `collapse() && mobileLayout()`; without `mobileLayout`
      it collapses to just `collapse`, i.e. the section is always collapsible
      when `collapse` is true, not only below the `sm` breakpoint.
    - `tedi-footer-section__container--mobile` is never emitted.
    - Angular's non-collapsed heading renders
      `<strong tedi-text class="tedi-footer-section__heading" color="white">`,
      but `tedi-footer-section__heading` has no rule anywhere in the vendored
      SCSS (verified against dist/tedi.css) — it's a dead class upstream, same
      category as progress-bar's `--value-bottom`. Not emitted here; the
      heading's look comes entirely from `tedi-text--white` plus its bold
      `<strong>` tag.

    The collapse toggle is inert without JS (like accordion/tabs — CONVENTIONS.md
    §8), so minimal Alpine drives `open`/`aria-expanded`/the chevron icon on top
    of markup and classes that match Angular exactly.
--}}
@props([
    /** Material Symbols icon name rendered before the heading. */
    'icon' => null,
    'heading' => null,
    /** Enables the collapse toggle (only meaningful on narrow layouts in Angular). */
    'collapse' => false,
])

<div
    @if ($collapse) x-data="{ open: false }" @endif
    {{ $attributes->class([
        'tedi-footer-section',
        'tedi-footer-section--collapse' => $collapse,
    ]) }}
>
    @if ($icon)
        <tedi:icon class="tedi-footer-section__icon" :name="$icon" color="white" />
    @endif

    <div class="tedi-footer-section__container">
        @if ($collapse)
            <button
                type="button"
                class="tedi-footer-section__button"
                aria-expanded="false"
                x-on:click="open = ! open"
                x-bind:aria-expanded="open.toString()"
            >
                {{ $heading }}
                <tedi:icon
                    class="collapse__icon"
                    color="white"
                    name="expand_more"
                    x-text="open ? 'expand_less' : 'expand_more'"
                />
            </button>
        @else
            <tedi:text as="strong" color="white">
                {{ $heading }}
            </tedi:text>
        @endif

        <div
            class="tedi-footer-section__content-wrapper{{ $collapse ? ' tedi-footer-section__content-wrapper--collapsed' : '' }}"
            @if ($collapse) x-bind:class="{ 'tedi-footer-section__content-wrapper--collapsed': ! open }" @endif
        >
            <div class="tedi-footer-section__content">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
