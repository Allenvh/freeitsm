# catalogueApprovalGate()

Should a submission of this form be gated behind approval, and by whom?

Only PORTAL submissions are gated: the feature is about customer catalogue requests
that auto-raise a ticket for the requester. An analyst filling a form internally has
no portal requester to raise one for, so it is never gated.

A form flagged `requires_approval` but with no approver is treated as UNCONFIGURED —
it must never strand a request with nobody able to clear it. Returns the approver id
to gate on, or null for "let it through as normal".

```php
function catalogueApprovalGate(array $form, ?int $portalUserId): ?int
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$form` | `array` |  | the forms row being submitted - only requires_approval and approver_id are read |
| `$portalUserId` | `?int` |  | users.id of the portal requester; null when an analyst filled the form internally |

**Returns:** `?int`

*Nothing calls this function by name.*

