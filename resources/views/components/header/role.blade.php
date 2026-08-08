{{--
    TEDI Header Role.
    Port of angular/tedi/components/layout/header/header-role/header-role.component.{ts,html}

    Substantial, documented divergences (breakpoints not ported — CONVENTIONS.md
    §7 — and overlay positioning out of scope — §7.3):
    - Angular renders an entire `*hideAt('lg')` accordion branch (mobile head +
      collapsible list) and a `*showAt('lg')` branch (label row + popover
      trigger). Only the `showAt('lg')` (desktop) branch is ported; the mobile
      accordion is dropped, same collapse-to-base-branch rule used for
      header-login/-logout/-profile. `tedi-header-role__head--open` was
      `hasRoleSelection() && mobileOpen()` — `mobileOpen` only exists in the
      dropped mobile branch, so this modifier has no meaning here and is not
      emitted.
    - The desktop trigger normally opens a `<tedi-popover>`; here it toggles a
      plain `.tedi-header-role__dropdown` div with local Alpine state instead
      of floating-ui.
    - `showSearch` renders a plain `<input type="search">`, not `<tedi-search>`
      (not yet ported). No live filtering is wired up — `filteredRepresentatives()`
      was reactive Angular state; this port always renders the full
      `representatives` list. A consumer needing live filtering should drive it
      with their own Livewire/Alpine state.
    - `currentRepresentative` was a two-way `model()`; here it's a plain prop
      used only for the `data-selected` comparison and the trigger label.
      Representative buttons render inert — wire `wire:click` / `x-on:click`
      per item yourself (e.g. by mapping `representatives` server-side and
      building the attribute you need) since there is no generic per-item
      attribute-forwarding hook here.
    - `clearSearchOnSelect` is accepted for API parity (CONVENTIONS.md §9 DoD
      item 2, same default `true`) but is inert, like alert's `closeDelay` —
      it only cleared the search input on `handleSelectRepresentative()`, and
      selection itself isn't wired up here (see above).
    - `HeaderRoleComponent` optionally injects a parent `HeaderProfileComponent`
      to coordinate which role's accordion is open across instances
      (`activeRole`) and to close on the profile modal's close. That
      coordination is pure runtime signal wiring with nothing static to port —
      there is no `@aware` data to inherit here, so it is dropped entirely.

    `[tedi-header-role-title]` / `[tedi-header-role-content]` /
    `[tedi-header-role-no-results]` become the named slots `title` / `content`
    / `noResults` (CONVENTIONS.md §2).
--}}
@props([
    'label' => '',
    'description' => '',
    'showSearch' => false,
    'searchClearable' => false,
    'isOrganization' => false,
    'searchLabel' => null,
    'organizationSearchLabel' => null,
    /** Accepted for API parity — inert; see doc comment above. */
    'clearSearchOnSelect' => true,
    /** Defaults to showing the switch when there is more than one representative. */
    'showRoleSwitch' => null,
    /** Representative[]: [['id' => ..., 'name' => ..., 'icon' => ..., 'description' => ...], ...]. Required. */
    'representatives',
    /** The currently selected representative (same shape as one entry above). Required. */
    'currentRepresentative',
])

@php
    $hasRoleSelection = $showRoleSwitch ?? (count($representatives) > 1);
    $hasTitleSlot = isset($title) && $title->isNotEmpty();
    $resolvedSearchLabel = $isOrganization
        ? ($organizationSearchLabel ?? __('tedi::tedi.header.role-search.organization'))
        : ($searchLabel ?? __('tedi::tedi.header.role-search'));

    $resolveIcon = function ($icon) {
        if (! $icon) {
            return null;
        }

        return is_array($icon) ? ['name' => $icon['name'], 'size' => $icon['size'] ?? 24] : ['name' => $icon, 'size' => 24];
    };
@endphp

<div x-data="{ open: false }" {{ $attributes->class(['tedi-header-role']) }}>
    @if ($label || $description || $hasTitleSlot)
        <tedi:text
            as="div"
            color="secondary"
            modifiers="small"
            class="tedi-header-role__head"
        >
            @if ($hasTitleSlot)
                {{ $title }}
            @elseif ($label)
                <tedi:text as="p" :modifiers="['small', 'bold']" color="secondary">{{ $label }}</tedi:text>
            @endif

            @if ($description)
                <span class="tedi-header-role__description">{{ $description }}</span>
            @endif
        </tedi:text>
    @endif

    @if ($hasRoleSelection)
        <button
            type="button"
            class="tedi-link tedi-header__link-button"
            x-on:click="open = ! open"
            x-bind:aria-expanded="open.toString()"
        >
            <span>{{ $currentRepresentative['name'] }}</span>
            <tedi:icon name="expand_more" :size="16" class="tedi-header-role__chevron" />
        </button>

        <div class="tedi-header-role__dropdown" x-show="open" style="display: none;">
            @if ($showSearch)
                <input
                    type="search"
                    id="{{ \Tedi\Livewire\Tedi::id('tedi-header-role') }}"
                    aria-label="{{ $resolvedSearchLabel }}"
                    placeholder="{{ $resolvedSearchLabel }}"
                />
            @endif

            @if (isset($content) && $content->isNotEmpty())
                {{ $content }}
            @else
                @if (empty($representatives))
                    @if (isset($noResults) && $noResults->isNotEmpty())
                        {{ $noResults }}
                    @else
                        <span class="tedi-header-role__no-results">{{ __('tedi::tedi.header.role-no-representatives') }}</span>
                    @endif
                @endif

                @foreach ($representatives as $representative)
                    @php $icon = $resolveIcon($representative['icon'] ?? null); @endphp
                    <button
                        type="button"
                        class="tedi-header-role__representative"
                        data-selected="{{ ($representative['id'] ?? null) === ($currentRepresentative['id'] ?? null) ? 'true' : 'false' }}"
                    >
                        @if ($icon)
                            <tedi:icon :name="$icon['name']" :size="$icon['size']" />
                        @endif
                        <div>
                            <div>{{ $representative['name'] }}</div>
                            @if (! empty($representative['description']))
                                <tedi:text as="div" modifiers="small">{{ $representative['description'] }}</tedi:text>
                            @endif
                        </div>
                    </button>
                @endforeach
            @endif
        </div>
    @else
        <div class="tedi-header-role__value">{{ $currentRepresentative['name'] }}</div>
    @endif
</div>
