Bind `value` to a string in single mode and read it back.

Angular's story keeps the label in sync through `(valueChange)`. Output events
aren't re-emitted in this port (CONVENTIONS.md §7.2), so the value shown is the
static initial one.
