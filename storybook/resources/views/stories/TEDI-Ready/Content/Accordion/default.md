
The accordion item is composed of three parts, each owning its own configuration:

- `<tedi-accordion-item>`: owns the item's state (`expanded`) and the inputs shared by header and content (`selected`, `showIconCard`, `defaultExpanded`).
- `<tedi-accordion-item-header>`: owns header appearance and interaction (`titleLayout`, `headerClickable`, expand labels, `headerClass`, etc.). Put the title and any extras (action buttons, badges, descriptions) inside this element using the corresponding slot attributes.
- `<tedi-accordion-item-content>`: owns content styling (`contentClass`). Wraps the collapsible content.


| Selector | Description |
|----------|------------|
| `[tedi-accordion-title]` | The accordion title. |
| `[tedi-accordion-before-title]` | Custom elements before the title. |
| `[tedi-accordion-after-title]` | Custom elements after the title. |
| `[tedi-accordion-start-action]` | Custom actions at the start of the header. |
| `[tedi-accordion-end-action]` | Custom actions at the end of the header. |
| `[tedi-accordion-start-description]` | Description rendered below the title. |
| `[tedi-accordion-end-description]` | Description rendered at the end of the header. |
| `[tedi-accordion-icon-card]` | Template for rendering the icon card layout (child of `<tedi-accordion-item>`). |
      