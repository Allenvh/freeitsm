# d012_whyMissing()

WHY `is_file()` SAID NO.

download.php's 410 — "That file is recorded but missing from storage." — is one
message covering one test: `!is_file(`$path`)`. That test is false for at least
seven genuinely different reasons, and they have completely different fixes.
Reporting them all as "the file is gone" sends somebody looking for a backup
when the actual fault is a directory they cannot traverse.

  1. Nothing is there                    — restore it
  2. The path is a DIRECTORY             — the stored key is empty or wrong
  3. A broken symlink                    — the target moved
  4. Present but differently CASED       — a Windows to Linux move; Linux cares
  5. The parent directory is unreadable  — `is_file()` cannot stat, so it says no
     or not traversable                    even though the file is right there
  6. open_basedir excludes the path      — PHP refuses before touching the disk
  7. The stored key was rewritten by     — the row holds something that never
     `documentStoragePath()`'s sanitiser     addressed the file it was written as

⚠️ 4 AND 5 ARE THE ONES THAT MATTER MOST, because in both of them the file IS
on the server — which is exactly what somebody reporting this bug will tell you,
and exactly what makes it look like the software is lying to them.

```php
function d012_whyMissing($abs, $baseDir, $storedKey)
```

Defined in [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md), line 157.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$abs` |  |  | the absolute path the file was expected at |
| `$baseDir` |  |  | the storage folder the stored value is relative to; null when unknown |
| `$storedKey` |  |  | the path or key exactly as stored in the database row |

## Calls

[d012_trim()](/code/functions/d012_trim.md)

## Called from

- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)

