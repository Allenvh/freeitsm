# apiParseNaiveDatetime()

Parse a NAIVE local datetime ("YYYY-MM-DD HH:MM[:SS]", T separator OK, date-only OK). 422s on timezone designators — this resource is naive-local by design (see header).

```php
function apiParseNaiveDatetime($value, string $field): ?string
```

Defined in [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the caller-supplied datetime (untrusted), server-local with no offset; null or '' gives null |
| `$field` | `string` |  | the field or query parameter name, quoted in the 422 message |

**Returns:** `?string`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md) - in [apiCalendarEventsList()](/code/functions/apiCalendarEventsList.md)

