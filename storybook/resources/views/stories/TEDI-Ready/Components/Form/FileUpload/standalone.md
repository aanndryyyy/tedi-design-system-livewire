The field paints its own surface and renders its own clear button, hint,
error and success text, so it works with no wrapper, and a sibling label
still names the add button. Wrap it in a `tedi-form-field` to lay out the
label and share `size` and `clearable` with it.
