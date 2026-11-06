# helpNum()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [forms/help/_top.php](/code/files/forms/help/_top.php.md), [knowledge/help/_top.php](/code/files/knowledge/help/_top.php.md)

A section's number, from the registry. '' when the registry does not know it.

```php
function helpNum(string $id): string
```

Defined in [forms/help/_top.php](/code/files/forms/help/_top.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `string` |  | the section's key in the topic's 'sections' registry |

**Returns:** `string`

## Called from

- [forms/help/_top.php](/code/files/forms/help/_top.php.md) - in [helpSection()](/code/functions/helpSection.md)
- [knowledge/help/_top.php](/code/files/knowledge/help/_top.php.md) - in [helpSection()](/code/functions/helpSection.md)

