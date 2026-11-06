# helpSection()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [forms/help/_top.php](/code/files/forms/help/_top.php.md), [knowledge/help/_top.php](/code/files/knowledge/help/_top.php.md)

Open a section: the hairline, the number badge, the heading and its lead.

```php
function helpSection(string $id, string $heading, string $lead = ''): void
```

Defined in [forms/help/_top.php](/code/files/forms/help/_top.php.md), line 58.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `string` |  | the section's key in the topic registry, also used as the HTML id (escaped) |
| `$heading` | `string` |  | the heading text, plain (escaped) |
| `$lead` | `string` | `''` | the introductory paragraph as trusted HTML (not escaped); '' for none |

**Returns:** `void`

## Calls

[helpNum()](/code/functions/helpNum.md)

## Called from

- [forms/help/blocks.php](/code/files/forms/help/blocks.php.md)
- [forms/help/drafts.php](/code/files/forms/help/drafts.php.md)
- [forms/help/layout.php](/code/files/forms/help/layout.php.md)
- [forms/help/tables.php](/code/files/forms/help/tables.php.md)
- [forms/help/who-can-request.php](/code/files/forms/help/who-can-request.php.md)
- [knowledge/help/documents-help-centre.php](/code/files/knowledge/help/documents-help-centre.php.md)

