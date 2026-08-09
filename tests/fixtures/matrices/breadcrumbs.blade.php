@php
    $short = [
        ['label' => 'Töölaud', 'href' => '#', 'underline' => false],
        ['label' => 'Taotlus nr 506'],
    ];

    $trail = [
        ['label' => 'Töölaud', 'href' => '#'],
        ['label' => 'Taotlused', 'href' => '#'],
        ['label' => 'Taotlus nr 506'],
    ];

    $long = [
        ['label' => 'Töölaud', 'href' => '#'],
        ['label' => 'Patsiendid', 'href' => '#'],
        ['label' => 'Anna Tamm', 'href' => '#'],
        ['label' => 'Visiidid', 'href' => '#'],
        ['label' => '2024-05-12', 'href' => '#'],
        ['label' => 'Piirangud'],
    ];
@endphp

<div class="gx-sec">
    <h2>Breadcrumbs</h2>
    <p>Port of <code>navigation/breadcrumbs</code>. Angular collects crumbs through the
        <code>*tediBreadcrumbItem</code> structural directive; Blade can't introspect its
        slot, so the trail is the <code>:items</code> array prop (CONVENTIONS.md §5) and
        the last entry is always the current page. The <code>xs</code>…<code>xxl</code>
        breakpoint inputs are not ported (§7 #1). The ellipsis menu composes the real
        <code>&lt;tedi:dropdown&gt;</code> stack.</p>

    <div class="gx-case">
        <div class="gx-case__label">default (long) — links plus the current page</div>
        <div class="gx-case__demo">
            <tedi:breadcrumbs :items="$trail" aria-label="Liikumistee" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">crumbs without href render as buttons</div>
        <div class="gx-case__demo">
            <tedi:breadcrumbs :items="[['label' => 'Töölaud'], ['label' => 'Taotlused'], ['label' => 'Taotlus nr 506']]" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">variant: short — back-link to the parent crumb</div>
        <div class="gx-case__demo">
            <tedi:breadcrumbs variant="short" :items="$short" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">max-items: 4, before: 1, after: 2 — middle collapses into the ellipsis dropdown</div>
        <div class="gx-case__demo">
            <tedi:breadcrumbs :items="$long" :max-items="4" :items-before-collapse="1" :items-after-collapse="2" />
        </div>
    </div>

    <div class="gx-case">
        <div class="gx-case__label">separator: string / separatorTemplate slot / default chevron</div>
        <div class="gx-case__demo">
            <tedi:breadcrumbs :items="$trail" separator="/" aria-label="Kaldkriipsuga" />
            <tedi:breadcrumbs :items="$trail" aria-label="Noolega">
                <x-slot:separator-template>
                    <tedi:icon name="arrow_forward" :size="16" color="brand" />
                </x-slot:separator-template>
            </tedi:breadcrumbs>
        </div>
    </div>
</div>
