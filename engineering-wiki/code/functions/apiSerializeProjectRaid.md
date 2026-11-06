# apiSerializeProjectRaid()

RAID log

```php
function apiSerializeProjectRaid(array $r): array
```

Defined in [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), line 462.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a RAID row as ProjectToolsService::raid() returns it, with owner, ticket, escalation and actions |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectRaidCreate()](/code/functions/apiProjectRaidCreate.md), [apiProjectRaidUpdate()](/code/functions/apiProjectRaidUpdate.md)

