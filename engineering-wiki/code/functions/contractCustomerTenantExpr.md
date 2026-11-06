# contractCustomerTenantExpr()

The SQL expression for a contract's customer company, for alias `$c`. Uses the customer person's company when no company was chosen.

```php
function contractCustomerTenantExpr(string $c = 'c'): string
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 77.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `string` | `'c'` | the alias the calling query gives the contracts table (trusted - put straight into SQL) |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `contractCustomerTenantExpr` | read [users](/code/tables/users.md) |  |

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleContracts()](/code/functions/peopleContracts.md)

