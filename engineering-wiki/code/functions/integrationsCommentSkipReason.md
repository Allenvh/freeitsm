# integrationsCommentSkipReason()

Should this comment event become a note? The complete decision, pure.

Echo suppression happens on the `already_imported` line: the id of everything
we push is recorded, so our own writes are recognised coming back. That is the
whole guard, and it is exact.

```php
function integrationsCommentSkipReason(
    array $event,
    ?string $accountIdentity,
    array $knownCommentIds,
    string $entityType = 'ticket',
    bool $suppressByAuthor = false
): string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1243.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `array` |  | a canonical comment event from the provider's `pollChanges()` (type, comment_id, comment_body, author_*) |
| `$accountIdentity` | `?string` |  | the connection's account_identity; null if unknown |
| `$knownCommentIds` | `array` |  | ids already imported for this link (guard 1) |
| `$entityType` | `string` | `'ticket'` | the linked work item's type; only 'ticket' is imported |
| `$suppressByAuthor` | `bool` | `false` | also drop anything authored by our own account. **Off**, and deliberately so — see `integrationsCommentIsEcho()`. This is the seam a future per-connection setting flips; it is not a default to restore. |

**Returns:** string '' to import, otherwise the reason it was dropped

## Calls

[integrationsCommentIsEcho()](/code/functions/integrationsCommentIsEcho.md)

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md)

