# replyTemplateWriteScope()

May this analyst write to this template?

Returns one of: 'shared' (needs the capability), 'mine' (owns it), or null (must be
refused). Kept as a lookup rather than a boolean so the caller can tell "you may not
touch this" apart from "you need the settings permission for this one", and so the
privilege-escalation case has a single home: a template you own is yours to edit,
but turning it INTO a shared one is a settings action and re-checks the capability
at the call site.

```php
function replyTemplateWriteScope(PDO $conn, int $analystId, int $templateId): ?string
```

Defined in [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), line 147.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$templateId` | `int` |  | the ticket_reply_templates id |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 149 | `replyTemplateWriteScope` | read [ticket_reply_templates](/code/tables/ticket_reply_templates.md) |  |

## Called from

- [api/tickets/delete_reply_template.php](/code/files/api/tickets/delete_reply_template.php.md)
- [api/tickets/save_reply_template.php](/code/files/api/tickets/save_reply_template.php.md)

