# entityVisit()

Record that this analyst has just looked at this record — the PAGE LOAD path.

Call it from a record page AFTER that page has decided the analyst may see the
record. It is a write on a GET, so it deliberately does nothing at all for a
request that is not a person opening a page: see `recentTrailShouldRecord()`.
Records opened without a page load go through `recentTrailWrite()` instead.

🔴 NEVER THROWS. This is bookkeeping hung off the side of pages that have real
work to do — a ticket must still open if the trail table is missing, or is
mid-verification, or the install has not run Database Verification since
upgrading. Every failure here is swallowed on purpose.

```php
function entityVisit(string $type, int $id, ?PDO $conn = null): void
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the record type, a RECENT_TRAIL_MODULES key such as 'ticket' or 'asset' |
| `$id` | `int` |  | the record's id |
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[recentTrailShouldRecord()](/code/functions/recentTrailShouldRecord.md), [recentTrailWrite()](/code/functions/recentTrailWrite.md)

## Called from

- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [contracts/view.php](/code/files/contracts/view.php.md)
- [domains/view.php](/code/files/domains/view.php.md)
- [people/company.php](/code/files/people/company.php.md)
- [people/contact.php](/code/files/people/contact.php.md)
- [people/person.php](/code/files/people/person.php.md)
- [people/supplier.php](/code/files/people/supplier.php.md)

