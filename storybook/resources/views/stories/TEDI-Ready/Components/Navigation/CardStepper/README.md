<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.59.78?node-id=51362-134115&m=dev" target="_blank">Figma ↗</a><br/><hr/>
`tedi:card-stepper` is the mobile / compact form of a stepper: a single card showing
the active step, a `N / M` counter, a segmented progress bar and a button that
opens the full step list in a bottom sheet. Pair it with `tedi:vertical-stepper` on wider
screens, or use it on its own.

The active step is server state: the arrows and the step list dispatch a `tedi:step-change`
event with the requested index, and the page renders back with the new `active-step`.
