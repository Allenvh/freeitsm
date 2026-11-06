# integrationsResolvePriority()

Our priority → the tracker's priority name.

⚠️ No `*` fallback here, deliberately, unlike the two above. "Everything is a
Task" is a reasonable thing to mean; "every priority is Highest" is not — it
would silently mark a dev team's whole backlog urgent. An unmapped priority
simply travels as text in the description, exactly as it did before mapping.

```php
function integrationsResolvePriority(array $maps, ?int $priorityId): ?string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$maps` | `array` |  | the connection's mappings as returned by `integrationsLoadMaps()` |
| `$priorityId` | `?int` |  | the ticket's priority_id; null gives null |

**Returns:** `?string`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

