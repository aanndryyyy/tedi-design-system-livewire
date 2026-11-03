The panel stays in the DOM (hidden) while closed, so its content and state
persist. Type into the field, close the sheet, then reopen - the value is retained.
This port always behaves this way; upstream opts in with `keepMounted`.
