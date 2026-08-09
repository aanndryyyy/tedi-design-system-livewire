{{--
    TEDI SideNav Dropdown Group.
    Port of angular/tedi/components/layout/sidenav/sidenav-dropdown-group/sidenav-dropdown-group.component.{ts,html}

    Angular collects its projected `<tedi-sidenav-dropdown-item>` children with
    `@ContentChildren`, hides them in a `display: none` container, and RE-RENDERS
    them as plain markup: the first becomes the group's parent row, the rest a
    nested `<ul>`. No `<tedi-sidenav-dropdown-item>` element survives into the
    visible DOM.

    Blade cannot inspect its slot (CONVENTIONS.md §5), so the items arrive as an
    explicit `items` array — which produces byte-for-byte the same visible
    markup Angular emits. Each entry accepts:

        ['label' => 'Treatments', 'href' => '#', 'route' => null, 'selected' => false]

    Passing `<tedi:sidenav.dropdown-item>` children here is NOT supported; use
    `items`. The default slot is ignored, matching the fact that Angular's
    projected children are never displayed.

    OMITTED: the hidden `<div style="display:none" aria-hidden="true">` holding
    the `<ng-content>`. It exists purely to host the ContentChildren query and
    renders nothing; there is no query here, so it would be pure DOM noise.

    `role="presentation"` and `style="display: contents"` are the Angular host
    bindings, and the root is the literal `<tedi-sidenav-dropdown-group>`
    element because a sibling rule keys on it —
    `tedi-sidenav-dropdown-item:has(+ tedi-sidenav-dropdown-group)` in
    sidenav-dropdown-item.component.scss (CONVENTIONS.md §4).
--}}
@props([
    /** list<array{label:string, href?:string, route?:string, selected?:bool}> — §5 replacement for @ContentChildren. */
    'items' => [],
])

@php
    $groupItems = array_values($items);
    $first = $groupItems[0] ?? null;
    $rest = array_slice($groupItems, 1);

    $hrefOf = fn (array $item) => $item['href'] ?? $item['route'] ?? null;
@endphp

<tedi-sidenav-dropdown-group {{ $attributes->class(['tedi-sidenav-dropdown-group'])->merge(['role' => 'presentation'])->style(['display: contents']) }}>
    @if ($first)
        <li class="tedi-sidenav-dropdown-group__parent-wrapper">
            <div @class([
                'tedi-sidenav-dropdown-item',
                'tedi-sidenav-dropdown-group__parent',
                'tedi-sidenav-dropdown-item--selected' => $first['selected'] ?? false,
            ])>
                @if ($hrefOf($first))
                    <a href="{{ $hrefOf($first) }}" class="tedi-sidenav-dropdown-item__trigger">{{ $first['label'] ?? '' }}</a>
                @else
                    <span class="tedi-sidenav-dropdown-item__trigger">{{ $first['label'] ?? '' }}</span>
                @endif
            </div>

            @if (count($rest) > 0)
                <ul class="tedi-sidenav-dropdown-group__list">
                    @foreach ($rest as $item)
                        <li @class([
                            'tedi-sidenav-dropdown-item',
                            'tedi-sidenav-dropdown-group__item',
                            'tedi-sidenav-dropdown-item--selected' => $item['selected'] ?? false,
                        ])>
                            @if ($hrefOf($item))
                                <a href="{{ $hrefOf($item) }}" class="tedi-sidenav-dropdown-item__trigger">{{ $item['label'] ?? '' }}</a>
                            @else
                                <span class="tedi-sidenav-dropdown-item__trigger">{{ $item['label'] ?? '' }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endif
</tedi-sidenav-dropdown-group>
