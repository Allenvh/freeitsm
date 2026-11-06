# contractOwnerSql()

The owning company, for alias `$c`: tenant_id (NULL read as Default) and company_name. A NULL column before Database Verification, so callers need no second branch.

```php
function contractOwnerSql(PDO $conn, string $c = 'c'): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** array{0:string,1:string} [select columns (with leading comma), joins]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `contractOwnerSql` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[contractTenantReady()](/code/functions/contractTenantReady.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/contracts/get_contract.php](/code/files/api/contracts/get_contract.php.md)
- [api/contracts/get_contracts.php](/code/files/api/contracts/get_contracts.php.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractSelect()](/code/functions/apiContractSelect.md)

