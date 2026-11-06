# setupSafeDbError()

Keep the diagnostic part of a PDO connection error, drop the reconnaissance. PDO reports e.g. "SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost'": the code is what tells an installer what to fix, the tail names the account and host.

```php
function setupSafeDbError(string $message): string
```

Defined in [setup/index.php](/code/files/setup/index.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the PDOException message; '' comes back when it has no SQLSTATE prefix |

**Returns:** `string`

## Called from

- [setup/index.php](/code/files/setup/index.php.md)

