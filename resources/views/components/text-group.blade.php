{{--
    TEDI Text Group.
    Port of angular/tedi/components/content/text-group/text-group.component.{ts,html}

    The Angular template wraps the label slot content in `<span tedi-label>`
    — ported here as `<tedi:form.label as="span">` using LabelComponent's
    defaults (`color="secondary"`, `size="default"`).

    Angular's host element wraps the `<dl>`: `<tedi-text-group>` carries
    `display: block` (text-group.component.scss:1) and the consumer's attributes,
    while `classes()` goes on the inner `<dl>`. Reproduced literally here — the
    `<dl>` is block either way, so this is structural parity rather than a
    visual fix, but descendant rules elsewhere may key on the element.
--}}
@props([
    /** vertical|horizontal */
    'type' => 'horizontal',
    /** Width for the label (e.g. '200px', '30%'). */
    'labelWidth' => null,
])

<tedi-text-group {{ $attributes }}>
    <dl
        @if ($labelWidth) style="--_label-width: {{ $labelWidth }}" @endif
        @class([
            'tedi-text-group',
            'tedi-text-group--'.$type,
            'tedi-text-group--fixed-label' => (bool) $labelWidth,
        ])
    >
        <dt>
            <tedi:form.label as="span">
                <tedi:text-group-label>{{ $label ?? '' }}</tedi:text-group-label>
            </tedi:form.label>
        </dt>
        <dd>
            <tedi:text-group-value>{{ $value ?? $slot }}</tedi:text-group-value>
        </dd>
    </dl>
</tedi-text-group>
