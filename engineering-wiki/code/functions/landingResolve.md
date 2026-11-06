# landingResolve()

Where should this visitor go? Returns a relative path from the app root.

The cookie wins when present and valid. It is only ever written from an
analyst's saved preference, so trusting it costs nothing an analyst could not
already do by typing either login URL directly — and it is validated anyway.

```php
function landingResolve(PDO $conn): string
```

Defined in [includes/landing.php](/code/files/includes/landing.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[landingInstallDefault()](/code/functions/landingInstallDefault.md), [landingIsValid()](/code/functions/landingIsValid.md), [landingTargets()](/code/functions/landingTargets.md)

## Called from

- [index.php](/code/files/index.php.md)

