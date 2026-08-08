<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.17.25?node-id=136-14995&m=dev&focus-id=25945-119670" target="_BLANK">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/27208b-timeline" target="_BLANK">Zeroheight ↗</a>

This component is responsive and adapts to mobile layout when below LG (992px) breakpoint.<br />
Timeline consists of several sub-components:
- `TimelineItemComponent`: Used for showing single item in timeline. Item timings are not required. First timing is showed with 16px, rest 14px.
- `TimelineTitleComponent`: Used for showing title in timeline item. If you want to use heading element for title, put it inside title component, wrapping your text.
- `TimelineDescriptionComponent`: Used for showing description in timeline item.

TimelineItemComponent can also contain any other content, it will be rendered under description.
