# includes/ticket_merge.php

> PHP - 831 lines

Merging tickets.

WHAT A MERGE ACTUALLY IS
------------------------
Two people reported the same printer. Or a monitoring system opened forty tickets
for one outage. Merging folds those conversations together so there is one place to
answer from — without destroying the references the requesters already hold.

THE REFERENCE IS THE HARD PART
------------------------------
Every notification FreeITSM has ever sent carries `[SDREF:ABC-452-98881]` in its
subject, and check_mailbox_email.php routes inbound replies by matching it. So a
merged-away ticket can never simply cease to exist: somewhere out there are emails
in a customer's inbox whose Reply button still points at it.

That is why `tickets.merged_into_id` exists and why the source row is NEVER deleted.
The source stays, closed and flagged, and both the UI and the mail importer follow
the pointer. "Whatever happened to ABC?" is answerable forever, and a reply to a
two-year-old ABC notification still lands on the live ticket.

TWO POLICIES, BOTH THE ADMIN'S CHOICE
-------------------------------------
Tickets → Settings → Merge behaviour, install-wide (not per-analyst: whether a
requester's reference survives is a promise to the CUSTOMER, and two analysts on
the same mailbox must not be able to disagree about it).

  reference_mode  'survivor' — one existing ticket lives on and keeps its number.
                               Nothing the requester has seen becomes a dead ref.
                  'new'      — a brand-new ticket is minted and EVERY source folds
                               into it, including the one the analyst picked. All
                               the old refs become redirects.

  originals_mode  'thread'      messages move onto the target, tagged with where
                                they came from. Searchable, quotable, inline.
                  'thread_html' as above, PLUS a self-contained HTML snapshot of
                                each source attached to the target.
                  'html'        the messages stay on their original tickets and the
                                target gets only the snapshots. Tidiest target;
                                the trade-off is that inbox search no longer finds
                                the merged content, because search reads message
                                bodies and not attachments.

WHAT MOVES AND WHAT STAYS
-------------------------
Fourteen tables hang off a ticket, and the split is not arbitrary — the test is
"does this record describe the CONVERSATION, or does it describe what happened to
THIS TICKET?" The first kind moves; the second kind would be falsified by moving.

  MOVE   emails (attachments follow by email_id), ticket_notes, ticket_time_entries,
         ticket_recordings, ticket_cmdb_objects, ticket_assets, tasks,
         problem_tickets, change_tickets, form_submissions, webchat_conversations

  STAY   ticket_audit          the source's own history. Moving it would rewrite
                               the past and make the source look like it never had one.
         sla_notifications_sent what the SLA engine did to THAT ticket, at the time.
         ticket_csat_responses  a survey somebody answered ABOUT that ticket.
         mailbox_activity_log   an append-only log; logs are not editable records.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mergeSettings()](/code/functions/mergeSettings.md) | 91 | Read the install's merge policy. Defaults are the conservative pair: keep the requester's reference alive, and keep the conversation searchable. |
