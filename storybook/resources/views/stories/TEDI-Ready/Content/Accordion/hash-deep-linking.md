
Items with `openOnHashMatch` auto-expand when `window.location.hash`
matches their `itemId`. Useful for FAQs, settings panels, documentation, or
any page where a sharable link should open straight to a specific section.

Click the links below to update the URL hash. The matching item expands
automatically. The listener also reacts to `hashchange`, so users
navigating between in-page links will see the corresponding item open as
they go. Combine with `allowMultiple` if you want previously opened items
to stay open.

**Note:** `itemId` must be set explicitly — `openOnHashMatch` is a
no-op for items relying on the auto-generated header/content IDs.
        