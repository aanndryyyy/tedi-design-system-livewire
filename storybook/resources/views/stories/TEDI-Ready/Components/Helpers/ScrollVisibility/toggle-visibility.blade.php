@storybook([
    'name' => 'Toggle Visibility',
    'order' => 2,
    'status' => 'stable',
    'design' => 'https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-(work-in-progress)?node-id=10758-111106&m=dev',
    'args' => [
        'scrollDistance' => 100,
    ],
    'argTypes' => [
        'scrollDistance' => ['control' => 'number'],
    ],
])

@php $lorem = str_repeat('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer eget convallis quam, eu rhoncus turpis. ', 200); @endphp

{{-- Scroll down to hide the bar, then back up to bring it straight back —
     that second half is what toggleVisibility adds. --}}
<div>
    <tedi:scroll-visibility animation-direction="up" toggle-visibility :scroll-distance="$scrollDistance">
        <nav style="width: 100%; position: fixed; top: 0; background: rgb(0, 72, 130); color: white; height: 48px; align-content: center; text-align: center; z-index: 10">
            Scroll down to hide, up to show
        </nav>
    </tedi:scroll-visibility>

    <tedi:text>{{ $lorem }}</tedi:text>
</div>
