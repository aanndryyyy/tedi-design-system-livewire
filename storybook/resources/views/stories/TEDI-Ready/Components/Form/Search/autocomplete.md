Suggestions filter live against the field value, with the matched substring
bolded and a "no results" row when nothing matches. Focus stays in the input;
ArrowUp/Down highlight an option, Enter accepts it, Esc closes.

<!--
Angular's text continues: "Enter with nothing highlighted emits `searchEvent`
instead." and "Search does not filter — pass already-filtered `suggestions` and
react to `valueChange`, so sync and async work the same way." `searchEvent` and
`valueChange` are Angular outputs; their equivalents here are below. This
static Storybook render has no server, so the list shown is the one for the
initial "Mar" query and does not refilter as you type.
-->
Enter with nothing highlighted dispatches the `tedi-search:search` DOM event on the input instead.

Search does not filter — pass already-filtered `suggestions` and bind the field with `wire:model.live`, so the list is recomputed on the server as you type. This static Storybook render has no server, so here the list does not refilter.
