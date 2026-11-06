# analystAnonymise()

Anonymise: everything Deactivate does, then the person behind the records is removed - personal settings and sign-in material deleted, the account's name and contact details replaced, and copies of the name next to their id replaced. The records themselves stay, credited to "Former analyst #N". Irreversible.

```php
function analystAnonymise(PDO $conn, ActorContext $ctx, int $analystId): void
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 601.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$analystId` | `int` |  | the analyst to anonymise |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 603 | `analystAnonymise` | read [analysts](/code/tables/analysts.md) |  |
| 612 | `analystAnonymise` | **update** [analysts](/code/tables/analysts.md) (`username`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `password_hash`, `totp_secret`, `totp_enabled`, `trust_device_enabled`, `auth_provider_id`) |  |

## Calls

[analystFormerName()](/code/functions/analystFormerName.md), [analystLcAssertActionable()](/code/functions/analystLcAssertActionable.md), [analystLcColumnExists()](/code/functions/analystLcColumnExists.md), [analystLcLog()](/code/functions/analystLcLog.md), [analystNameAuditTables()](/code/functions/analystNameAuditTables.md), [analystNameCopies()](/code/functions/analystNameCopies.md), [analystRemovePersonal()](/code/functions/analystRemovePersonal.md)

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)

