# contractNormaliseParty()

Check and normalise the party fields of a contract being saved. Throws InvalidArgumentException with a message fit for the analyst.

A supplier contract keeps supplier_id and clears the customer; a customer
contract keeps the customer (at least one of company or person) and clears the
supplier, so a contract never points both ways. The analyst must be able to see
the customer company and the person's company - otherwise they could put a
contract on a customer they cannot see, or learn a person exists by id.

```php
function contractNormaliseParty(PDO $conn, int $analystId, array $data): array
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 223.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$data` | `array` |  | the submitted contract fields (untrusted) - party_type, supplier_id, customer_tenant_id, customer_user_id are read; the rest is ignored |

**Returns:** array{party_type:string,supplier_id:?int,customer_tenant_id:?int,customer_user_id:?int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 245 | `contractNormaliseParty` | read [tenants](/code/tables/tenants.md) |  |
| 252 | `contractNormaliseParty` | read [users](/code/tables/users.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [t()](/code/functions/t.md)

## Called from

- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::partyFields`

