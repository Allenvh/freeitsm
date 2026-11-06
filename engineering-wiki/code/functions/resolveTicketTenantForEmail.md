# resolveTicketTenantForEmail()

Decide which company (tenant) a NEW inbound-email ticket belongs to.

This implements the design's inbound routing for the "no existing ticket"
case only — a reply to an existing ticket inherits that ticket's tenant
upstream (the email is attached to the found ticket) and never reaches here:

  1. Single-company install (!isMultiTenant) → always the Default company.
  2. Pinned mailbox (target_mailboxes.tenant_id set) → that company; the
     sender is ignored. (A pinned mailbox is also the outbound reply identity.)
  3. Shared-intake mailbox (tenant_id NULL) → most-specific-match wins:
       (a) the exact sender address is on a company's "specific senders" list
           (tenant_sender_addresses) → that company. This is how a personal /
           freemail address (jane@gmail.com) reaches the right company even
           though its domain can never be mapped (two clients share gmail.com).
       (b) otherwise match the sender's domain against the companies'
           registered domains (tenant_domains) → that company.
       - no match (including freemail not on any list) → NULL, meaning
         "triage": the ticket is left un-companied and, because
         `ticketTenantFilter()` treats NULL as Default-owned, surfaces under
         the Default company until an analyst files it. Nothing is ever lost.

Deliberately defensive (try/catch around the mailbox/domain lookups) so it is
safe to call on a part-migrated install.

```php
function resolveTicketTenantForEmail(PDO $conn, $mailboxId, string $fromAddress): ?int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$mailboxId` |  |  | the importing mailbox's id |
| `$fromAddress` | `string` |  | the sender's email address |

**Returns:** int|null a tenant id, or NULL meaning "send to triage".

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1117 | `resolveTicketTenantForEmail` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 1134 | `resolveTicketTenantForEmail` | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 1152 | `resolveTicketTenantForEmail` | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)

