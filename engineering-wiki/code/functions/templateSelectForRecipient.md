# templateSelectForRecipient()

Choose the template for an event and a recipient, and say WHY it was chosen.

⚠️ SPECIFICITY DECIDES, NOT ORDER. This is the whole design of the sender rules
(discussion #80) and the reason they are safe to hand to an administrator:

     1. a rule naming this exact address      someone@a.com
     2. a rule naming this domain             a.com
     3. a template with no rules at all       everyone

The alternative — evaluate top to bottom, first match wins — requires the admin
to get the rules right AND the ordering right, and gives no sign when the second
is wrong. Here, dragging rows cannot change which template is sent. `display_order`
survives only as a tie-break between two rules of equal specificity, which is a
genuine ambiguity the settings screen flags rather than resolves silently.

⚠️ NO RULES MEANS EVERYONE, not nobody. The permissive case is the empty case, so
a template nobody has restricted keeps working and a fresh install always has a
catch-all. Inverting that would make "forgot to add a rule" mean silence.

Returns ['template' => ?array, 'reason' => string, 'matched' => ?array] — the
reason is what the simulator shows and what is written to the send log when
nothing matches, so the answer to "why did nobody get a reply?" survives the
twelve months after everyone forgets this screen exists.

```php
function templateSelectForRecipient(
    PDO $conn,
    string $eventTrigger,
    ?string $recipientEmail = null
): array
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 266.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$eventTrigger` | `string` |  | the template event, e.g. 'ticket_closed' |
| `$recipientEmail` | `?string` | `null` | the requester's address, matched against sender rules; null or '' matches unrestricted templates only |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 269 | `templateSelectForRecipient` | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |

## Calls

[templateRulesByTemplate()](/code/functions/templateRulesByTemplate.md)

## Called from

- [api/tickets/simulate_email_template.php](/code/files/api/tickets/simulate_email_template.php.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [getActiveTemplate()](/code/functions/getActiveTemplate.md)

