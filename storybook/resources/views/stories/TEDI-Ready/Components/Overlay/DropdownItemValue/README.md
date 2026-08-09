The DropdownItemValue component provides a reusable structure for rendering option content
in both Select and Dropdown components. It supports built-in checkbox/radio indicators
and flexible layouts for label and meta text.

## Usage

Use this component inside dropdown items or select options to render structured content
with optional selection indicators.

### Basic usage
```html
<tedi:dropdown-item-value>
  <tedi:dropdown-item-value-label>Option 1</tedi:dropdown-item-value-label>
</tedi:dropdown-item-value>
```

### With meta text
```html
<tedi:dropdown-item-value>
  <tedi:dropdown-item-value-label>Tallinn</tedi:dropdown-item-value-label>
  <tedi:dropdown-item-value-meta>3 timeslots</tedi:dropdown-item-value-meta>
</tedi:dropdown-item-value>
```

### With checkbox (multiselect)
```html
<tedi:dropdown-item-value type="checkbox" :selected="$isSelected">
  <tedi:dropdown-item-value-label>Option 1</tedi:dropdown-item-value-label>
</tedi:dropdown-item-value>
```
