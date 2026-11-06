# templateEventAudience()

Who an event's email is addressed to: 'requester' or 'analyst'.

🔑 THIS IS THE ONLY DEFINITION. The settings screen shows the audience in
its "Sends to" column and must not keep a second copy of this map - a screen
that says "Requester" while the sender picks the analyst is worse than no
column at all. api/tickets/get_email_templates.php calls this and hands the
answer to the browser.

Everything except analyst_assigned goes to the requester, and that is the
safe default for any event added later: an email that reaches the customer
when it should not have is a bad day, but an internal notification quietly
sent to nobody is a feature that looks broken.

```php
function templateEventAudience(string $eventTrigger): string
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 30.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$eventTrigger` | `string` |  | the template event, e.g. 'new_ticket_email', 'analyst_assigned', 'ticket_closed' |

**Returns:** `string`

## Called from

- [api/tickets/get_email_templates.php](/code/files/api/tickets/get_email_templates.php.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)

