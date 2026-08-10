@storybook([
    'name' => 'Default',
    'order' => 1,
    'status' => 'stable',
    'args' => [
        'id' => 'test-1',
        'scrollOnMatch' => true,
    ],
    'argTypes' => [
        'id' => [
            'control' => 'text',
            'description' => 'Id, which is rendered on the wrapper element. It is used to detect the element on the page to scroll to.',
        ],
        'scrollOnMatch' => [
            'control' => 'boolean',
            'description' => 'Scroll to element on match.',
            'table' => ['defaultValue' => ['summary' => 'true']],
        ],
    ],
])

@php $lorem = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at gravida mi, id convallis augue. Donec hendrerit sit amet quam a vehicula. Vestibulum ligula turpis, tempor non lacus et, vestibulum congue massa. Maecenas a sollicitudin dui. Mauris dictum fringilla nibh, sit amet egestas lectus feugiat id. Cras ac felis porttitor, blandit lorem id, gravida felis. Vivamus in tortor vitae neque viverra sodales.'; @endphp

<tedi:row cols="1" :gap="5">
    <tedi:col>
        <tedi:link href="#{{ $id }}">Click here to add #{{ $id }} hash</tedi:link>
    </tedi:col>

    @for ($i = 0; $i < 7; $i++)
        <tedi:col><p>{{ $lorem }}</p></tedi:col>
    @endfor

    <tedi:col>
        <tedi:hash-trigger :id="$id" :scroll-on-match="(bool) $scrollOnMatch">
            Should scroll here with #{{ $id }}
        </tedi:hash-trigger>
    </tedi:col>
</tedi:row>
