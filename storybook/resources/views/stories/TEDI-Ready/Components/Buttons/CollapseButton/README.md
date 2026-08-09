<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.49.74?node-id=15433-138256&m=dev" target="_blank">Figma ↗</a><br />
<a href="https://www.tedi.ee/1ee8444b7/p/9469bf-collapse-button" target="_BLANK">Zeroheight ↗</a>

<p>
  Standalone toggle button used by <code>&lt;tedi:collapse&gt;</code> and the
  table's expandable rows.
</p>

<!--
  Divergence from the Angular docs: upstream says "the parent owns the `open`
  state and listens to `openChange`". `output()` events are not re-emitted in
  this port (CONVENTIONS.md §3), so the open state lives in Alpine instead: the
  button declares its own `x-data` by default, or binds to a parent-owned
  expression when one is passed as `state` (which is what `<tedi:collapse>`
  does). Consumers needing server awareness bind `wire:click` on the button.
-->
