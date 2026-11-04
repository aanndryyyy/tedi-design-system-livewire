<a href="https://www.figma.com/file/jWiRIXhHRxwVdMSimKX2FF/TEDI-Design-System-(draft)?type=design&node-id=45-30752&mode=dev" target="_BLANK">Figma ↗</a><br/>
<a href="https://www.tedi.ee/1ee8444b7/p/28835d-icon" target="_BLANK">Zeroheight ↗</a><hr/>
<a href="https://fonts.google.com/icons?icon.set=Material+Icons" target="_BLANK">Official Google Material Icons homepage icons ↗</a><br/>
<a href="https://www.figma.com/community/file/1014241558898418245/material-design-icons?searchSessionId=lvxhc4l5-a6 target="_BLANK">Material Icons Figma ↗</a><br/>
<a href="https://www.figma.com/community/plugin/740272380439725040/material-design-icons" target="_BLANK">Figma Material Symbols plugin ↗</a><hr/>

Use a single icon `type` throughout your application. Each type is a separate
font file. Upstream recommends loading `index-without-icons.css` with only the
style you use (e.g. `icons/outlined.css`) instead of `index.css`. This
package's `dist/tedi.css` is built from core's full `index`, so it declares all
three faces. Browsers only download a face a page actually uses, so an app that
sticks to one `type` fetches one font file. A PWA that precaches every file in
`dist/fonts` downloads all three.
