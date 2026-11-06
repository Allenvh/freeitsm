# apiParseDate()

Parse an incoming date/time filter or field (ISO 8601, with or without Z, or "YYYY-MM-DD HH:MM:SS") into a UTC DB string. 400s on garbage.

```php
function apiParseDate(string $value, string $field): string
```

Defined in [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the caller-supplied date/time text (untrusted); no offset is read as UTC |
| `$field` | `string` |  | the field or query parameter name, quoted in the 400 message |

**Returns:** `string`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesList()](/code/functions/apiChangesList.md)
- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormSubmissionsList()](/code/functions/apiFormSubmissionsList.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md)
- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmDiagramsList()](/code/functions/apiNmDiagramsList.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsList()](/code/functions/apiProblemsList.md)
- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiStatusIncidentsList()](/code/functions/apiStatusIncidentsList.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsList()](/code/functions/apiTicketsList.md)
- [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md) - in [apiWorkflowExecutionsRespondList()](/code/functions/apiWorkflowExecutionsRespondList.md)

