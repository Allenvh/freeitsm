# contractListSql()

The contracts LIST and its dashboard: the active company, or every company the analyst can see when the switcher says "All companies" - the same as Assets and Domains. ['', []] on a single-company install.

```php
function contractListSql(PDO $conn, int $analystId, string $c = 'c'): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** array{0:string,1:array}

## Calls

[activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [contractTenantReady()](/code/functions/contractTenantReady.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md)
- [api/contracts/get_dashboard_stats.php](/code/files/api/contracts/get_dashboard_stats.php.md)

