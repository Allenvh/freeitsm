# peopleCompanyCards()

The companies page's cards: `peopleCompanies()` plus a few figures for each.

One grouped query per figure rather than `companyDetail()` per company. Each
figure obeys the company page's own rules, so a card and the page it opens
cannot disagree: a module's figure only for an analyst who can open that
module (null, not 0, otherwise - "no tickets" would be a claim), limited to
the companies they can access, and a record with no company counted under
the Default company, as `peopleCompanyMatch()` does.

```php
function peopleCompanyCards(PDO $conn, int $analystId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 153.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 171 | `peopleCompanyCards` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 178 | `peopleCompanyCards` | read [assets](/code/tables/assets.md) |  |
| 183 | `peopleCompanyCards` | read [users](/code/tables/users.md) |  |
| 186 | `peopleCompanyCards` | read [tenants](/code/tables/tenants.md) |  |
| 189 | `peopleCompanyCards` | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [peopleCompanies()](/code/functions/peopleCompanies.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [people/companies.php](/code/files/people/companies.php.md)

