# filesAudit()

Writes one row to the Files audit log, with the analyst's name, the target's path, IP address and user agent.

An unknown action is logged to the PHP error log and skipped.

```php
function filesAudit(
    PDO $conn,
    int $analystId,
    string $action,
    array $t = [],
    array $detail = []
): void
```

Defined in [includes/files/audit.php](/code/files/includes/files/audit.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$action` | `string` |  | what was done - one of FILES_AUDIT_ACTIONS ('view', 'download', 'denied' ...) |
| `$t` | `array` | `[]` | ['folder_id'=>?, 'item_id'=>?, 'version_id'=>?, 'path'=>?] - path is worked out from folder_id (+ item name) when omitted. |
| `$detail` | `array` | `[]` | extra facts for the row, stored as JSON in files_audit.detail; empty stores NULL |

**Returns:** `void`

**Writes:** files_audit - the record of who did what to which folder or file

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `filesAudit` | read [analysts](/code/tables/analysts.md) |  |
| 60 | `filesAudit` | **insert** [files_audit](/code/tables/files_audit.md) (`analyst_id`, `analyst_name`, `action`, `folder_id`, `item_id`, `version_id`, `target_path`, `detail`, `ip_address`, `user_agent`, `created_datetime`) |  |

## Called from

- [api/files/download.php](/code/files/api/files/download.php.md)
- [api/files/folder.php](/code/files/api/files/folder.php.md)
- [api/files/item.php](/code/files/api/files/item.php.md)
- [api/files/list.php](/code/files/api/files/list.php.md)
- [api/files/permissions.php](/code/files/api/files/permissions.php.md)
- [api/files/properties.php](/code/files/api/files/properties.php.md)
- [api/files/search.php](/code/files/api/files/search.php.md)
- [api/files/settings.php](/code/files/api/files/settings.php.md)
- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/files/transfer.php](/code/files/api/files/transfer.php.md) - in [filesTransferItem()](/code/functions/filesTransferItem.md), [filesTransferFolder()](/code/functions/filesTransferFolder.md)
- [api/files/upload.php](/code/files/api/files/upload.php.md)
- [api/files/view.php](/code/files/api/files/view.php.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesNeed()](/code/functions/filesNeed.md)

