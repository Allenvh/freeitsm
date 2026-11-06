# templateRulesByTemplate()

Sender rules for the given templates, keyed by template id, values lowercased.

```php
function templateRulesByTemplate(PDO $conn, array $templateIds): array
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 322.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$templateIds` | `array` |  | ticket_email_templates ids |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 327 | `templateRulesByTemplate` | read [ticket_email_template_rules](/code/tables/ticket_email_template_rules.md) |  |

## Called from

- [api/tickets/get_email_templates.php](/code/files/api/tickets/get_email_templates.php.md)
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [templateSelectForRecipient()](/code/functions/templateSelectForRecipient.md)