| [resolveMergedTicket()](/code/functions/resolveMergedTicket.md) | 125 | Follow the merge chain to the ticket that is actually live. |
| [unmergeTicket()](/code/functions/unmergeTicket.md) | 172 | Undo a merge: take back exactly what was moved, and reopen the source. |
| [mergedAwayInfo()](/code/functions/mergedAwayInfo.md) | 301 | Is this ticket a merged-away one? Returns the target row, or null. |
| [mergeTickets()](/code/functions/mergeTickets.md) | 336 | Merge one or more source tickets into a target. |
| [mergeCreateTargetTicket()](/code/functions/mergeCreateTargetTicket.md) | 445 | Create the brand-new ticket that everything folds into ('new' reference mode). |
| [mergeOneTicket()](/code/functions/mergeOneTicket.md) | 491 | Fold a single source into the target. Assumes the caller holds a transaction. |
| [mergeFirstClosedStatusId()](/code/functions/mergeFirstClosedStatusId.md) | 620 | The install's first closed status id, or null if none is flagged. |
| [mergeTicketNumber()](/code/functions/mergeTicketNumber.md) | 630 | A ticket's number for audit and note text; "#id" if the lookup fails. |
| [mergeAudit()](/code/functions/mergeAudit.md) | 650 | Writes one ticket_audit row for a merge step, best-effort - a failed audit write never stops the merge. An actor id of 0 is stored as NULL. |
| [mergeBuildSnapshotHtml()](/code/functions/mergeBuildSnapshotHtml.md) | 679 | Render a source ticket's whole conversation as a self-contained HTML document. |
| [mergeAttachSnapshot()](/code/functions/mergeAttachSnapshot.md) | 770 | Attach a snapshot to the target ticket. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 99 | `mergeSettings` | read [system_settings](/code/tables/system_settings.md) |  |
| 131 | `resolveMergedTicket` | read [tickets](/code/tables/tickets.md) |  |
| 175 | `unmergeTicket` | read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 198 | `unmergeTicket` | read [emails](/code/tables/emails.md) |  |
| 210 | `unmergeTicket` | **update** [emails](/code/tables/emails.md) (`ticket_id`) |  |
| 229 | `unmergeTicket` | read [email_attachments](/code/tables/email_attachments.md) |  |
| 235 | `unmergeTicket` | **delete** [email_attachments](/code/tables/email_attachments.md) |  |
| 236 | `unmergeTicket` | **delete** [emails](/code/tables/emails.md) |  |
| 243 | `unmergeTicket` | **update** [tickets](/code/tables/tickets.md) (`merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime`) |  |
| 257 | `unmergeTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 266 | `unmergeTicket` | read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 270 | `unmergeTicket` | read [emails](/code/tables/emails.md) |  |
| 277 | `unmergeTicket` | **update** [ticket_merges](/code/tables/ticket_merges.md) (`undone_datetime`, `undone_by_id`) |  |
| 307 | `mergedAwayInfo` | read [tickets](/code/tables/tickets.md), read [ticket_merges](/code/tables/ticket_merges.md) |  |
| 458 | `mergeCreateTargetTicket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_analyst_id`, `origin_id`, `user_id`, `owner_id`, `tenant_id`, `created_datetime`) |  |
| 504 | `mergeOneTicket` | read [emails](/code/tables/emails.md) |  |
| 508 | `mergeOneTicket` | **update** [emails](/code/tables/emails.md) (`ticket_id`) |  |
| 553 | `mergeOneTicket` | **update** [ticket_links](/code/tables/ticket_links.md) (`source_ticket_id`) |  |
| 554 | `mergeOneTicket` | **update** [ticket_links](/code/tables/ticket_links.md) (`target_ticket_id`) |  |
| 555 | `mergeOneTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 557 | `mergeOneTicket` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |
| 582 | `mergeOneTicket` | **update** [tickets](/code/tables/tickets.md) (`merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime`) |  |
| 594 | `mergeOneTicket` | **insert** [ticket_merges](/code/tables/ticket_merges.md) (`source_ticket_id`, `source_ticket_number`, `target_ticket_id`, `reference_mode`, `originals_mode`, `moved_email_ids`, `moved_related`, `snapshot_email_id`, `source_prev_status_id`, `source_prev_closed_datetime`, `merged_by_id`, `merged_datetime`) |  |
| 613 | `mergeOneTicket` | **insert** [ticket_links](/code/tables/ticket_links.md) (`source_ticket_id`, `target_ticket_id`, `relation_type`, `created_by_id`, `created_datetime`) |  |
| 622 | `mergeFirstClosedStatusId` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 632 | `mergeTicketNumber` | read [tickets](/code/tables/tickets.md) |  |
| 653 | `mergeAudit` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 686 | `mergeBuildSnapshotHtml` | read [emails](/code/tables/emails.md) |  |
| 696 | `mergeBuildSnapshotHtml` | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 774 | `mergeAttachSnapshot` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `received_datetime`, `body_content`, `body_type`, `has_attachments`, `is_read`, `ticket_id`, `direction`, `channel`, `is_initial`) |  |
| 825 | `mergeAttachSnapshot` | **insert** [email_attachments](/code/tables/email_attachments.md) (`email_id`, `filename`, `content_type`, `file_path`, `file_size`, `is_inline`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/html_sanitise.php](/code/files/includes/html_sanitise.php.md)

**Included by:** [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md), [api/tickets/get_merge_settings.php](/code/files/api/tickets/get_merge_settings.php.md), [api/tickets/merge_tickets.php](/code/files/api/tickets/merge_tickets.php.md), [api/tickets/undo_merge.php](/code/files/api/tickets/undo_merge.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [sanitiseUserHtml()](/code/functions/sanitiseUserHtml.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityInherit()](/code/functions/ticketSensitivityInherit.md), [uploadStoreBytes()](/code/functions/uploadStoreBytes.md)

## Traps

> [!WARNING]
> **Line 449, in `mergeCreateTargetTicket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

