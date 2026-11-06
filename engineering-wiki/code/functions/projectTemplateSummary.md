# projectTemplateSummary()

What the picker shows about a template: counts, without the whole plan.

```php
function projectTemplateSummary(
    string $key,
    string $name,
    ?string $desc,
    array $c,
    bool $builtin,
    bool $active = true
): array
```

Defined in [includes/projects/templates.php](/code/files/includes/projects/templates.php.md), line 337.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the template key: 'builtin:<name>' or 'saved:<id>' |
| `$name` | `string` |  | the template's name |
| `$desc` | `?string` |  | its description; null for none |
| `$c` | `array` |  | the content, already through `projectTemplateNormalise()` |
| `$builtin` | `bool` |  | true for a template that ships with FreeITSM |
| `$active` | `bool` | `true` | false when it is hidden from the new-project picker |

**Returns:** `array`

## Called from

- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateList()](/code/functions/projectTemplateList.md)

