{{--
    TEDI Header Role.
    Port of angular/tedi/components/layout/header/header-role/header-role.component.{ts,html}

    The desktop (`*showAt('lg')`) branch is ported in full, now including the
    `<tedi-popover>` that holds the role list — `tedi:popover` exists and is
    positioned by `tediOverlay` (CONVENTIONS.md §11), so the trigger opens a real
    overlay rather than a hand-rolled toggled `<div>` as in an earlier revision.
    The popover content carries `tedi-header-role__dropdown` exactly as Angular's
    `<tedi-popover-content maxWidth="small" class="tedi-header-role__dropdown">`
    does, and the search field / representative buttons are its direct children
    so the `&__dropdown > *` separator rules apply unchanged.

    Remaining divergences:

    - The mobile (`*hideAt('lg')`) accordion branch is NOT ported. `tedi:show-at`
      / `tedi:hide-at` render a WRAPPER `<div>`, and `.tedi-header-role > *`
      (header-role.component.scss:83) plus `.tedi-header-actions > *` are child
      selectors — wrapping the two branches would make the wrapper the styled
      child and break the layout. Angular's `*showAt`/`*hideAt` are structural
      and add no element, which Blade has no equivalent for. So this port always
      renders the desktop branch, at every width. `mobileOpen`-derived state
      (`tedi-header-role__head--open`, `collapseText`) only exists in the dropped
      branch and is therefore not emitted.
    - `filteredRepresentatives()` was reactive Angular state that REMOVES
      non-matching entries from the DOM. Here every representative is rendered
      server-side and filtered client-side with `x-show`, so a hidden entry still
      occupies its DOM position — the `&__dropdown > *:not(:first-child)`
      separator therefore keys off the written order, not the visible one, and a
      hidden first entry leaves a separator above the first visible one. Filtering
      is case-insensitive over name + description, matching upstream's `includes`.
    - `currentRepresentative` was a two-way `model()`. Selection is wired here as
      local Alpine state: clicking a representative updates `data-selected` and
      the trigger label and closes the popover (Angular's
      `handleSelectRepresentative`), but nothing is persisted server-side. Bind
      `wire:click` per item yourself if the server needs to know — there is no
      generic per-item attribute-forwarding hook.
    - `clearSearchOnSelect` IS honoured: on select the query resets when true.
    - `HeaderRoleComponent` optionally injects a parent `HeaderProfileComponent`
      to coordinate which role's accordion is open across instances
      (`activeRole`) and to close on the profile modal's close. That coordination
      is pure runtime signal wiring belonging to the dropped mobile branch, so it
      is dropped with it.

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
    /** Clears the search query when a representative is selected. */
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
    $popoverId = \Tedi\Livewire\Tedi::id('tedi-header-role');

    $resolveIcon = function ($icon) {
        if (! $icon) {
            return null;
        }

        return is_array($icon) ? ['name' => $icon['name'], 'size' => $icon['size'] ?? 24] : ['name' => $icon, 'size' => 24];
    };

    // Local selection + search state. `matches()` mirrors upstream's
    // case-insensitive `includes` over the representative's visible text; the
    // haystacks are precomputed server-side so `noResults` can ask whether any
    // entry is still visible without walking the DOM.
    $haystacks = array_values(array_map(
        fn ($representative) => mb_strtolower(trim($representative['name'].' '.($representative['description'] ?? ''))),
        $representatives,
    ));

    $roleState = <<<'JS'
        {
            query: '',
            haystacks: {$haystacks_js},
            selectedId: {$selectedId_js},
            selectedName: {$selectedName_js},
            clearSearchOnSelect: {$clearSearchOnSelect_js},
            matches(haystack) {
                return haystack.includes(this.query.trim().toLowerCase());
            },
            representativesVisible() {
                return this.haystacks.some((haystack) => this.matches(haystack));
            },
            select(id, name) {
                this.selectedId = id;
                this.selectedName = name;

                if (this.clearSearchOnSelect) {
                    this.query = '';
                }
            },
        }
        JS;
@endphp

@php
    // Interpolated after the heredoc so the JSON literals keep their quoting.
    $roleState = strtr($roleState, [
        '{$haystacks_js}' => json_encode($haystacks),
        '{$selectedId_js}' => json_encode($currentRepresentative['id'] ?? null),
        '{$selectedName_js}' => json_encode($currentRepresentative['name'] ?? ''),
        '{$clearSearchOnSelect_js}' => $clearSearchOnSelect ? 'true' : 'false',
    ]);
@endphp

<div
    x-data="{{ $roleState }}"
    {{ $attributes->class(['tedi-header-role']) }}
>
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
        <tedi:popover :with-border="true" position="bottom" :prevent-overflow="true" :container-id="$popoverId">
            <x-slot:trigger>
                <tedi:popover-trigger tag="button" class="tedi-link tedi-header__link-button">
                    <span x-text="selectedName">{{ $currentRepresentative['name'] }}</span>
                    <tedi:icon name="expand_more" :size="16" class="tedi-header-role__chevron" />
                </tedi:popover-trigger>
            </x-slot:trigger>

            <tedi:popover-content max-width="small" class="tedi-header-role__dropdown">
                @if ($showSearch)
                    <tedi:search
                        :input-id="\Tedi\Livewire\Tedi::id('tedi-header-role')"
                        :label="$resolvedSearchLabel"
                        :clearable="$searchClearable"
                        x-model="query"
                        :clear-attributes="['x-on:click' => 'query = \'\'']"
                    />
                @endif

                @if (isset($content) && $content->isNotEmpty())
                    {{ $content }}
                @else
                    @if (isset($noResults) && $noResults->isNotEmpty())
                        <div x-show="!representativesVisible()" @if (! empty($representatives)) style="display: none;" @endif>
                            {{ $noResults }}
                        </div>
                    @else
                        <span
                            class="tedi-header-role__no-results"
                            x-show="!representativesVisible()"
                            @if (! empty($representatives)) style="display: none;" @endif
                        >{{ __('tedi::tedi.header.role-no-representatives') }}</span>
                    @endif

                    @foreach ($representatives as $representative)
                        @php
                            $icon = $resolveIcon($representative['icon'] ?? null);
                            $haystack = mb_strtolower(trim($representative['name'].' '.($representative['description'] ?? '')));
                        @endphp
                        <button
                            type="button"
                            class="tedi-header-role__representative"
                            data-selected="{{ ($representative['id'] ?? null) === ($currentRepresentative['id'] ?? null) ? 'true' : 'false' }}"
                            x-show="matches(@js($haystack))"
                            x-bind:data-selected="(selectedId === @js($representative['id'] ?? null)).toString()"
                            x-on:click="select(@js($representative['id'] ?? null), @js($representative['name'])); hide(true)"
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
            </tedi:popover-content>
        </tedi:popover>
    @else
        <div class="tedi-header-role__value">{{ $currentRepresentative['name'] }}</div>
    @endif
</div>
