# replyTemplatesVisibleTo()

Every template this analyst may insert, shared first then their own.

The visibility rule, in one place:
  - SHARED (analyst_id IS NULL) — resolved through `getTenantConfigRows()` like any
    other per-company config list, so a company sees the global defaults it has not
    hidden plus the ones it added. Degrades to "all rows" on a single-company
    install, which is exactly today's behaviour.
  - PRIVATE (analyst_id = me) — never filtered by tenant: it is one person's own
    text, it follows them to whichever company they are looking at.

An administrator gets no special sight of other people's private templates. There is
deliberately no branch here that could give them any.

```php
function replyTemplatesVisibleTo(PDO $conn, int $analystId, bool $activeOnly = true): array
```

Defined in [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$activeOnly` | `bool` | `true` | true for active templates only (inserting); false for every one (managing them) |

**Returns:** array rows with an extra 'scope' => 'shared'|'mine'

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 | `replyTemplatesVisibleTo` | read [ticket_reply_templates](/code/tables/ticket_reply_templates.md) |  |

## Calls

[getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantConfigRows()](/code/functions/getTenantConfigRows.md)

## Called from

- [api/tickets/get_reply_templates.php](/code/files/api/tickets/get_reply_templates.php.md)
- [api/tickets/render_reply_template.php](/code/files/api/tickets/render_reply_template.php.md)

