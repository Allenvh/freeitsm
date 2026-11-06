# oidcLoadAnalyst()

Loads one analyst row by id, or null when there is none.

```php
function oidcLoadAnalyst(PDO $conn, int $id): ?array
```

Defined in [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), line 329.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the analysts.id to load |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 330 | `oidcLoadAnalyst` | read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

