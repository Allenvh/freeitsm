# integrationsAbsoluteUrl()

An ABSOLUTE url for a path in this install, for links we send OUT.

⚠️ `BASE_URL` is deliberately a path — `/` or `/freeitsm-app/` — because every
internal page link wants that. Put it in a Jira issue or an Azure DevOps work
item and the developer gets `/tickets/?id=409`, which resolves against
*their* tracker's host and 404s. The whole reason we put the link there is so
somebody can click back to the ticket, so it has never done its job.

The resolution itself now lives in includes/public_url.php, because the
[ticket_url] merge code needs the identical answer (discussion #80) and two
copies of "how is this install addressed" is exactly the pair that drifts.
This name is kept: it is what every provider calls, and it reads correctly at
those call sites.

```php
function integrationsAbsoluteUrl(PDO $conn, string $path): string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 199.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$path` | `string` |  | a path inside this install, e.g. 'tickets/?id=409' - a leading slash is optional |

**Returns:** `string`

## Calls

[publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md)

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_escalate_to_tracker`

