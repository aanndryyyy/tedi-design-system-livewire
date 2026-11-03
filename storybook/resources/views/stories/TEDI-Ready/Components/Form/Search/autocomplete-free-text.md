Enter with no suggestion highlighted dispatches `tedi-search:search` with the
raw field value instead of selecting, so the combobox doubles as a plain search
box. ArrowDown first, then Enter, selects the highlighted suggestion instead.

<!-- Angular's text says Enter "emits `searchEvent`", an Angular output; here it is the `tedi-search:search` DOM event on the input, with `detail.value`. -->
