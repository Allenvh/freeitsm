# mapScormStatus()

Map SCORM status values to our normalised status

```php
function mapScormStatus(string $val): string
```

Defined in [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md), line 191.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$val` | `string` |  | a SCORM lesson_status / completion_status value as sent by the package, any case |

**Returns:** `string`

## Called from

- [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md)

