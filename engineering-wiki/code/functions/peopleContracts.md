# peopleContracts()

Contracts where the person / the company is the CUSTOMER (#153 step 1).

```php
function peopleContracts(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 405.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['user' => users id] or ['company' => tenant id] - the contract's customer |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 420 | `peopleContracts` | read [contracts](/code/tables/contracts.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |

## Calls

[contractCustomerTenantExpr()](/code/functions/contractCustomerTenantExpr.md), [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartyReady()](/code/functions/contractPartyReady.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [entityLink()](/code/functions/entityLink.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)

