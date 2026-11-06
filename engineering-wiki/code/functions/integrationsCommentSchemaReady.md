# integrationsCommentSchemaReady()

Is the comment map present? Separate from `integrationsSchemaReady()` because it arrived a release later: an install that ran Database Verification for V1 but not since has the links but not the map, and must degrade to "no comments come back" rather than throwing inside the poll or the ticket view.

```php
function integrationsCommentSchemaReady(PDO $conn): bool
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1172.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1177 | `integrationsCommentSchemaReady` | read [integration_comment_map](/code/tables/integration_comment_map.md) |  |

## Called from

- [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsKnownCommentIds()](/code/functions/integrationsKnownCommentIds.md), [integrationsRecordOutboundComment()](/code/functions/integrationsRecordOutboundComment.md), [integrationsPullComments()](/code/functions/integrationsPullComments.md)

