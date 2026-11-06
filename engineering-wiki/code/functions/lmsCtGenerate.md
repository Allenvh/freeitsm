# lmsCtGenerate()

Ask the AI for `$count` questions on one skill. Returns validated DRAFTS (not saved). A graded question comes back best-to-worst and is given the install's marks in that order; the candidate sees them shuffled.

```php
function lmsCtGenerate(
    array $cfg,
    string $role,
    string $skill,
    string $difficulty,
    string $format,
    int $count,
    array $avoid,
    array $marks
): array
```

Defined in [includes/lms/competency_tests.php](/code/files/includes/lms/competency_tests.php.md), line 465.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cfg` | `array` |  | the AI settings for the LMS, from aiSettingsLoad(`$conn`, 'lms_ai') |
| `$role` | `string` |  | the role being recruited for, as described on the test; '' if none |
| `$skill` | `string` |  | the skill the questions must test |
| `$difficulty` | `string` |  | 'beginner', 'intermediate' or 'advanced' |
| `$format` | `string` |  | 'choice' (one correct answer) or 'graded' (four answers, best first) |
| `$count` | `int` |  | how many questions to ask for, clamped to 1-20 |
| `$avoid` | `array` |  | question texts already in the bank on this skill, so they are not repeated (first 40 sent) |
| `$marks` | `array` |  | the graded marks, best first, from `lmsCtGradedMarks()` |

**Returns:** `array`

## Calls

[aiProviderChat()](/code/functions/aiProviderChat.md), [lmsCtParseDrafts()](/code/functions/lmsCtParseDrafts.md)

## Called from

- [api/lms/tests.php](/code/files/api/lms/tests.php.md)

