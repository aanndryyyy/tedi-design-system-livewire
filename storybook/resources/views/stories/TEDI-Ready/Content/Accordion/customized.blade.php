@storybook([
    'name' => 'Customized',
    'order' => 5,
    'status' => 'subset',
    'args' => [],
    'argTypes' => [],
])

{{--
    Angular's last two examples wrap decorative content (avatar, badge, long
    description) in `*showAt="'md'"` so it's hidden below the md breakpoint.
    Breakpoint-conditional rendering isn't ported (README divergence table),
    so those two items are dropped here; the rest of the customization
    gallery (custom header classes, a header-embedded checkbox, a
    show-more/show-less end action) is unaffected.
--}}
@php
    $contentExample = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.';
@endphp

<style>
    .custom-header.tedi-accordion-item-header,
    .custom-content.tedi-accordion-item-content {
        background: var(--card-background-brand-quaternary);
    }
    .custom-header.tedi-accordion-item-header .tedi-accordion-item-header__start {
        gap: 1rem;
    }
    .custom-title.tedi-accordion-item-header .tedi-accordion-item-header__title-main span {
        font-weight: var(--heading-h6-weight);
    }
</style>

<div style="display: flex; flex-direction: column; gap: 1rem;">
    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header title-layout="fill">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:after-title>
                    <tedi:status-badge color="brand" text="Avalik" />
                </x-slot:after-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header title-layout="fill">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:before-title>
                    <tedi:icon name="account_circle" color="brand" background="brand-secondary" :size="16" />
                </x-slot:before-title>
                <x-slot:after-title>
                    <tedi:status-badge color="neutral" text="Uus" />
                </x-slot:after-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item :selected="false">
            <tedi:accordion-item-header :header-clickable="false" :show-expand-label="false" expand-action-position="start">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:end-action>
                    <tedi:form.label as="span" color="primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
                        <tedi:checkbox />
                        Vali
                    </tedi:form.label>
                </x-slot:end-action>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>

    <tedi:accordion>
        <tedi:accordion-item>
            <tedi:accordion-item-header title-layout="fill">
                <x-slot:title>Pealkiri</x-slot:title>
                <x-slot:after-title>
                    <tedi:status-badge color="success" text="Kinnitatud" />
                </x-slot:after-title>
            </tedi:accordion-item-header>
            <tedi:accordion-item-content>{{ $contentExample }}</tedi:accordion-item-content>
        </tedi:accordion-item>
    </tedi:accordion>
</div>
