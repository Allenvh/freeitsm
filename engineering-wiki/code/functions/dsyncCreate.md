# dsyncCreate()

Create a person. Returns the new id, 0 on a preview, or null on failure.

```php
function dsyncCreate(PDO $conn, array $provider, array $p, bool $preview): ?int
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 833.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the auth_providers row - id and tenant_id are read |
| `$p` | `array` |  | the person as `dsyncMapPerson()` (or the CardDAV mapper) returns it |
| `$preview` | `bool` |  | true to create nothing and return 0 |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 838 | `dsyncCreate` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `employee_id`, `directory_username`, `auth_provider_id`, `tenant_id`, `is_managed`) |  |

## Calls

[dsyncLinkIdentity()](/code/functions/dsyncLinkIdentity.md)

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

