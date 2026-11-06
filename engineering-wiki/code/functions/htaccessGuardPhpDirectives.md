# htaccessGuardPhpDirectives()

Wrap every unguarded PHP directive in an .htaccess in <IfModule> blocks for mod_php 8 (mod_php.c) and mod_php 7 (mod_php7.c), keeping its indentation and the file's own line endings. Returns the text unchanged when there is nothing to do, so it is safe to run on every file every time.

Wrapping rather than deleting, so the repair never changes what mod_php does:
a file whose only copy of the directive was the unguarded one keeps it.

```php
function htaccessGuardPhpDirectives(string $text): string
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 536.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the whole .htaccess file's contents; its line endings are kept |

**Returns:** `string`

## Calls

[htaccessUnguardedPhpDirectives()](/code/functions/htaccessUnguardedPhpDirectives.md)

## Called from

- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

