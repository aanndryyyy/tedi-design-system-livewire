@php
    use Illuminate\Support\HtmlString;

    $columns = [
        ['key' => 'name', 'header' => 'Nimi', 'width' => 220],
        ['key' => 'role', 'header' => 'Roll'],
        ['key' => 'salary', 'header' => 'Palk', 'align' => 'right', 'vAlign' => 'middle'],
    ];

    $rows = [
        ['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €']],
        ['id' => 'r2', 'cells' => ['name' => 'Jüri Kask', 'role' => 'Designer', 'salary' => '3800 €']],
        ['id' => 'r3', 'cells' => ['name' => 'Maria Saar', 'role' => 'Product', 'salary' => '4600 €']],
    ];
@endphp

<div class="gx-sec">
    <h2>Table</h2>
    <p>
        Port of <code>content/table</code>. Documented subset: the
        <code>@tanstack/angular-table</code> engine (sorting, filtering, selection,
        expansion, pagination and column state) is not portable, so the markup is
        driven by explicit <code>:columns</code> / <code>:rows</code> arrays.
    </p>

    <div class="gx-case">
        <div class="gx-case__label">size: medium | small (no <code>--medium</code> class — TEDI ships no rule)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table :columns="$columns" :rows="$rows" size="medium" caption="Töötajad" />
            <tedi:table :columns="$columns" :rows="$rows" size="small" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">striped &middot; verticalBorders &middot; borderless &middot; fixedLayout</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table :columns="$columns" :rows="$rows" :striped="true" />
            <tedi:table :columns="$columns" :rows="$rows" :vertical-borders="true" />
            <tedi:table :columns="$columns" :rows="$rows" :borderless="true" />
            <tedi:table :columns="$columns" :rows="$rows" :fixed-layout="true" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">rowHover &middot; interactive (clickable rows) &middot; activeRowId &middot; selected</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table :columns="$columns" :rows="$rows" :row-hover="true" />
            <tedi:table :columns="$columns" :rows="$rows" :interactive="true" active-row-id="r2" />
            <tedi:table
                :columns="$columns"
                :rows="[
                    ['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'selected' => true],
                    ['id' => 'r2', 'cells' => ['name' => 'Jüri Kask', 'role' => 'Designer', 'salary' => '3800 €'], 'subRow' => true],
                ]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">sticky: header (needs maxHeight) &middot; first column</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table :columns="$columns" :rows="$rows" :sticky-header="true" :max-height="160" />
            <tedi:table :columns="$columns" :rows="$rows" :sticky-first-column="true" />
            <tedi:table :columns="$columns" :rows="$rows" :sticky-header="true" :sticky-first-column="true" :max-height="160" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">align: left | center | right &middot; valign: top | middle | bottom</div>
        <div class="gx-case__demo">
            <tedi:table
                :columns="[
                    ['key' => 'l', 'header' => 'Vasak', 'align' => 'left', 'vAlign' => 'top', 'footer' => 'V'],
                    ['key' => 'c', 'header' => 'Kesk', 'align' => 'center', 'vAlign' => 'middle', 'footer' => 'K'],
                    ['key' => 'r', 'header' => 'Parem', 'align' => 'right', 'vAlign' => 'bottom', 'footer' => 'P'],
                ]"
                :rows="[['id' => 'r1', 'cells' => ['l' => '1', 'c' => '2', 'r' => '3']]]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">sort states: none | asc | desc (tedi-table-header-button)</div>
        <div class="gx-case__demo">
            <tedi:table
                :columns="[
                    ['key' => 'a', 'header' => 'Sorteerimata', 'sortable' => true, 'sort' => 'none'],
                    ['key' => 'b', 'header' => 'Kasvav', 'sortable' => true, 'sort' => 'asc'],
                    ['key' => 'c', 'header' => 'Kahanev', 'sortable' => true, 'sort' => 'desc'],
                ]"
                :rows="[['id' => 'r1', 'cells' => ['a' => '1', 'b' => '2', 'c' => '3']]]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">selection: multiple (checkbox + select-all) | single (radio)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table
                :columns="$columns"
                :rows="[
                    ['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'selected' => true],
                    ['id' => 'r2', 'cells' => ['name' => 'Jüri Kask', 'role' => 'Designer', 'salary' => '3800 €'], 'selectDisabled' => true],
                ]"
                :enable-row-selection="true"
                selection-mode="multiple"
                :select-all-indeterminate="true"
            />
            <tedi:table :columns="$columns" :rows="$rows" :enable-row-selection="true" selection-mode="single" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">expandable: icon-only chevron (secondary | default) &middot; labelled toggle &middot; row trigger</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table
                :columns="$columns"
                :expandable="true"
                :rows="[
                    ['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'expandable' => true, 'sub' => new HtmlString('<p>Suletud alamrida</p>')],
                    ['id' => 'r2', 'cells' => ['name' => 'Jüri Kask', 'role' => 'Designer', 'salary' => '3800 €'], 'expandable' => true, 'expanded' => true, 'sub' => new HtmlString('<p>Avatud alamrida</p>')],
                    ['id' => 'r3', 'cells' => ['name' => 'Maria Saar', 'role' => 'Product', 'salary' => '4600 €']],
                ]"
            />
            <tedi:table
                :columns="$columns"
                :expandable="true"
                expand-button-variant="default"
                :rows="[['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'expandable' => true, 'sub' => 'Detail']]"
            />
            <tedi:table
                :columns="$columns"
                :expandable="true"
                :expand-button-label="['open' => 'Vaata', 'close' => 'Peida']"
                :rows="[['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'expandable' => true, 'sub' => 'Detail']]"
            />
            <tedi:table
                :columns="$columns"
                :expandable="true"
                expand-trigger="row"
                :rows="[['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer', 'salary' => '4200 €'], 'expandable' => true, 'sub' => 'Detail']]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">rowGroupDividers: all | between | none (with grouped rows + rowspan)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            @foreach (['all', 'between', 'none'] as $dividers)
                <tedi:table
                    :grouped="true"
                    :row-group-dividers="$dividers"
                    :columns="[['key' => 'city', 'header' => 'Linn'], ['key' => 'name', 'header' => 'Nimi']]"
                    :rows="[
                        ['id' => 'g1', 'groupStart' => true, 'cells' => ['city' => ['value' => 'Tallinn', 'rowspan' => 2], 'name' => 'Anna Tamm']],
                        ['id' => 'g2', 'cells' => ['city' => ['value' => '', 'rowspan' => 0], 'name' => 'Mart Mets']],
                        ['id' => 'g3', 'groupStart' => true, 'cells' => ['city' => 'Tartu', 'name' => 'Jüri Kask']],
                    ]"
                />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">column filter row (markup only — the built-in filter popover is not ported)</div>
        <div class="gx-case__demo">
            <tedi:table
                :enable-column-filters="true"
                :columns="[
                    ['key' => 'name', 'header' => 'Nimi', 'filterable' => true],
                    ['key' => 'role', 'header' => 'Roll'],
                ]"
                :rows="[['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'role' => 'Engineer']]]"
            />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">footer row &middot; empty placeholder (with and without role)</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table
                :columns="[
                    ['key' => 'name', 'header' => 'Nimi', 'footer' => 'Kokku'],
                    ['key' => 'salary', 'header' => 'Palk', 'align' => 'right', 'footer' => '12 600 €'],
                ]"
                :rows="[['id' => 'r1', 'cells' => ['name' => 'Anna Tamm', 'salary' => '4200 €']]]"
            />
            <tedi:table :columns="$columns" :rows="[]" />
            <tedi:table :columns="$columns" :rows="[]" placeholder="Otsingule ei vastanud ükski rida" placeholder-role="status" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">pagination slots (top + bottom) — the host loses its gap and the scroll box its matching corners</div>
        <div class="gx-case__demo gx-case__demo--stack" style="gap:1.5rem">
            <tedi:table :columns="$columns" :rows="$rows">
                <x-slot:pagination-top>
                    <tedi:pagination :page-count="4" :page="2" :total-items="37" />
                </x-slot:pagination-top>
                <x-slot:pagination>
                    <tedi:pagination :page-count="4" :page="2" :total-items="37" />
                </x-slot:pagination>
            </tedi:table>

            <tedi:table :columns="$columns" :rows="$rows" :borderless="true">
                <x-slot:pagination>
                    <tedi:pagination :page-count="4" :page="1" />
                </x-slot:pagination>
            </tedi:table>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">toolbar + columns menu</div>
        <div class="gx-case__demo">
            <tedi:table :columns="$columns" :rows="$rows">
                <tedi:table-toolbar>
                    <tedi:table-columns-menu :columns="[
                        ['id' => 'name', 'label' => 'Nimi'],
                        ['id' => 'role', 'label' => 'Roll'],
                        ['id' => 'salary', 'label' => 'Palk', 'visible' => false],
                    ]" />
                </tedi:table-toolbar>
            </tedi:table>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">table-header-button: default &middot; selected &middot; filled &middot; disabled</div>
        <div class="gx-case__demo" style="gap:1rem">
            <tedi:table-header-button icon="unfold_more">Nimi</tedi:table-header-button>
            <tedi:table-header-button icon="arrow_upward" :selected="true">Nimi</tedi:table-header-button>
            <tedi:table-header-button icon="filter_alt" :filled="true" :selected="true" aria-label="Filtreeri" />
            <tedi:table-header-button icon="filter_alt" :disabled="true" aria-label="Filtreeri" />
        </div>
    </div>
</div>
