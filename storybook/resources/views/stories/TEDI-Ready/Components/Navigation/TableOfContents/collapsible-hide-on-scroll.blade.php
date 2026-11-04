{{--
    Angular passes the scroll region as a template reference
    (`[scrollContainer]="scroll"`); Blade passes a CSS selector.
--}}
@storybook([
    'name' => 'Collapsible: hide on scroll',
    'order' => 12,
    'status' => 'stable',
    'args' => ['separator' => false],
    'argTypes' => ['separator' => ['table' => ['disable' => true]]],
])

<div>
    <tedi:show-at breakpoint="lg">
        <div style="background: var(--general-surface-primary); padding: 2rem;">
            <tedi:text as="h2" modifiers="h1">Tervisedeklaratsioon</tedi:text>
            <tedi:text as="p" color="secondary" style="margin: 0.5rem 0 0;">
                Tervisedeklaratsioon koosneb 22 kohustuslikust küsimusest. Alusta või
                jätka selle koostamisega allpool.
            </tedi:text>
            <div style="display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 1.5rem; align-items: start; margin-top: 1.5rem;">
                <div style="min-height: 35rem; background: var(--general-surface-primary); border: 1px solid var(--general-border-primary); border-radius: var(--card-radius-rounded);"></div>
                <tedi:table-of-contents heading="Sisukord" :sticky="false" active-id="methods">
                <tedi:table-of-contents-item item-id="intro" :separator="(bool) $separator">
                    <tedi:link href="#intro" :underline="false">Sissejuhatus</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="methods" :separator="(bool) $separator">
                    <tedi:link href="#methods" :underline="false">Meetodid</tedi:link>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="methods-1" :separator="(bool) $separator">
                            <tedi:link href="#methods-1" :underline="false">Andmete kogumine</tedi:link>
                        </tedi:table-of-contents-item>
                        <tedi:table-of-contents-item item-id="methods-2" :separator="(bool) $separator">
                            <tedi:link href="#methods-2" :underline="false">Analüüs</tedi:link>
                        </tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="results" :separator="(bool) $separator">
                    <tedi:link href="#results" :underline="false">Tulemused</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="discussion" :separator="(bool) $separator">
                    <tedi:link href="#discussion" :underline="false">Arutelu</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="conclusion" :separator="(bool) $separator">
                    <tedi:link href="#conclusion" :underline="false">Kokkuvõte</tedi:link>
                </tedi:table-of-contents-item>
                </tedi:table-of-contents>
            </div>
        </div>
    </tedi:show-at>

    <tedi:hide-at breakpoint="lg">
        <div style="position: relative; height: 100dvh; overflow: hidden; transform: translateZ(0); background: var(--general-surface-tertiary);">
            <div id="toc-hide-on-scroll-region" style="height: 100%; overflow-y: auto; padding: var(--layout-page-spacing-top) var(--layout-page-spacing-x) 5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <tedi:link href="#" :underline="false" icon-start="arrow_back">Tervisetõendid ja -deklaratsioonid</tedi:link>
                    <div>
                        <tedi:text as="h2" modifiers="h1">Tervisedeklaratsioon</tedi:text>
                        <tedi:text as="p" color="secondary">
                            Tervisedeklaratsioon koosneb 22 kohustuslikust küsimusest. Alusta või
                            jätka selle koostamisega allpool.
                        </tedi:text>
                    </div>
                </div>
                <div style="min-height: 70rem; margin-top: 1rem; background: var(--general-surface-primary); border: 1px solid var(--general-border-primary); border-radius: var(--card-radius-rounded);"></div>
            </div>
            <tedi:table-of-contents-collapsible
                heading="Sisukord"
                active-id="methods"
                :hide-on-scroll="true"
                scroll-container="#toc-hide-on-scroll-region"
            >
                <tedi:table-of-contents-item item-id="intro" :separator="(bool) $separator">
                    <tedi:link href="#intro" :underline="false">Sissejuhatus</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="methods" :separator="(bool) $separator">
                    <tedi:link href="#methods" :underline="false">Meetodid</tedi:link>
                    <x-slot:sub-items>
                        <tedi:table-of-contents-item item-id="methods-1" :separator="(bool) $separator">
                            <tedi:link href="#methods-1" :underline="false">Andmete kogumine</tedi:link>
                        </tedi:table-of-contents-item>
                        <tedi:table-of-contents-item item-id="methods-2" :separator="(bool) $separator">
                            <tedi:link href="#methods-2" :underline="false">Analüüs</tedi:link>
                        </tedi:table-of-contents-item>
                    </x-slot:sub-items>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="results" :separator="(bool) $separator">
                    <tedi:link href="#results" :underline="false">Tulemused</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="discussion" :separator="(bool) $separator">
                    <tedi:link href="#discussion" :underline="false">Arutelu</tedi:link>
                </tedi:table-of-contents-item>
                <tedi:table-of-contents-item item-id="conclusion" :separator="(bool) $separator">
                    <tedi:link href="#conclusion" :underline="false">Kokkuvõte</tedi:link>
                </tedi:table-of-contents-item>
            </tedi:table-of-contents-collapsible>
        </div>
    </tedi:hide-at>
</div>
