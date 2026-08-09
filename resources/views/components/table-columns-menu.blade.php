{{--
    TEDI Table Columns Menu.
    Port of angular/tedi/components/content/table/table-columns-menu/table-columns-menu.component.{ts,html}

    Angular's selector is the element `tedi-table-columns-menu`, so per
    CONVENTIONS.md §4 the root is that literal element with the
    `tedi-table-columns-menu` class and the static `data-name` host attribute.

    DOCUMENTED SUBSET (CONVENTIONS.md §7, same spirit as <tedi:select>).
    Angular reads the hideable columns straight off the TanStack table instance
    through `inject(TEDI_TABLE_CONTEXT)` and mutates visibility with
    `column.toggleVisibility()`. Neither the TanStack engine nor its context is
    ported, so:

    - the column list is an EXPLICIT `:columns` array prop (§5), the same
      array-prop shape `<tedi:table>` itself takes;
    - toggling is the consumer's: bind `wire:click` / `x-on:click` per column
      via each entry's `attributes` key (§7.2), and echo the new visibility
      back in as `visible`;
    - `column.getCanHide()` filtering happens consumer-side — every entry you
      pass is rendered.

    Angular's "last visible column can't be hidden" guard IS ported: it is a
    pure function of the passed array (`visible && visibleCount === 1`).

    CLASS NOTE: the vendored SCSS defines `.tedi-table-columns-menu__option`,
    but the Angular template never emits it (it is the class-based alternative
    the React port uses). Per §4 this port does not emit it either — parity
    with Angular wins.

    ICON SIZE DIVERGENCE: Angular writes `<tedi-icon name="tune" [size]="18">`
    into the trigger button by hand. This port composes `<tedi:button>` whose
    `icon-start` renders the contextual button icon size (24 at the default
    button size), so the trigger's icon is 24px rather than 18px. Composing the
    shared button keeps its class logic (`--pl` / `--pr`) in one place, which is
    worth more than the 6px.
--}}
@props([
    /** Trigger label. Falls back to the localised `table.columns` translation. */
    'triggerLabel' => null,
    /**
     * Column[]: [['id' => 'name', 'label' => 'Nimi', 'visible' => true,
     *             'attributes' => ['wire:click' => "toggle('name')"]], ...]
     * `label` falls back to `id`, `visible` to true.
     */
    'columns' => [],
    /** Dropdown placement, forwarded to the dropdown root. */
    'position' => 'bottom-end',
])

@php
    $visibleCount = 0;

    foreach ($columns as $column) {
        if (($column['visible'] ?? true)) {
            $visibleCount++;
        }
    }

    $label = $triggerLabel ?? __('tedi::tedi.table.columns');
@endphp

<tedi-table-columns-menu data-name="tedi-table-columns-menu" {{ $attributes->class(['tedi-table-columns-menu']) }}>
    <tedi:dropdown :position="$position">
        <tedi:dropdown-trigger>
            <tedi:button variant="neutral" icon-start="tune">{{ $label }}</tedi:button>
        </tedi:dropdown-trigger>

        <tedi:dropdown-content>
            @foreach ($columns as $column)
                @php
                    $isVisible = $column['visible'] ?? true;
                    $isLastVisible = $isVisible && $visibleCount === 1;
                @endphp

                <tedi:dropdown-item
                    :value="$column['id']"
                    :disabled="$isLastVisible"
                    :close-on-select="false"
                    :attributes="(new \Illuminate\View\ComponentAttributeBag)->merge($column['attributes'] ?? [])"
                >
                    <x-slot:item-value>
                        <tedi:dropdown-item-value type="checkbox" :selected="$isVisible" :disabled="$isLastVisible">
                            <tedi:dropdown-item-value-label>{{ $column['label'] ?? $column['id'] }}</tedi:dropdown-item-value-label>
                        </tedi:dropdown-item-value>
                    </x-slot:item-value>
                </tedi:dropdown-item>
            @endforeach
        </tedi:dropdown-content>
    </tedi:dropdown>
</tedi-table-columns-menu>
