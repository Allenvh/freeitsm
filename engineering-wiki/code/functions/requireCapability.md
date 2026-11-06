# requireCapability()

Page gate: bounce an analyst who lacks `$capability` back to the launcher, so a settings URL can't simply be typed. Authoritative (DB-checked), fails closed. Call after functions.php is loaded and the login check. Pair with (or place after) `requireModuleAccess()` — Layer 1 gets you into the module, this decides whether you may configure it.

```php
function requireCapability(string $capability, ?PDO $conn = null): void
```

Defined in [includes/rbac.php](/code/files/includes/rbac.php.md), line 111.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$capability` | `string` |  | the capability key the page needs, a Cap:: constant |
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [lms/editor.php](/code/files/lms/editor.php.md)
- [lms/settings/index.php](/code/files/lms/settings/index.php.md)

