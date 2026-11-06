# addCheck()

Appends one result row (key, label, status, detail and an optional fix) to the global `$checks` list the diagnosis returns.

```php
function addCheck(
    string $key,
    string $label,
    string $status,
    string $detail,
    string $fix = ''
): void
```

Defined in [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a stable id for the check, used by the page |
| `$label` | `string` |  | the check's name as shown |
| `$status` | `string` |  | 'ok', 'warn', 'fail' or 'skip' |
| `$detail` | `string` |  | what was found, in plain words |
| `$fix` | `string` | `''` | what to do about it; empty when there is nothing to do |

**Returns:** `void`

## Called from

- [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md)

