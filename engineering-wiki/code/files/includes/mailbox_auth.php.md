# includes/mailbox_auth.php

> PHP - 43 lines

What "authenticated" means for a mailbox — defined ONCE.

⚠️ WHY THIS EXISTS. Four separate queries each carried their own copy of

    CASE WHEN token_data IS NOT NULL AND token_data != '' THEN 1 ELSE 0 END

all written before Basic IMAP mailboxes existed. `token_data` holds the OAuth
token, so that test is structurally false for IMAP: those mailboxes authenticate
with a stored username + password and never hold a token at all. No credential
is ever written to that column for them — see save_mailbox.php, where the only
mention sets it to NULL.

The result was a mailbox that collected mail perfectly well while the company
routing summary, the routing tester and the topology view all called it "not
authenticated", and the routing panel warned that no mail would flow. The
mailbox list disagreed with all three, because it had been taught about IMAP
separately, in PHP, months later.

The copies were the bug. One expression, used everywhere, cannot drift again.

Columns referenced (`provider`, `imap_password`) are not new: get_mailboxes.php
and get_topology.php already select them, so this adds no schema requirement
that the mailbox screens did not already have.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mailboxAuthenticatedSql()](/code/functions/mailboxAuthenticatedSql.md) | 38 | SQL returning 1 when a `target_mailboxes` row can log in, 0 when it cannot. |

## How it hangs together

**Included by:** [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md), [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md), [api/system/get_topology.php](/code/files/api/system/get_topology.php.md)

