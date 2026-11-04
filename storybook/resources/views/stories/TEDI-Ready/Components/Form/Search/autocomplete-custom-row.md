Objects instead of strings — `bindLabel` names the display property. The field
is filled with the resolved label.

<!--
Angular's text: "`tediSearchSuggestion` renders a richer row.
`suggestionSelect` emits the whole object, while the field is filled with the
resolved label." `tediSearchSuggestion` is an Angular template directive and
`suggestionSelect` an output; their equivalents here are below.
-->
The richer row goes in the `rows` slot — one `tedi:search-suggestion` with `:custom="true"` per suggestion. Pass `:item` on each, and the `tedi-search:select` DOM event hands the object back as `detail.item`.
