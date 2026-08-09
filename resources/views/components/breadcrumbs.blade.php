{{--
    TEDI Breadcrumbs.
    Port of angular/tedi/components/navigation/breadcrumbs/breadcrumbs.component.{ts,html}

    Angular's selector is the element `tedi-breadcrumbs`, so per CONVENTIONS.md
    §4's element-selector rule the root is that literal custom element.

    §4's "also check display" note applies and resolves to "no fallback needed":
    the component's SCSS sets its display through `:host { display: block }`,
    but the component is ViewEncapsulation.None, so `:host` survives into
    dist/tedi.css as a literal `:host` selector that matches nothing in the
    light DOM. `.tedi-breadcrumbs` itself carries no declarations either. So
    upstream's <tedi-breadcrumbs> computes `display: inline` too, and rendering
    the bare custom element is exact parity. The inner <nav>/<ol> do the
    layout work regardless.

    CONTENT PROJECTION → EXPLICIT ARRAY (CONVENTIONS.md §5).
    Angular collects crumbs through the `*tediBreadcrumbItem` structural
    directive (`contentChildren`) and re-projects each one's TemplateRef.
    Blade cannot introspect its own slot, so the trail is the `items` array
    prop instead, following the precedent of `pagination`, `header.language`
    and `header.role`. Each entry is an array:

        ['label' => 'Töölaud', 'href' => '/', 'underline' => true]

      label      required, the crumb text
      href       omitted → the crumb renders as a <tedi:link> button, which is
                 Angular's `button tedi-link` crumb (the ButtonCrumbs story)
      underline  per-crumb, defaults to true; `false` matches
                 `[underline]="false"` on a projected tedi-link

    The LAST entry is always the current page and renders as a plain
    `<span aria-current="page">`, never a link — that is Angular's
    `current: index === lastIndex` plus its documented guidance that the
    current crumb is a plain element. There is no default slot: with `items`
    driving the trail, a slot would be a second, conflicting API.

    Angular's `*tediBreadcrumbSeparator` template (`contentChild`) becomes the
    named `separatorTemplate` slot per §3's `<ng-content select=…>` rule, keeping
    upstream's precedence: slot > `separator` string > default chevron icon.
    Write it as `<x-slot:separator-template>`.

    NOT PORTED (CONVENTIONS.md §7 #1): the `xs`/`sm`/`md`/`lg`/`xl`/`xxl`
    inputs, which Angular resolves through BreakpointService to pick a
    per-viewport `variant`/`maxItems`/`itemsBeforeCollapse`/`itemsAfterCollapse`.
    Server-rendered Blade has no viewport and TEDI ships no breakpoint-variant
    classes for breadcrumbs, so the base props are resolved directly and the
    breakpoint inputs are omitted entirely rather than declared inert.

    The ellipsis menu composes the real <tedi:dropdown> stack, so every
    divergence documented on dropdown.blade.php applies here too — notably that
    the panel is not re-parented into an overlay container (CONVENTIONS.md §11).
--}}
@props([
    /** The crumbs, root first. See the shape documented above. */
    'items' => [],
    /** Accessible label for the nav landmark. Falls back to the `breadcrumbs` translation. */
    'ariaLabel' => null,
    /** Accessible label for the ellipsis button. Falls back to the `breadcrumbs.show-more` translation. */
    'showMoreLabel' => null,
    /** Separator string between crumbs. The separatorTemplate slot overrides it; omitted, a chevron icon is used. */
    'separator' => null,
    /** long shows the full trail; short shows only the parent crumb as a back-link. */
    'variant' => 'long',
    /** Max crumbs before the middle collapses into the ellipsis dropdown. Long variant only; null renders all. */
    'maxItems' => null,
    /** Crumbs kept visible at the start of the trail when collapsed. */
    'itemsBeforeCollapse' => 1,
    /** Crumbs kept visible at the end of the trail when collapsed. */
    'itemsAfterCollapse' => 1,
])

@php
    $crumbs = array_values($items);
    $count = count($crumbs);
    $lastIndex = $count - 1;

    // parentItem(): the second-to-last crumb, or null when there is only one.
    $parentItem = $count > 1 ? $crumbs[$count - 2] : null;

    // visible(): nothing renders without crumbs, and `short` needs a parent.
    $isShort = $variant === 'short';
    $visible = $count > 0 && (! $isShort || $parentItem !== null);

    // tokens(): a direct port of the computed() in breadcrumbs.component.ts.
    $before = max(0, (int) $itemsBeforeCollapse);
    // Keep at least one trailing crumb so the current page is never collapsed.
    $after = max(1, (int) $itemsAfterCollapse);

    $shouldCollapse = $maxItems !== null
        && $count > (int) $maxItems
        && $count > $before + $after;

    $tokens = [];

    if ($count > 0 && ! $shouldCollapse) {
        foreach ($crumbs as $index => $crumb) {
            $tokens[] = ['kind' => 'item', 'item' => $crumb, 'current' => $index === $lastIndex];
        }
    } elseif ($count > 0) {
        foreach (array_slice($crumbs, 0, $before) as $crumb) {
            $tokens[] = ['kind' => 'item', 'item' => $crumb, 'current' => false];
        }

        $tokens[] = ['kind' => 'ellipsis', 'hidden' => array_slice($crumbs, $before, $count - $after - $before)];

        $tail = array_slice($crumbs, $count - $after);

        foreach ($tail as $index => $crumb) {
            $tokens[] = ['kind' => 'item', 'item' => $crumb, 'current' => $index === count($tail) - 1];
        }
    }

    $lastTokenIndex = count($tokens) - 1;
    $ellipsisId = \Tedi\Livewire\Tedi::id('tedi-breadcrumbs');
@endphp

<tedi-breadcrumbs {{ $attributes->class(['tedi-breadcrumbs']) }}>
    @if ($visible)
        <nav aria-label="{{ $ariaLabel ?: __('tedi::tedi.breadcrumbs') }}">
            <ol class="tedi-breadcrumbs__list">
                @if ($isShort)
                    <li class="tedi-breadcrumbs__item">
                        <tedi:icon name="arrow_back" :size="16" color="brand" />
                        <tedi:link
                            :href="$parentItem['href'] ?? null"
                            :underline="$parentItem['underline'] ?? true"
                        >{{ $parentItem['label'] ?? '' }}</tedi:link>
                    </li>
                @else
                    @foreach ($tokens as $tokenIndex => $token)
                        @if ($token['kind'] === 'item')
                            <li @class([
                                'tedi-breadcrumbs__item',
                                'tedi-breadcrumbs__item--current' => $token['current'],
                            ])>
                                @if ($token['current'])
                                    <span aria-current="page">{{ $token['item']['label'] ?? '' }}</span>
                                @else
                                    <tedi:link
                                        :href="$token['item']['href'] ?? null"
                                        :underline="$token['item']['underline'] ?? true"
                                    >{{ $token['item']['label'] ?? '' }}</tedi:link>
                                @endif
                            </li>
                        @else
                            <li class="tedi-breadcrumbs__item">
                                <tedi:dropdown position="bottom-start" :container-id="$ellipsisId">
                                    <tedi:dropdown-trigger>
                                        <tedi:link
                                            :underline="false"
                                            class="tedi-breadcrumbs__ellipsis"
                                            :aria-label="$showMoreLabel ?: __('tedi::tedi.breadcrumbs.show-more')"
                                        ><span aria-hidden="true">…</span></tedi:link>
                                    </tedi:dropdown-trigger>

                                    <tedi:dropdown-content dropdown-role="menu">
                                        @foreach ($token['hidden'] as $hidden)
                                            <tedi:dropdown-item :clip-content="false" class="tedi-breadcrumbs__dropdown-item">
                                                <tedi:link
                                                    :href="$hidden['href'] ?? null"
                                                    :underline="$hidden['underline'] ?? true"
                                                >{{ $hidden['label'] ?? '' }}</tedi:link>
                                            </tedi:dropdown-item>
                                        @endforeach
                                    </tedi:dropdown-content>
                                </tedi:dropdown>
                            </li>
                        @endif

                        @if ($tokenIndex !== $lastTokenIndex)
                            <li class="tedi-breadcrumbs__separator" aria-hidden="true">
                                @isset($separatorTemplate)
                                    {{ $separatorTemplate }}
                                @elseif ($separator)
                                    <tedi:text color="brand">{{ $separator }}</tedi:text>
                                @else
                                    <tedi:icon name="chevron_right" :size="16" color="brand" />
                                @endif
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
        </nav>
    @endif
</tedi-breadcrumbs>
