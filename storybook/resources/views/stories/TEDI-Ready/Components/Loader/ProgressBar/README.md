<a href="https://www.figma.com/design/jWiRIXhHRxwVdMSimKX2FF/TEDI-READY-2.49.74?node-id=25616-189000&m=dev" target="_blank">Figma ↗</a><br>
<a href="https://www.tedi.ee/1ee8444b7/p/87bb13-progress-bar" target="_blank">Zeroheight ↗</a>

Project a `<tedi-feedback-text>` inside `<tedi-progress-bar>` to add a hint
or error message below the bar.

### Announcing changes

Progress bars are not live regions by default. Enable announcements when
users need updates without navigating back to the bar. Upstream's **Announced**
story is a client-side countdown and has no Blade equivalent; in Blade, set
`announce="polite"` (or `assertive`) and the bundled `tediProgressBar`
behaviour publishes each value change a Livewire re-render or an Alpine
`:value` binding makes.

### Responsive inputs

`size`, `labelPosition`, `showValue`, `valuePosition` and `valueLabel` can be
overridden per breakpoint with the `xs`–`xxl` inputs. The base inputs describe
the smallest viewport; each breakpoint input takes a **partial** set of inputs
that layers on top from that breakpoint **and up** (mobile-first). See the
**Responsive** story below.

```html
<tedi-progress-bar
  [value]="40"
  label="Upload"
  labelPosition="top"
  valuePosition="bottom"
  [md]="{ labelPosition: 'horizontal', valuePosition: 'horizontal' }"
/>
```
