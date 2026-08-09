FileDropzoneComponent is a component that allows users to drag and drop files or select them through a file input.

<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.13.19?node-id=12457-128384&m=dev" target="_BLANK">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/70876f-file-dropzone" target="_BLANK">Zeroheight ↗</a>

<!--
Ported as a documented subset: the markup plus the native file input, with the
file list and its validation supplied by the server (`:files`, `state`,
`has-error`, `error`) instead of by Angular's FileService / ControlValueAccessor
/ async validators. See the component's header comment and the README's
divergence table. Angular's `Replace` story exercises the unported `mode` input
and is therefore not ported.
-->
