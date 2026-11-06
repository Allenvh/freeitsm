# rpFail()

Sends a failure JSON reply ({success: false, error}) with an HTTP status, 400 by default.

```php
function rpFail(string $message, int $code = 400): void
```

Defined in [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md), line 36.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$message` | `string` |  | the error text sent as 'error' |
| `$code` | `int` | `400` | the HTTP status |

**Returns:** `void`

## Calls

[rpOut()](/code/functions/rpOut.md)

## Called from

- [api/reporting/packs/block_data.php](/code/files/api/reporting/packs/block_data.php.md)
- [api/reporting/packs/catalogue.php](/code/files/api/reporting/packs/catalogue.php.md)
- [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md)
- [api/reporting/packs/create.php](/code/files/api/reporting/packs/create.php.md)
- [api/reporting/packs/delete.php](/code/files/api/reporting/packs/delete.php.md)
- [api/reporting/packs/get.php](/code/files/api/reporting/packs/get.php.md)
- [api/reporting/packs/list.php](/code/files/api/reporting/packs/list.php.md)
- [api/reporting/packs/save.php](/code/files/api/reporting/packs/save.php.md)
- [api/reporting/packs/shares.php](/code/files/api/reporting/packs/shares.php.md)

