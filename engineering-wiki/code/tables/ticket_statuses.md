# ticket_statuses

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `is_closed` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `is_default` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `pauses_sla` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):780 |  | insert | `name`, `is_closed`, `colour`, `is_default`, `display_order` |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):2186 |  | insert | `name`, `display_order` |  |
| [api/tickets/delete_ticket_status.php](/code/files/api/tickets/delete_ticket_status.php.md):46 |  | delete |  |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md):54 |  | update | `is_default` |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md):60 |  | update | `name`, `colour`, `is_closed`, `pauses_sla`, `is_default`, `display_order`, `is_active` |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md):66 |  | insert | `name`, `colour`, `is_closed`, `pauses_sla`, `is_default`, `display_order`, `is_active` |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md):75 |  | update | `is_default` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md) | L50, L75 |  |
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L91 |  |
| [api/change-management/list_linkable_tickets.php](/code/files/api/change-management/list_linkable_tickets.php.md) | L49 |  |
| [api/cmdb/generate_object_summary.php](/code/files/api/cmdb/generate_object_summary.php.md) | L183, L196 |  |
| [api/cmdb/get_object_tickets.php](/code/files/api/cmdb/get_object_tickets.php.md) | L45, L71 |  |
| [api/problem-management/get.php](/code/files/api/problem-management/get.php.md) | L37 |  |
| [api/problem-management/list_linkable_tickets.php](/code/files/api/problem-management/list_linkable_tickets.php.md) | L49 |  |
| [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md) | L71, L91 |  |
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md) | L135 |  |
| [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md) | L28, L40, L80 |  |
| [api/self-service/get_team_tickets.php](/code/files/api/self-service/get_team_tickets.php.md) | L81, L95, L101 |  |
| [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md) | L48 |  |
| [api/self-service/get_tickets.php](/code/files/api/self-service/get_tickets.php.md) | L30 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L778, L2186, L2192, L2201, L2231 |  |
| [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md) | L446, L452, L454 |  |
| [api/system/get_orphaned_tickets.php](/code/files/api/system/get_orphaned_tickets.php.md) | L27 |  |
| [api/tickets/calendar_enrolment.php](/code/files/api/tickets/calendar_enrolment.php.md) | enrolBackfillTickets L59 |  |
| [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) | saveEmailToDatabase L1194 |  |
| [api/tickets/delete_ticket_status.php](/code/files/api/tickets/delete_ticket_status.php.md) | L31, L45 |  |
| [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md) | L49 |  |
| [api/tickets/get_emails.php](/code/files/api/tickets/get_emails.php.md) | L56 |  |
| [api/tickets/get_scheduled_tickets.php](/code/files/api/tickets/get_scheduled_tickets.php.md) | L45 |  |
| [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md) | L96, L135, L150, L189, L204, L262 ... |  |
| [api/tickets/get_ticket_statuses.php](/code/files/api/tickets/get_ticket_statuses.php.md) | L20 |  |
| [api/tickets/get_ticket_widget_data.php](/code/files/api/tickets/get_ticket_widget_data.php.md) | getCategoricalData L303, getCategoricalWithSeries L381, getTimeData L459, getTimeWithSeries L499, getCreatedVsClosedData L581 |  |
| [api/tickets/get_user_tickets.php](/code/files/api/tickets/get_user_tickets.php.md) | L31 |  |
| [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md) | L73 |  |
| [api/tickets/schedule_feed.php](/code/files/api/tickets/schedule_feed.php.md) | L83 |  |
| [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md) | L85 |  |
| [api/tickets/search_tickets.php](/code/files/api/tickets/search_tickets.php.md) | L67, L89 |  |
| [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) | apiCmdbObjectTicketsList L518 |  |
| [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) | apiProblemsGet L218 |  |
| [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) | apiStatusesList L55 |  |
| [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) | apiTicketSelect L38, apiTicketsList L228 |  |
| [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) | apiUsersGet L135 |  |
| [api/webchat/poll.php](/code/files/api/webchat/poll.php.md) | L75 |  |
| [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) | analystReferenceRegistry L57 |  |
| [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md) | calendarSyncReconcileTicket L51 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkDescribe L173 |  |
| [includes/inbox_view_filter.php](/code/files/includes/inbox_view_filter.php.md) | inboxViewFilterSql L53 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapWindowSql L58 |  |
| [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) | ingestInboundMessage L161 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCompanyCards L171, peopleTickets L334, peopleTickets L343 |  |
| [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) | projectCapacity L231 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L207 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewTicket L141 |  |
| [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) | L35 |  |
| [includes/search/search.php](/code/files/includes/search/search.php.md) | searchCorpusQuery L286 |  |
| [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | TicketsService::createTicket L118, TicketsService::loadTicket L1100, TicketsService::resolveStatus L1186, TicketsService::resolveStatus L1189 |  |
| [includes/sla.php](/code/files/includes/sla.php.md) | sla_get_state L49, sla_get_state L115, sla_get_state L127 |  |
| [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) | sla_run_breach_check L38 |  |
| [includes/template_email.php](/code/files/includes/template_email.php.md) | buildTicketMergeData L350 |  |
| [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) | ticketLinksFor L214, ticketLinkableList L276 |  |
| [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) | mergeFirstClosedStatusId L622 |  |
| [includes/ticket_reply.php](/code/files/includes/ticket_reply.php.md) | reopenTicketForCustomerReply L77, reopenTicketForCustomerReply L92 |  |
| [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) | splitCreateTicket L282 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolOpenIncidents L410, warbotToolOpenIncidents L422, warbotToolRelatedTickets L930 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L169, getWatchtowerData L201, getWatchtowerData L229, getWatchtowerData L249 |  |
| [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) | webchatOpenTicket L231 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_set_ticket_status L2271, WorkflowEngine::action_set_ticket_status L2282, WorkflowEngine::action_create_ticket L2740 |  |

