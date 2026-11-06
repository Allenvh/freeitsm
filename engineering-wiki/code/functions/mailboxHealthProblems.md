# mailboxHealthProblems()

Every warning here can be dismissed, because every one of them can legitimately be somebody's deliberate choice: a mailbox that genuinely should record no origin, a receive-only IMAP mailbox with no outgoing server, a low-traffic mailbox nobody minds being checked rarely. Dismissing says "I know", and the item drops out of the ! while staying visible, and reversible, inside it.

Errors cannot be dismissed. Reading the wrong inbox or holding credentials
that no longer decrypt is not a configuration choice, it is a fault, and
letting somebody silence it would be the one case where this whole feature
makes things worse than saying nothing.

```php
function mailboxHealthProblems(array $mb, array $context = []): array
```

Defined in [includes/mailbox_health.php](/code/files/includes/mailbox_health.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$mb` | `array` |  | A mailbox row as get_mailboxes.php has prepared it (decrypted, typed, with auth_status already computed). |
| `$context` | `array` | `[]` | 'origin_names' => [id => name] of origins that exist |

**Returns:** array<int, array{key:string, severity:string, title:string, detail:string, dismissible:bool, dismissed:bool}>

## Called from

- [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md)
- [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md)

