# peopleName()

The name to show for a person: preferred name, then display name, email, username, or #id.

```php
function peopleName(array $r): string
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a users row - preferred_name, display_name, email, username and id are read |

**Returns:** `string`

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleListRows()](/code/functions/peopleListRows.md), [personDetail()](/code/functions/personDetail.md)

