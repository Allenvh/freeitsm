# peopleDomains()

Domains the person is the customer for / the company holds.

```php
function peopleDomains(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 446.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['user' => users id] (domain customer) or ['company' => tenant id] |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 457 | `peopleDomains` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |

## Calls

[domainCustomerReady()](/code/functions/domainCustomerReady.md), [entityLink()](/code/functions/entityLink.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)

