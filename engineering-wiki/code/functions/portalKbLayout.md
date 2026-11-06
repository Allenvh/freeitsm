# portalKbLayout()

The layout this portal user should see. Always one of PORTAL_KB_LAYOUTS.

```php
function portalKbLayout(PDO $conn, int $userId): string
```

Defined in [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user (users id) |

**Returns:** `string`

## Calls

[portalPrefGet()](/code/functions/portalPrefGet.md)

*Nothing calls this function by name.*

