
`headingLevel` wraps the header trigger in a semantic `<h1>`–`<h6>`
element per the WAI-ARIA Accordion Pattern. The wrapper uses
`display: contents` so it adds *no* visual change — it only contributes
to the document outline that assistive technologies, table-of-contents
generators, and SEO crawlers rely on.

Use it whenever the accordion participates in a heading hierarchy: FAQs,
documentation, policy pages, dashboards with sectioned content — anywhere
the document outline matters for screen-reader navigation, table-of-contents
generators, or SEO. Pick a level that fits the surrounding content
(typically one level deeper than the section's own heading — `<h2>`
section → `<h3>` accordion items).

Inspect the DOM to confirm: each header is wrapped in a real `<h3>`,
but the rendered look matches the surrounding accordion items exactly.
        