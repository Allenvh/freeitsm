# projectTargetValueNames()

Names for list values in rules (status 6 => Retired), keyed "field:id".

```php
function projectTargetValueNames(PDO $conn, array $targets): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 265.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$targets` | `array` |  | targets as `projectTargetsFor()` gives them - done_field and done_value are read |

**Returns:** `array`

## Called from

- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetsDetail()](/code/functions/projectTargetsDetail.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)

