{{--
    Both layouts are in the DOM here (`tedi:show-at` / `tedi:hide-at` toggle
    `display`, where Angular's structural directives remove one), so the
    mobile copy's section ids carry an `m-` prefix to stay unique.

    Upstream's TocStickyDemoComponent, as inline Alpine. The TOC is controlled,
    so the story owns scroll behaviour: an IntersectionObserver over the
    sections of whichever scroll region is visible sets `active`, which reaches
    each TOC's `activeId` through `x-model` (the components are x-modelable),
    and clicking an item smooth-scrolls that region to the section.
--}}
@storybook([
    'name' => 'Sticky In Layout',
    'order' => 10,
    'status' => 'stable',
])

@php
    $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor '
        .'incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud '
        .'exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.';
    $chapters = [];
    for ($i = 1; $i <= 30; $i++) {
        $chapter = ['id' => 'chapter-'.$i, 'label' => 'Peatükk '.$i, 'children' => []];
        if ($i === 3) {
            $chapter['children'] = [
                ['id' => 'chapter-3-1', 'label' => 'Peatükk 3.1'],
                ['id' => 'chapter-3-2', 'label' => 'Peatükk 3.2'],
            ];
        }
        $chapters[] = $chapter;
    }
    $ids = collect($chapters)->flatMap(fn ($c) => [$c['id'], ...array_column($c['children'], 'id')])->all();
@endphp

<style>
    .scroll-page {
        height: 30rem;
        overflow-y: auto;
        border: var(--tedi-borders-01) solid var(--card-border-primary);
        border-radius: var(--card-radius-rounded);
    }
    .scroll-page__grid {
        display: grid;
        grid-template-columns: 1fr 16rem;
        gap: 2rem;
        padding: 2rem;
    }
    .scroll-page article > h1,
    .mobile-scroll article > h1 {
        margin-bottom: 1rem;
    }
    .scroll-page__section {
        margin-bottom: 2rem;
    }
    .scroll-page__section p {
        margin: 0.25rem 0 0;
    }
    .mobile-shell {
        display: flex;
        flex-direction: column;
        height: 100vh;
    }
    .mobile-scroll {
        flex: 1 1 auto;
        min-height: 0;
        overflow-y: auto;
        padding: 2rem;
    }
</style>

<div
    x-data="{
        active: @js($ids[0]),
        ids: @js($ids),
        seeking: false,
        seekTimeout: null,
        observers: [],
        init() {
            this.$root.querySelectorAll('[data-scroll-root]').forEach((root) => this.observe(root));
        },
        destroy() {
            this.observers.forEach((o) => o.disconnect());
            clearTimeout(this.seekTimeout);
        },
        observe(root) {
            const prefix = root.dataset.scrollRoot;
            const visibility = new Map();
            const observer = new IntersectionObserver((entries) => {
                if (! root.offsetParent) return;
                entries.forEach((e) => visibility.set(e.target.id.slice(prefix.length), e.isIntersecting));
                if (this.seeking) return;
                if (root.scrollTop + root.clientHeight >= root.scrollHeight - 2) {
                    this.active = this.ids[this.ids.length - 1];
                    return;
                }
                const id = this.ids.find((id) => visibility.get(id));
                if (id) this.active = id;
            }, { root, rootMargin: '0px 0px -55% 0px' });
            this.ids.forEach((id) => {
                const el = root.querySelector('#' + CSS.escape(prefix + id));
                if (el) observer.observe(el);
            });
            this.observers.push(observer);
        },
        select(id, event) {
            const root = event.target.closest('[data-layout]').querySelector('[data-scroll-root]');
            const target = root && root.querySelector('#' + CSS.escape(root.dataset.scrollRoot + id));
            if (! target) return;
            this.seeking = true;
            this.active = id;
            clearTimeout(this.seekTimeout);
            this.seekTimeout = setTimeout(() => (this.seeking = false), 700);
            root.scrollTo({
                top: root.scrollTop + target.getBoundingClientRect().top - root.getBoundingClientRect().top,
                behavior: 'smooth',
            });
        },
    }"
