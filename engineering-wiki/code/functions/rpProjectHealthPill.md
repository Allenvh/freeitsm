# rpProjectHealthPill()

A health pill: the words the Projects module uses, in its colours.

```php
function rpProjectHealthPill(?string $h): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$h` | `?string` |  | the shown health: 'green', 'amber', 'red', or null for none |

**Returns:** `array`

## Calls

[t()](/code/functions/t.md)

## Called from

- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectsStatus()](/code/functions/rpProjectsStatus.md)

