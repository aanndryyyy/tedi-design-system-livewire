{{--
    Published from area17/blast, with two changes:

    1. The <html> tag carries the TEDI theme class, which the design system's
       tokens hang off. Stories can override it per story with a `theme` arg
       ('default' | 'dark'), otherwise the light theme applies.
    2. The scripts at the end of <body> are emitted inert and booted once per
       document — see the comment there.
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="tedi-theme--{{ $theme ?? 'default' }}">
    <head>

    @if (!empty($css))
        @foreach ($css as $key => $asset)
            <link rel="stylesheet" href="{{ $asset }}">
        @endforeach
    @endif

    @if ($canvasBgColor)
        <style>
            .sb-show-main {
                background-color: {{ $canvasBgColor }}
            }
        </style>
    @endif
</head>

<body>
    @include('stories.'. $component)

    {{--
        Every script below is emitted inert, then booted exactly once per
        document by the guard at the end.

        Blast hands this whole document to Storybook, which injects it with
        `canvasElement.innerHTML = html` and then calls `simulatePageLoad()` to
        re-execute every script tag it finds. Since .storybook/preview.js
        renders docs stories inline, all of a component's stories now share a
        single document — so a plain src'd script would run once per story and
        boot Livewire and Alpine a dozen times over ("Detected multiple
        instances of Livewire running", "Uncaught TypeError: Cannot redefine
        property: $persist"). The same duplication applies in canvas view, where
        Storybook reuses one preview document across story navigations.

        `type="text/blast-deferred"` is not in simulatePageLoad's
        runScriptTypes, so it skips these tags and leaves them in the DOM for
        the guard to clone.
    --}}
    @if (!empty($js))
        @foreach ($js as $key => $asset)
            @php
                $path = $asset['path'] ?? (is_string($asset) ? $asset : null);
                $type = $asset['type'] ?? null;
            @endphp

            @if ($path)
                <script
                    type="text/blast-deferred"
                    data-blast-once
                    @if ($type)
                        data-blast-type="{{ $type }}"
                    @endif
                    src="{{ $path }}"
                ></script>
            @endif
        @endforeach
    @endif

    {{-- Boots Alpine, which the interactive components (accordion, tabs,
         carousel, header toggle) rely on. Emitted last so tedi.js has already
         registered its Alpine.data() components on the alpine:init event.
         Buffered rather than echoed directly so its tags can be made inert
         the same way. --}}
    @php
        ob_start();
    @endphp
    @livewireScripts
    @php
        $livewireScripts = str_replace(
            '<script',
            '<script type="text/blast-deferred" data-blast-once',
            ob_get_clean(),
        );
    @endphp
    {!! $livewireScripts !!}

    <script>
        (function () {
            if (window.__blastScriptsBooted) {
                return;
            }

            window.__blastScriptsBooted = true;

            var deferredScripts = document.querySelectorAll(
                'script[data-blast-once]',
            );
            var pending = deferredScripts.length;

            // Storybook fires its synthetic DOMContentLoaded as soon as the last
            // script it inserted has run — but it skipped ours, so by then these
            // are still downloading and Livewire has not yet registered its
            // listener. The real DOMContentLoaded fired with iframe.html, long
            // before any story existed, so without a second one Livewire never
            // calls Alpine.start() and nothing with x-data initialises.
            var bootWhenReady = function () {
                if (--pending > 0) {
                    return;
                }

                document.dispatchEvent(
                    new Event('DOMContentLoaded', {
                        bubbles: true,
                        cancelable: false,
                    }),
                );
            };

            deferredScripts.forEach(function (deferred) {
                    var script = document.createElement('script');

                    Array.prototype.forEach.call(
                        deferred.attributes,
                        function (attribute) {
                            if (
                                attribute.name === 'type' ||
                                attribute.name === 'data-blast-once' ||
                                attribute.name === 'data-blast-type'
                            ) {
                                return;
                            }

                            script.setAttribute(attribute.name, attribute.value);
                        },
                    );

                    if (deferred.dataset.blastType) {
                        script.type = deferred.dataset.blastType;
                    }

                    if (!deferred.hasAttribute('src')) {
                        script.textContent = deferred.textContent;
                    }

                    // Dynamically created scripts default to async, which would
                    // let Livewire boot Alpine before tedi.js has registered its
                    // Alpine.data() components.
                    script.async = false;

                    if (deferred.hasAttribute('src')) {
                        script.onload = bootWhenReady;
                        script.onerror = bootWhenReady;
                        document.head.appendChild(script);
                    } else {
                        document.head.appendChild(script);
                        bootWhenReady();
                    }
                });
        })();
    </script>
</body>
</html>
