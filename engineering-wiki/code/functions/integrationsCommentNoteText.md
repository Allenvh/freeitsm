# integrationsCommentNoteText()

How an imported comment reads on the ticket.

The attribution lives in the note TEXT, not only in the display join, because
plenty of things read note_text directly — the REST API, the portal, the AI
write-up. A note that says only "any update?" with no hint it came from a dev
in Jira is worse than no note.

```php
function integrationsCommentNoteText(array $event, array $link): string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1311.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `array` |  | a canonical comment event (author_name, comment_body) |
| `$link` | `array` |  | the integration_links row (external_key, or external_id when there is no key) |

**Returns:** `string`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md)

