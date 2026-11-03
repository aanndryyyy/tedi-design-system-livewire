<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.75.90?node-id=2188-34298&m=dev" target="_blank">Figma ↗</a><br>
<a href="https://www.tedi.ee/1ee8444b7/p/429294-skeleton" target="_blank">Zeroheight ↗</a>

`<tedi-skeleton>` stacks the blocks projected into it and announces the
loading state; `<tedi-skeleton-block>` draws one placeholder. Use one wrapper
per loading region, not one per block.

The wrapper carries `aria-busy="true"` and a `role="status"` live region.
Since it is removed once content arrives, set `aria-busy="false"` yourself on
the region that swaps skeleton for content.

<!-- Angular's last paragraph describes the per-breakpoint `xs`–`xxl` inputs and the Responsive story. Breakpoint inputs are not ported (see the README's divergences table), so neither is that story. -->
