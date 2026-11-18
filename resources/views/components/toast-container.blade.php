{{--
    TEDI Toast container.
    Port of angular/src/tedi/components/notifications/toast/toast-container.component.ts
    and, through the bundled `tediToastContainer` behaviour
    (resources/js/src/toast-container.js), of angular/src/tedi/services/toast/
    toast.service.ts and toast-announcer.service.ts.

    Upstream, ToastService creates the container on demand in a CDK overlay and
    renders its toasts into it. Blade cannot create a component from JS, so the
    container is placed once, in the layout, and the behaviour stands in for the
    service:

        `tedi:toast-container` (once, e.g. before the closing body tag)
        TediToast.success('Salvestatud', 'Muudatused salvestati.')
        $this->dispatch('tedi-toast', title: 'Salvestatud', type: 'success');

    The markup is upstream's: a `<tedi-toast-container class="tedi-toast-container">`
    root holding one `.tedi-toast-container__position.--{position}` group per
    position that has toasts, each holding `tedi:toast`s. Groups are rendered
    only when they have a toast, as upstream's `@if (… length > 0)` does.

    Server-rendered toasts: `tedi:toast` children in the slot (flash messages,
    say) render in the bottom-right group, upstream's default position, so they
    are on the page without JS. The behaviour then adopts each one as if
    ToastService had shown it — moves it to the group its `position` names,
    starts its `duration` timer, pauses on hover/focus per `pauseOnHover`, and
    announces it per `role`.

    Toasts spawned from JS are clones of the `<template>` below, which is
    `tedi:toast` itself, so they have the same markup and the translated close
    button. `TediToast.show()` / the `tedi-toast` event take upstream's
    ToastConfig keys: title, content, type, icon, role, duration,
    showProgressBar, pauseOnHover, position, id.

    The root is `wire:ignore`d. Its children are client state once the page
    loads, and a Livewire morph would otherwise strip the spawned toasts and
    re-insert the adopted ones. The slot is therefore read on the first render
    only; later toasts from a Livewire component go through `dispatch`.

    Divergences:
    - The vendored stylesheet sets `z-index: var(--z-index-toast)`, a token the
      installed core does not define, so it computes to `auto`. Upstream's
      stacking comes from CDK's overlay container instead. Define
      `--z-index-toast` yourself to lift the toasts above a positioned
      header or modal.
    - Every container on a page shares one ToastService: a toast goes to the
      most recently initialised container only.
--}}
<tedi-toast-container
    {{ $attributes->class(['tedi-toast-container']) }}
    x-data="tediToastContainer"
    wire:ignore
>
    <template x-ref="prototype">
        <tedi:toast title="Title" icon="info" :show-progress-bar="true" :duration="1" />
    </template>
    @if ($slot->hasActualContent())
        <div class="tedi-toast-container__position tedi-toast-container__position--bottom-right">
            {{ $slot }}
        </div>
    @endif
</tedi-toast-container>
