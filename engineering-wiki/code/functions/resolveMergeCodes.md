# resolveMergeCodes()

Replace [merge_code] placeholders with actual values.

```php
function resolveMergeCodes(string $template, array $mergeData): string
```

Defined in [includes/template_email.php](/code/files/includes/template_email.php.md), line 431.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$template` | `string` |  | the template text (subject or body) holding [merge_code] placeholders |
| `$mergeData` | `array` |  | [code => value]; values are put in as they are, not escaped |

**Returns:** `string`

## Called from

- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [sendTemplateEmail()](/code/functions/sendTemplateEmail.md)