>
    {{-- Desktop (lg and up): sticky sidebar inside a bounded scroll region. --}}
    <tedi:show-at breakpoint="lg">
        <div class="scroll-page" data-layout data-scroll-root="">
            <div class="scroll-page__grid">
                <article>
                    <tedi:text as="h1" modifiers="h1">Pealkiri</tedi:text>
                    @foreach ($chapters as $chapter)
                        <section id="{{ $chapter['id'] }}" class="scroll-page__section">
                            <tedi:text as="h2" modifiers="h3">{{ $chapter['label'] }}</tedi:text>
                            <tedi:text as="p">{{ $lorem }}</tedi:text>
                            <tedi:text as="p">{{ $lorem }}</tedi:text>
                        </section>
                        @foreach ($chapter['children'] as $child)
                            <section id="{{ $child['id'] }}" class="scroll-page__section">
                                <tedi:text as="h3" modifiers="h4">{{ $child['label'] }}</tedi:text>
                                <tedi:text as="p">{{ $lorem }}</tedi:text>
                            </section>
                        @endforeach
                    @endforeach
                </article>
                <tedi:table-of-contents
                    heading="Sisukord"
                    :active-id="$ids[0]"
                    :scroll-active-into-view="true"
                    sticky-max-height="calc(30rem - 3rem)"
                    x-model="active"
                >
                    @foreach ($chapters as $chapter)
                        <tedi:table-of-contents-item :item-id="$chapter['id']">
                            <tedi:link :href="'#'.$chapter['id']" :underline="false" x-on:click.prevent="select('{{ $chapter['id'] }}', $event)">{{ $chapter['label'] }}</tedi:link>
                            @if ($chapter['children'])
                                <x-slot:sub-items>
                                    @foreach ($chapter['children'] as $child)
                                        <tedi:table-of-contents-item :item-id="$child['id']">
                                            <tedi:link :href="'#'.$child['id']" :underline="false" x-on:click.prevent="select('{{ $child['id'] }}', $event)">{{ $child['label'] }}</tedi:link>
                                        </tedi:table-of-contents-item>
                                    @endforeach
                                </x-slot:sub-items>
                            @endif
                        </tedi:table-of-contents-item>
                    @endforeach
                </tedi:table-of-contents>
            </div>
        </div>
    </tedi:show-at>

    {{-- Below lg: the sidebar collapses into a bottom bar + sheet. --}}
    <tedi:hide-at breakpoint="lg">
        <div class="mobile-shell" data-layout>
            <div class="mobile-scroll" data-scroll-root="m-">
                <article>
                    <tedi:text as="h1" modifiers="h1">Pealkiri</tedi:text>
                    @foreach ($chapters as $chapter)
                        <section id="m-{{ $chapter['id'] }}" class="scroll-page__section">
                            <tedi:text as="h2" modifiers="h3">{{ $chapter['label'] }}</tedi:text>
                            <tedi:text as="p">{{ $lorem }}</tedi:text>
                            <tedi:text as="p">{{ $lorem }}</tedi:text>
                        </section>
                        @foreach ($chapter['children'] as $child)
                            <section id="m-{{ $child['id'] }}" class="scroll-page__section">
                                <tedi:text as="h3" modifiers="h4">{{ $child['label'] }}</tedi:text>
                                <tedi:text as="p">{{ $lorem }}</tedi:text>
                            </section>
                        @endforeach
                    @endforeach
                </article>
            </div>
            <tedi:table-of-contents-collapsible
                heading="Sisukord"
                :active-id="$ids[0]"
                :sticky="false"
                x-model="active"
            >
                @foreach ($chapters as $chapter)
                    <tedi:table-of-contents-item :item-id="$chapter['id']">
                        <tedi:link :href="'#'.$chapter['id']" :underline="false" x-on:click.prevent="select('{{ $chapter['id'] }}', $event)">{{ $chapter['label'] }}</tedi:link>
                        @if ($chapter['children'])
                            <x-slot:sub-items>
                                @foreach ($chapter['children'] as $child)
                                    <tedi:table-of-contents-item :item-id="$child['id']">
                                        <tedi:link :href="'#'.$child['id']" :underline="false" x-on:click.prevent="select('{{ $child['id'] }}', $event)">{{ $child['label'] }}</tedi:link>
                                    </tedi:table-of-contents-item>
                                @endforeach
                            </x-slot:sub-items>
                        @endif
                    </tedi:table-of-contents-item>
                @endforeach
            </tedi:table-of-contents-collapsible>
        </div>
    </tedi:hide-at>
</div>
