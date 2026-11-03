The OptionContent component provides a reusable structure for rendering option content
in both Select and Dropdown components. It supports built-in checkbox/radio indicators
and flexible layouts for label and meta text.

## Usage

Use this component inside dropdown items or select options to render structured content
with optional selection indicators.

### Basic usage
```html
<tedi:option-content>
  <tedi:option-content-label>Option 1</tedi:option-content-label>
</tedi:option-content>
```

### With meta text
```html
<tedi:option-content>
  <tedi:option-content-label>Tallinn</tedi:option-content-label>
  <tedi:option-content-meta>3 timeslots</tedi:option-content-meta>
</tedi:option-content>
```

### With checkbox (multiselect)
```html
<tedi:option-content type="checkbox" :selected="$isSelected">
  <tedi:option-content-label>Option 1</tedi:option-content-label>
</tedi:option-content>
```
