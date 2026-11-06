# teamsPackageFail()

Answers {success: false, error} as JSON and stops - used instead of the zip download when the package cannot be built.

```php
function teamsPackageFail(string $message): void
```

Defined in [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md), line 25.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error shown to the admin |

**Returns:** `void`

## Called from

- [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md)

