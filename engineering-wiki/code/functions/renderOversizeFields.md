# renderOversizeFields()

The oversized-email fields (GH #170), rendered twice: the install defaults on the Mailboxes tab (prefix 'ovd') and a mailbox's own rules in its dialog (prefix 'ovm', which also carries the two limits). files: `oversizeFields()`/ `setOversizeFields()` in the script read and fill them by prefix.

```php
function renderOversizeFields(string $px, bool $withLimits): void
```

Defined in [tickets/settings/index.php](/code/files/tickets/settings/index.php.md), line 76.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$px` | `string` |  | the element id prefix: 'ovd' for the install defaults, 'ovm' for a mailbox's own rules |
| `$withLimits` | `bool` |  | true to include the two size limits (the mailbox dialog) |

**Returns:** `void`

## Calls

[t()](/code/functions/t.md)

## Called from

- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

