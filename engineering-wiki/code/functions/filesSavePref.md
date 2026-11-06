# filesSavePref()

Saves one of the analyst's own Files preferences (insert or update).

```php
function filesSavePref(PDO $conn, int $analystId, string $key, string $value): void
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 162.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the preference_key with its files_ prefix, e.g. 'files_view' or 'files_windows' |
| `$value` | `string` |  | the value to store, already checked by the caller (JSON for 'files_windows') |

**Returns:** `void`

**Writes:** user_preferences(preference_value) - per-analyst choices such as the view

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `filesSavePref` | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## Called from

- [api/files/prefs.php](/code/files/api/files/prefs.php.md)

