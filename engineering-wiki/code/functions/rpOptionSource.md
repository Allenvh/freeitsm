# rpOptionSource()

Choices for a 'multi' option's source (the Properties pane's checkboxes).

```php
function rpOptionSource(PDO $conn, string $source, int $analystId = 0): array
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 415.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` |  | the option's source: 'projects' or 'status_services'; anything else gives [] |
| `$analystId` | `int` | `0` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 422 | `rpOptionSource` | read [status_services](/code/tables/status_services.md) |  |

## Calls

[rpProjectChoices()](/code/functions/rpProjectChoices.md)

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpCatalogue()](/code/functions/rpCatalogue.md)

