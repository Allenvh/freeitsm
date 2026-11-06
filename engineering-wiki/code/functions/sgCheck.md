# sgCheck()

One tick-off step.

```php
function sgCheck(string $id, string $title, string $body = ''): string
```

Defined in [system/help/sso-groups.php](/code/files/system/help/sso-groups.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `string` |  | the step's key, used to remember its tick in localStorage |
| `$title` | `string` |  | the step heading as HTML (trusted, not escaped) |
| `$body` | `string` | `''` | the step's detail as HTML (trusted, not escaped); '' for none |

**Returns:** `string`

## Called from

- [system/help/sso-groups.php](/code/files/system/help/sso-groups.php.md)

