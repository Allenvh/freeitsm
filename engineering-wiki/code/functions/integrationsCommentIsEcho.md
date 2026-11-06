# integrationsCommentIsEcho()

Is this inbound comment authored by the account WE authenticate as?

⚠️ NOT USED TO SUPPRESS COMMENTS. Read this before wiring it back in.

This was originally echo suppression's "guard 2", on the reasoning that
anything written by our own account must be our own write coming back. That
reasoning is wrong for comments, and it failed on the very first live test:
the person who creates the API token is usually also a person who comments in
Jira, so *their own* comments were silently swallowed. On a small team that is
not an edge case, it is the normal case — and it fails invisibly, which is the
worst way to fail.

It is redundant anyway. Guard 1 — `integration_comment_map` plus its UNIQUE
key — records the id of every comment we push, so an echo is caught by **id**
no matter who appears to have written it. That is exact, and it closes the
loop on its own: a note we pushed is recognised on the way back regardless of
author.

Kept, and still reachable through `integrationsCommentSkipReason()`'s
`$suppressByAuthor` flag, for two reasons: the events guard 1 genuinely cannot
cover (edits, attachments, field changes — none of which we process yet), and
so that "ignore comments from the connection's own account" can become a
per-connection setting by flipping one argument rather than rewriting this.

⚠️ An UNKNOWN identity is never treated as ours. A tracker that stops sending
an author must not silently swallow every comment — it must let them through
and be noisy, because the failure is then visible instead of invisible.

Pure on purpose: the whole decision is provable without a database.

```php
function integrationsCommentIsEcho(array $event, ?string $accountIdentity): bool
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1218.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `array` |  | a canonical comment event from the provider's `pollChanges()` (reads author_identity) |
| `$accountIdentity` | `?string` |  | the connection's account_identity - the account the token signs in as; null if unknown |

**Returns:** `bool`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsCommentSkipReason()](/code/functions/integrationsCommentSkipReason.md)

