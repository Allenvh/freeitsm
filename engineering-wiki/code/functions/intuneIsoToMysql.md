# intuneIsoToMysql()

Convert a Graph ISO-8601 timestamp ("2025-04-30T08:14:22Z") to a MySQL DATETIME string ("2025-04-30 08:14:22"), in UTC. Returns null for empty input.

```php
function intuneIsoToMysql(?string $iso): ?string
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$iso` | `?string` |  | a Graph ISO-8601 timestamp; null or '' gives null |

**Returns:** `?string`

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneUpsertDevice()](/code/functions/intuneUpsertDevice.md)

