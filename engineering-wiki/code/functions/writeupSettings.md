# writeupSettings()

Tunables, with defaults that a fresh install can live with.

These are settings rather than constants because "how thin is too thin" is a
genuine judgement that differs by desk: a small internal IT team wants the bar
low (any repeated question is worth an article), an MSP with a public portal
wants it high. Nobody has to touch them to get value out of the box.

```php
function writeupSettings(PDO $conn): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 123 | `writeupSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)
- [api/knowledge/assistant_settings.php](/code/files/api/knowledge/assistant_settings.php.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

