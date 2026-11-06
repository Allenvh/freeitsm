# domainStatusState()

For the domain's page: the mode, the trouble, the linked services at risk, an open incident if there is one, and whether this analyst may raise one (Service Status module access).

```php
function domainStatusState(PDO $conn, int $analystId, int $domainId): array
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 136.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain whose page is being drawn |

**Returns:** `array`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [domainSetting()](/code/functions/domainSetting.md), [domainStatusOpenIncident()](/code/functions/domainStatusOpenIncident.md), [domainStatusProblems()](/code/functions/domainStatusProblems.md), [domainStatusReady()](/code/functions/domainStatusReady.md), [domainStatusRow()](/code/functions/domainStatusRow.md), [domainStatusServiceIds()](/code/functions/domainStatusServiceIds.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

