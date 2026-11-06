# parseClaudeJson()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), [api/workflow/_ai_helpers.php](/code/files/api/workflow/_ai_helpers.php.md)

Parse a JSON response from Claude robustly: strip markdown fences, extract the first JSON object/array, and json_decode it.

```php
function parseClaudeJson(string $text): array
```

Defined in [api/cmdb/_ai_helpers.php](/code/files/api/cmdb/_ai_helpers.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the model's reply, which may wrap the JSON in markdown fences or prose |

**Returns:** `array`

## Called from

- [api/cmdb/ai_suggest_properties.php](/code/files/api/cmdb/ai_suggest_properties.php.md)
- [api/cmdb/ai_suggest_questions.php](/code/files/api/cmdb/ai_suggest_questions.php.md)
- [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md)
- [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md)
- [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md)
- [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md)
- [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md) - in [lmsCtParseDrafts()](/code/functions/lmsCtParseDrafts.md)

