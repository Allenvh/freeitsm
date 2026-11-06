# rpOut()

Sends a JSON reply with the given HTTP status and ends the request.

```php
function rpOut(array $payload, int $code = 200): void
```

Defined in [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md), line 23.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$payload` | `array` |  | the reply, sent as JSON |
| `$code` | `int` | `200` | the HTTP status |

**Returns:** `void`

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
- [includes/report_packs/api_common.php](/code/files/includes/report_packs/api_common.php.md) - in [rpFail()](/code/functions/rpFail.md)

