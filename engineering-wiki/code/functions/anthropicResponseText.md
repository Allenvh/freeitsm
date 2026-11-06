# anthropicResponseText()

Pull the assistant's text out of the shared client's response shape.

```php
function anthropicResponseText(array $resp): string
```

Defined in [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$resp` | `array` |  | the response array returned by `callAnthropic()` |

**Returns:** `string`

## Called from

- [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md)
- [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md)
- [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md)

