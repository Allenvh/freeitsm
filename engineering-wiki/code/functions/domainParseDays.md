# domainParseDays()

"60, 30, 7" → [60, 30, 7] — sorted high to low, de-duplicated, nonsense dropped. 0 is allowed and means "on the day".

```php
function domainParseDays(string $raw): array
```

Defined in [includes/domains/settings.php](/code/files/includes/domains/settings.php.md), line 255.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | numbers of days separated by commas or spaces, e.g. "60, 30, 7"; over 365 is dropped |

**Returns:** `array`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsDue()](/code/functions/domainAlertsDue.md)
- [includes/domains/settings.php](/code/files/includes/domains/settings.php.md) - in [domainSettingValidate()](/code/functions/domainSettingValidate.md)

