# dockerTlsWriteFile()

Write a file by renaming a temp copy over it, so a reader never sees half of one.

```php
function dockerTlsWriteFile(string $path, string $contents, int $mode): void
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | absolute path of the file to replace |
| `$contents` | `string` |  | the whole new contents |
| `$mode` | `int` |  | Unix permissions for it, e.g. 0600 for a private key |

**Returns:** `void`

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md)

