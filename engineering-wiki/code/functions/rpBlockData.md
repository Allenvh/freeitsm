# rpBlockData()

Fetch one block's data for this viewer. Throws RuntimeException with a message fit to show in the block when the viewer may not see it.

```php
function rpBlockData(PDO $conn, int $analystId, string $handler, $opts, array $criteria): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 385.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$handler` | `string` |  | the block's handler key in `rpHandlers()` |
| `$opts` |  |  | the block's options as saved (cleaned here by `rpCleanOpts()`) |
| `$criteria` | `array` |  | the pack's criteria: range (preset, from, to) and tenant ('active', 'all' or a company id) |

**Returns:** `array`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [rpCleanOpts()](/code/functions/rpCleanOpts.md), [rpHandlers()](/code/functions/rpHandlers.md), [rpModuleName()](/code/functions/rpModuleName.md), [rpResolveRange()](/code/functions/rpResolveRange.md), [t()](/code/functions/t.md)

## Called from

- [api/reporting/packs/block_data.php](/code/files/api/reporting/packs/block_data.php.md)

