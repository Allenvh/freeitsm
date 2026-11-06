# mailboxAuthenticatedSql()

SQL returning 1 when a `target_mailboxes` row can log in, 0 when it cannot.

OAuth providers (Microsoft, Google) prove it with a token; Basic IMAP proves it
with a stored password. Either one means the mailbox is authenticated.

```php
function mailboxAuthenticatedSql(string $prefix = ''): string
```

Defined in [includes/mailbox_auth.php](/code/files/includes/mailbox_auth.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$prefix` | `string` | `''` | Optional table alias INCLUDING the dot, e.g. "m." — the current callers all query `target_mailboxes` unaliased. |

**Returns:** string A CASE expression safe to embed in a SELECT list or WHERE.

## Called from

- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md)
- [api/system/get_topology.php](/code/files/api/system/get_topology.php.md)

