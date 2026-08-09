@php
    $views = [
        ['value' => '1', 'label' => 'Tabel'],
        ['value' => '2', 'label' => 'Loend'],
        ['value' => '3', 'label' => 'Kalender'],
    ];

    $withIcons = [
        ['value' => '1', 'label' => 'Tabel', 'iconLeft' => 'table'],
        ['value' => '2', 'label' => 'Loend', 'iconLeft' => 'list'],
        ['value' => '3', 'label' => 'Kalender', 'iconLeft' => 'calendar_month'],
    ];

    $iconOnly = [
        ['value' => '1', 'label' => 'Tabel', 'icon' => 'table'],
        ['value' => '2', 'label' => 'Loend', 'icon' => 'list'],
        ['value' => '3', 'label' => 'Kalender', 'icon' => 'calendar_month'],
    ];
@endphp

<div class="gx-sec">
    <h2>Button group</h2>
    <p>Port of <code>buttons/button-group</code> (<code>tedi-button-group</code>,
        <code>button[tedi-button-group-button]</code>). Angular reads its items back
        through <code>contentChildren</code>; Blade can't, so <code>:items</code> is the
        primary API (CONVENTIONS.md §5) and the slot stays available for tooltip-wrapped
        items. <code>variant</code>/<code>size</code> cascade to the items through
        Blade's aware mechanism. <code>enableMobileDropdown</code>/<code>mobileBreakpoint</code>
        are BreakpointService-driven and not ported (§7 #1) — the explicit
        <code>:dropdown-mode</code> prop selects that branch instead.</p>

    <div class="gx-case">
        <div class="gx-case__label">variant: primary-button-group / secondary-button-group (value="2")</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Esmane" variant="primary-button-group" value="2" :items="$views" />
            <tedi:button-group aria-label="Teisene" variant="secondary-button-group" value="2" :items="$views" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: non-group variants keep their own radius</div>
        <div class="gx-case__demo">
            @foreach (['primary', 'secondary', 'success', 'danger'] as $variant)
                <tedi:button-group :aria-label="$variant" :variant="$variant" value="1" :items="$views" />
            @endforeach
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">size: default / small</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Vaikesuurus" size="default" value="2" :items="$views" />
            <tedi:button-group aria-label="Väike" size="small" value="2" :items="$views" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">stretch: items share the width equally</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Venitatud" :stretch="true" value="2" :items="$views" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">multiple: several values selected at once</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Mitu valikut" :multiple="true" :value="['1', '3']" :items="$views" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">icon-left / icon-only (label becomes the accessible name) / disabled item</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Ikoonidega" value="2" :items="$withIcons" />
            <tedi:button-group aria-label="Ainult ikoonid" value="1" :items="$iconOnly" />
            <tedi:button-group aria-label="Keelatud" value="1" :items="[
                ['value' => '1', 'label' => 'Tabel'],
                ['value' => '2', 'label' => 'Loend', 'disabled' => true],
            ]" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">per-item variant/size override, and slot-written items</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Ülekirjutatud" variant="secondary-button-group" size="small" :items="[
                ['value' => '1', 'label' => 'Tavaline'],
                ['value' => '2', 'label' => 'Ohtlik', 'variant' => 'danger'],
            ]" />
            <tedi:button-group aria-label="Pesast" variant="secondary-button-group">
                <tedi:button-group-button value="a" label="Esimene" :selected="true" />
                <tedi:button-group-button value="b" label="Teine" />
            </tedi:button-group>
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">dropdown-mode: strip stays in the DOM, CSS hides it (label mode static / selected)</div>
        <div class="gx-case__demo">
            <tedi:button-group aria-label="Rippmenüü" :dropdown-mode="true" dropdown-label="Alammenüü" value="2" :items="$withIcons" />
            <tedi:button-group aria-label="Valitu järgi" :dropdown-mode="true" dropdown-label-mode="selected" value="2" :items="$withIcons" />
        </div>
    </div>
</div>
