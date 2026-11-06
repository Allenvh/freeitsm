# rpTenantLabel()

The company's name for the header field {{company}}.

```php
function rpTenantLabel(PDO $conn, int $analystId, $tenant): string
```

Defined in [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md), line 143.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenant` |  |  | the pack's company: 'active' (or null/''), 'all', or a company id |

**Returns:** `string`

## Calls

[getActiveTenantId()](/code/functions/getActiveTenantId.md), [getAllTenants()](/code/functions/getAllTenants.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md)

