# includes/services/notifications.php

> PHP - 588 lines

In-app notifications (discussion #55).

A bell in the header telling an analyst when something they care about
happened. It is a SUBSCRIBER to the existing workflow event bus rather than a
second instrumentation layer — WorkflowEngine::dispatch() already fires ~32
event types from 48 call sites, so almost everything worth telling somebody
about is already being announced.

─────────────────────────────────────────────────────────────────────────────
 THE POINT OF THIS FILE IS THE NOISE RULES.
─────────────────────────────────────────────────────────────────────────────
An analyst carrying forty tickets will see the bell fill up faster than they
can read it, and a bell nobody can leave switched on has failed regardless of
how correct it is. Four rules, all enforced here rather than at the call site:

  1. Never notify you about your own action. You changed the status; you do
     not need telling. Analysts make most of the changes on their own tickets,
     so this removes more noise than everything else combined.
  2. Bulk operations produce at most one notification, not one per record.
  3. Repeat events about the same object COALESCE into one row while unread.
  4. Only types that earn a bell are on by default, and each is switchable
     per analyst.

Anything added here later must go through `notify()` so it inherits all four.

TWO BELLS, ONE SERVICE (discussion #62)
----------------------------------------
The self-service portal has a bell too - a manager hears about a new ticket
from their team there. Its rows live in `portal_notifications` (keyed on
users.id), a sibling of `notifications` (keyed on analysts.id), and every
method here serves both: pass an analyst id as before, or
NotificationsService::portalUser(`$userId`) for a portal user. Coalescing,
reading and clearing are the same code either way; only the per-analyst type
preferences (rule 4) are analyst-only, because a portal user has none.

## Classes

- [NotificationsService](/code/classes/NotificationsService.md) (class, line 42)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 258 | `NotificationsService::preferences` | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/entity_links.php](/code/files/includes/entity_links.php.md)

**Included by:** [api/notifications/clear.php](/code/files/api/notifications/clear.php.md), [api/notifications/get_notifications.php](/code/files/api/notifications/get_notifications.php.md), [api/notifications/mark_read.php](/code/files/api/notifications/mark_read.php.md), [api/self-service/notifications.php](/code/files/api/self-service/notifications.php.md), [api/tickets/bulk_delete_tickets.php](/code/files/api/tickets/bulk_delete_tickets.php.md), [api/tickets/bulk_update_tickets.php](/code/files/api/tickets/bulk_update_tickets.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md)

**Calls:** [entityLink()](/code/functions/entityLink.md)

