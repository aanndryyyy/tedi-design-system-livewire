A collapsible card can hold **child row-groups** — repeated blocks of rows (e.g. one certificate
each) shown on a muted background inside the accordion body. `TableCard` renders the header and
main rows; pass the child groups as `children` (`Card.Content` + `TextGroup.List`) so they share
the same tokens and collapse together.
