# systemAreaSettings()

The settings inside each System card, for the landing page search (#2293).

url => [ [anchor id, heading key], ... ]. The id is on the heading itself in that
page (<h3 id="system-name">), so a result links straight to it; the key is the
SAME translation key the page prints, so the search matches what the page shows,
in every language. A key starting "=" is a literal, for an English-only page.

⚠️ Add a heading here when you add one to a System page - nothing finds it
otherwise. tests/system-settings-search/run.php fails if an id listed here is
missing from its page, so a renamed or deleted heading cannot leave a dead link.

```php
function systemAreaSettings(): array
```

Defined in [system/includes/areas.php](/code/files/system/includes/areas.php.md), line 376.

**Returns:** `array`

## Called from

- [system/index.php](/code/files/system/index.php.md)

