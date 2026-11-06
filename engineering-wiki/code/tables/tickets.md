# tickets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NULL` |  |
| `ticket_number` | `VARCHAR(50) NOT NULL` |  |
| `subject` | `VARCHAR(500) NOT NULL` |  |
| `status_id` | `INT NULL` |  |
| `priority_id` | `INT NULL` |  |
| `department_id` | `INT NULL` |  |
| `ticket_type_id` | `INT NULL` |  |
| `category_id` | `INT NULL` | Ticket classification (#1540). Three different questions: category_id          what it was REPORTED as (set when raised) closure_category_id  what it TURNED OUT to be (set at close, from the SAME tree) resolution_code_id   HOW it ended All three are NULL on every existing ticket and NOTHING is backfilled — NULL means "never categorised", shown as "Not categorised", never guessed. 🔑 Each holds the LEAF category id ONLY; the parent is DERIVED. |
| `closure_category_id` | `INT NULL` |  |
| `resolution_code_id` | `INT NULL` |  |
| `assigned_team_id` | `INT NULL` | Which TEAM owns this ticket (#1566). NULL on every existing ticket and on every install that does not use teams — nothing is backfilled. 🔑 A different fact from assigned_analyst_id: team = which queue owns it, analyst = who is doing it, owner_id = whose calendar it appears in. Setting one never sets or clears the other; clearing the analyst leaves the team, so a ticket falls back to the queue rather than the void. 🔴 Routing, NOT permission — it must never change who can see a ticket. |
| `assigned_analyst_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `closed_datetime` | `DATETIME NULL` |  |
| `origin_id` | `INT NULL` |  |
| `first_time_fix` | `TINYINT(1) NULL` |  |
| `it_training_provided` | `TINYINT(1) NULL` |  |
| `user_id` | `INT NULL` |  |
| `owner_id` | `INT NULL` |  |
| `work_start_datetime` | `DATETIME NULL` |  |
| `work_end_datetime` | `DATETIME NULL` | NULL end = "scheduled before this column existed"; readers apply the one-hour default. Nothing is backfilled, so an upgrade is a no-op. |
| `work_all_day` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `deleted_datetime` | `DATETIME NULL` |  |
| `deleted_by` | `INT NULL` |  |
| `last_inbound_at` | `DATETIME NULL` | Messaging channels: when the customer last messaged in (drives the 24h provider service window on the reply box). NULL for non-channel tickets. |
| `merged_into_id` | `INT NULL` | Set when this ticket has been merged AWAY into another one. NULL = a live ticket, which is every ticket until somebody merges it.  The banner, the search redirect and the inbound-email redirect all key off THIS COLUMN and never off a status name. Statuses are user-configurable (an install may rename or add its own), so "merged" as a status string would be a rule that quietly stops working the day somebody edits a list in settings — the same trap the reopen-on-reply rule avoids by reading ticket_statuses.is_closed rather than hardcoding "Closed". |
| `snoozed_until` | `DATETIME NULL` | Snooze (#933): the ticket is out of the working queue until this instant. "Asleep" is `snoozed_until > `UTC_TIMESTAMP()`` and nothing else — no cron clears it, so a ticket returns on time even on an install with the schedulers switched off. A past value simply means it has woken. |
| `snoozed_at` | `DATETIME NULL` |  |
| `snoozed_by` | `INT NULL` |  |
| `snooze_reason` | `VARCHAR(255) NULL` |  |
| `sensitivity` | `VARCHAR(20) NOT NULL DEFAULT 'normal'` | 'normal' or 'confidential' (discussion #62). Confidential keeps a ticket from the requester's managers; see includes/ticket_sensitivity.php. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md):166 |  | update | `updated_datetime` |  |
| [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md):119 |  | update | `updated_datetime` |  |
| [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md):96 |  | update | `updated_datetime` |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md):226 | `simCleanup` | delete |  |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md):135 |  | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `category_id`, `user_id`, `tenant_id`, `created_datetime`, `updated_datetime` |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md):232 |  | update | `updated_datetime` |  |
| [api/system/assign_ticket_department.php](/code/files/api/system/assign_ticket_department.php.md):58 |  | update | `department_id`, `updated_datetime` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):1714 |  | update | `tenant_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2192 |  | update | `status_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2201 |  | update | `status_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2212 |  | update | `priority_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2220 |  | update | `priority_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2231 |  | update | `status_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2240 |  | update | `priority_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2273 |  | update | `user_id` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2289 |  | update | `user_id` |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md):380 |  | delete |  |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1139 | `saveEmailToDatabase` | update | `updated_datetime` |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md):1194 | `saveEmailToDatabase` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `user_id`, `tenant_id`, `origin_id` |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md):78 |  | delete |  |  |
| [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md):62 |  | update | `tenant_id`, `updated_datetime` |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md):104 |  | delete |  |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):125 |  | update | `tenant_id` |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):140 |  | update | `tenant_id` |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):146 |  | update | `tenant_id` |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):160 |  | update | `tenant_id` |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):164 |  | update | `tenant_id` |  |
| [api/tickets/save_ticket_subject.php](/code/files/api/tickets/save_ticket_subject.php.md):54 |  | update | `subject` |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md):844 | `saveSentEmail` | update | `updated_datetime` |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):220 | `calendarPullApply` | update | `work_start_datetime`, `work_end_datetime`, `work_all_day` |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md):240 | `calendarPullUnschedule` | update | `work_start_datetime`, `work_end_datetime`, `work_all_day` |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md):306 | `catalogueCreateTicketFromSubmission` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_team_id`, `assigned_analyst_id`, `user_id`, `tenant_id` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1381 | `integrationsApplyCommentEvent` | update | `updated_datetime` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):161 | `ingestInboundMessage` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `last_inbound_at`, `user_id`, `tenant_id`, `origin_id` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):180 | `ingestInboundMessage` | update | `updated_datetime`, `last_inbound_at` |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md):717 | `messagingTelegramLinkPhone` | update | `user_id` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):237 | `TicketsService::createTicket` | insert | `tenant_id`, `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `category_id`, `origin_id`, `assigned_team_id` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):648 | `TicketsService::updateTicket` | update |  |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):820 | `TicketsService::deleteTicket` | update | `deleted_datetime`, `deleted_by` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):842 | `TicketsService::restoreTicket` | update | `deleted_datetime`, `deleted_by` |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md):907 | `TicketsService::createNote` | update | `updated_datetime` |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md):767 | `templateSaveSentEmail` | update | `updated_datetime` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):243 | `unmergeTicket` | update | `merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):458 | `mergeCreateTargetTicket` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_analyst_id`, `origin_id`, `user_id`, `owner_id` |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md):582 | `mergeOneTicket` | update | `merged_into_id`, `status_id`, `closed_datetime`, `updated_datetime` |  |
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md):814 | `TicketNumbering::applyRenumber` | update | `ticket_number` |  |
| [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md):104 | `reopenTicketForCustomerReply` | update | `status_id`, `closed_datetime`, `updated_datetime` |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md):88 | `ticketSensitivityRaise` | update | `sensitivity` |  |
| [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md):228 | `snoozeTicket` | update | `snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason` |  |
| [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md):252 | `wakeTicket` | update | `snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason` |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md):287 | `splitCreateTicket` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_analyst_id`, `origin_id`, `user_id`, `owner_id` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):231 | `webchatOpenTicket` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `created_datetime`, `updated_datetime`, `last_inbound_at`, `user_id`, `tenant_id`, `origin_id` |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md):363 | `webchatIngestMessage` | update | `updated_datetime`, `last_inbound_at` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2313 | `WorkflowEngine::action_set_ticket_status` | update |  |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2342 | `WorkflowEngine::action_set_ticket_priority` | update | `priority_id`, `updated_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2367 | `WorkflowEngine::action_assign_ticket` | update | `assigned_analyst_id`, `owner_id`, `updated_datetime` |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md):2750 | `WorkflowEngine::action_create_ticket` | insert | `ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_team_id`, `assigned_analyst_id`, `user_id`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md) | L50, L75 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L91 |  |
| [api/change-management/link_ticket.php](/code/files/api/change-management/link_ticket.php.md) | L26 |  |
| [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md) | L49 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L183, L196 |  |
| [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md) | L45, L71 |  |
| [api/forms/get_submissions.php](/code/files/api/forms/get_submissions.php.md) | L75 |  |
| [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md) | L83 |  |
| [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md) | L78 |  |
| [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md) | L63 |  |
| [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md) | L133 |  |
| [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md) | L100 |  |
| [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md) | L89 |  |
| [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md) | testSimulation L203, simCleanup L236 |  |
| [api/problem-management/ai_root_cause.php](/code/files/api/problem-management/ai_root_cause.php.md) | L35 |  |
| [api/problem-management/ai_suggest_problem.php](/code/files/api/problem-management/ai_suggest_problem.php.md) | L28 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L37 |  |
| [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md) | L32 |  |
| [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md) | L49 |  |
| [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md) | L71 |  |
| [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md) | L42 |  |
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L40, L80, L131 |  |
| [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md) | L64 |  |
| [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md) | L65, L81, L95, L101 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L48 |  |
| [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md) | L30 |  |
| [api/self-service/mark_confidential.php](/code/files/api/self-service/mark_confidential.php.md) | L45 |  |
| [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md) | L74 |  |
| [api/system/assign_ticket_department.php](/code/files/api/system/assign_ticket_department.php.md) | L53 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L2186, L2206, L2279, L2321, L2322, L2323 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L141, L146, L418 |  |
| [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) | L334, L338, L981, L993 |  |
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L250, L252, L254, L256, L259, L265 ... |  |
| [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md) | L27 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L68 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L70, L567 |  |
| [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md) | generateDemoTicketNumber L214 |  |
| [api/system/search_status.php](/code/files/api/system/search_status.php.md) | L68, L102 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L39 |  |
| [api/tasks/search_links.php](/code/files/api/tasks/search_links.php.md) | L44 |  |
| [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md) | L123 |  |
| [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md) | L54 |  |
| [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) | enrolBackfillTickets L59 |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | saveEmailToDatabase L1270 |  |
| [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md) | L106 |  |
| [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md) | L77 |  |
| [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md) | L53, L65 |  |
| [api/tickets/delete_ticket_priority.php](/code/files/api/tickets/delete_ticket_priority.php.md) | L35 |  |
| [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md) | L51 |  |
| [api/tickets/delete_ticket_status.php](/code/files/api/tickets/delete_ticket_status.php.md) | L37 |  |
| [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md) | L61 |  |
| [api/tickets/delete_user.php](/code/files/api/tickets/delete_user.php.md) | L56 |  |
| [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md) | L36 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L113, L118 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_inbox_activity.php](/code/files/api/tickets/get_inbox_activity.php.md) | L41 |  |
| [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md) | L45 |  |
| [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md) | L64, L67, L73 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L87, L96, L110, L119, L135, L150 ... |  |
| [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md) | L143 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L305, getCategoricalData L307, getCategoricalData L309, getCategoricalData L311, getCategoricalData L334, getCategoricalData L343 ... |  |
| [api/tickets/get_triage.php](/code/files/api/tickets/get_triage.php.md) | L38 |  |
| [api/tickets/get_user_tickets.php](/code/files/api/tickets/get_user_tickets.php.md) | L31 |  |
| [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md) | L59 |  |
| [api/tickets/move_ticket_to_company.php](/code/files/api/tickets/move_ticket_to_company.php.md) | L49 |  |
| [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md) | L45 |  |
| [api/tickets/save_department.php](/code/files/api/tickets/save_department.php.md) | L65 |  |
| [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md) | L43, L60 |  |
| [api/tickets/save_ticket_cmdb_object.php](/code/files/api/tickets/save_ticket_cmdb_object.php.md) | L36, L56 |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md) | L42 |  |
| [api/tickets/save_ticket_subject.php](/code/files/api/tickets/save_ticket_subject.php.md) | L43 |  |
| [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md) | L89 |  |
| [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md) | L83 |  |
| [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md) | L85 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L64 |  |
| [api/tickets/search_linkable_tasks.php](/code/files/api/tickets/search_linkable_tasks.php.md) | L50 |  |
| [api/tickets/search_tickets.php](/code/files/api/tickets/search_tickets.php.md) | L67 |  |
| [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) | buildFullEmailBody L699 |  |
| [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md) | L53 |  |
| [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md) | L65 |  |
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) | L150 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbObjectTicketsList L518 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemsGet L218 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTasksGet L338 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38, apiTicketsList L228 |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) | apiUsersGet L135 |  |
| [api/webchat/poll.php](/code/files/api/webchat/poll.php.md) | L75 |  |
| [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md) | L87 |  |
| [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md) | oversizeNotify L273 |  |
| [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) | calendarSyncPullForAnalyst L117, calendarPullUnschedule L238 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTicket L51 |  |
| [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) | catalogueApprovalsList L568 |  |
| [includes/documents.php](/code/files/includes/documents.php.md) | documentEntityRegistry L109, documentEntityRegistry L114, documentEntityRegistry L128, documentEntityRegistry L136 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkTargetOk L120, domainLinkDescribe L173, domainLinkSearch L279 |  |
| [includes/feature_bingo/cards/tickets-channels.php](/code/files/includes/feature_bingo/cards/tickets-channels.php.md) | L842 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsTicketPayload L869, integrationsEntityRouting L1149 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapWindowSql L58 |  |
| [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) | writeupTicketBundle L238, writeupEmbeddingText L372 |  |
| [includes/managers.php](/code/files/includes/managers.php.md) | portalTicketAccess L328, managersNotifyNewTicket L393 |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | findOpenChannelTicket L308, messagingTelegramLinkPhone L709, messagingTelegramLinkPhone L724 |  |
| [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) | notificationsEntityFor L372 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCompanyCards L171, peopleTickets L334, peopleTickets L343, peopleForms L594 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L231 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkTargetOk L144, projectLinkDescribe L207, projectLinkSearch L354 |  |
| [includes/projects/read.php](/code/files/includes/projects/read.php.md) | projectTaskStats L54 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L446 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTicket L141 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | rpTicketsBreakdown L75, rpTicketsTrend L198, rpTicketsKpis L220, rpTicketsKpis L229, rpTicketsList L257 |  |
| [includes/search/backfill.php](/code/files/includes/search/backfill.php.md) | searchBackfillRun L66, searchBackfillRun L70, searchBackfillRun L105, searchBackfillPrune L160 |  |
| [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) | searchIndexTicket L60 |  |
| [includes/search/search.php](/code/files/includes/search/search.php.md) | searchCorpusQuery L279 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::linkTicket L652, ChangesService::unlinkTicket L692 |  |
| [includes/services/problems.php](/code/files/includes/services/problems.php.md) | ProblemsService::linkTicket L297 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::issueToTicket L643, ProjectToolsService::raid L825 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::resolveNewTaskTenant L1234, TasksService::ticketAccessible L1409 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::updateTicket L740, TicketsService::loadTicket L1100, TicketsService::canAccessTicket L1138 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_get_state L49 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_run_breach_check L38, sla_emit_workflow_event L157, sla_find_matching_rule L214, sla_resolve_recipients L245, sla_resolve_recipients L257 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | buildTicketMergeData L350 |  |
| [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) | renderTenantSwitcher L54 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessTicket L279 |  |
| [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) | ticketTenantId L285 |  |
| [includes/ticket_events.php](/code/files/includes/ticket_events.php.md) | ticketDispatchCreated L55 |  |
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) | ticketLinkLoad L26, ticketLinkNum L39, ticketLinksFor L214, ticketLinkableList L276 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | resolveMergedTicket L131, mergedAwayInfo L307, mergeTicketNumber L632 |  |
| [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md) | TicketNumbering::inUse L494, TicketNumbering::findTicketId L519, TicketNumbering::planRenumber L675 |  |
| [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md) | reopenTicketForCustomerReply L77 |  |
| [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) | ticketSensitivityReady L52, ticketSensitivityApplyDefaults L118, ticketSensitivityInherit L153, ticketIsConfidential L173, ticketEmailRecipientsAllowed L300 |  |
| [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md) | snoozeSchemaReady L60, scheduleSchemaReady L83, wakeTicket L246, snoozeStateFor L291 |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) | splitTicket L203 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolOpenIncidents L410, warbotToolOpenIncidents L422, warbotToolTicketSpike L706, warbotToolTicketSpike L719, warbotToolTicketSpike L742, warbotToolRelatedTickets L922 ... |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L169, getWatchtowerData L201, getWatchtowerData L229, getWatchtowerData L249 |  |
| [tickets/csat/index.php](/code/files/tickets/csat/index.php.md) | L100, L121, L135, L155, L166, L184 ... |  |
| [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md) | L32 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_set_ticket_status L2282 |  |

