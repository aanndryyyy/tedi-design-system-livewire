@storybook([
    'name' => 'Animation Direction',
    'order' => 3,
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

<style>
    .scroll-visibility-story-bar {
        position: fixed;
        z-index: 10;
        align-content: center;
        color: white;
        text-align: center;
        background: rgb(0, 72, 130);
    }
    .scroll-visibility-story-bar--up { top: 0; width: 100%; height: 48px; }
    .scroll-visibility-story-bar--down { bottom: 0; width: 100%; height: 48px; }
    .scroll-visibility-story-bar--left { top: 0; left: 0; width: 48px; height: 100vh; }
    .scroll-visibility-story-bar--right { top: 0; right: 0; width: 48px; height: 100vh; }
</style>

<div>
    @foreach (['up', 'down', 'left', 'right'] as $direction)
        <tedi:scroll-visibility :animation-direction="$direction" :scroll-distance="$scrollDistance">
            <nav class="scroll-visibility-story-bar scroll-visibility-story-bar--{{ $direction }}">{{ ucfirst($direction) }}</nav>
        </tedi:scroll-visibility>
    @endforeach

    <tedi:text>{{ $lorem }}</tedi:text>
</div>
