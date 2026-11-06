# messagingTemplateVarCount()

How many distinct {{n}} placeholders a template body uses (the highest index). e.g. "Hi {{1}}, ref {{2}}" → 2.

```php
function messagingTemplateVarCount(string $body): int
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 352.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the template text with {{1}}, {{2}} ... placeholders |

**Returns:** `int`

## Called from

- [api/messaging/get_templates.php](/code/files/api/messaging/get_templates.php.md)
- [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md)

