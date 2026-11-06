# csatBuildUrl()

Build the full public URL the user clicks. Uses the host the request came in on so multi-domain installs (test.example.com vs example.com) work without config changes.

```php
function csatBuildUrl(string $token): string
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 62.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$token` | `string` |  | the survey token from ticket_csat_responses.token; URL-encoded here |

**Returns:** `string`

## Calls

[requestScheme()](/code/functions/requestScheme.md)

## Called from

- [includes/csat.php](/code/files/includes/csat.php.md) - in [sendCsatSurvey()](/code/functions/sendCsatSurvey.md)

