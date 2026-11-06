# workflow/includes/engine.php

> PHP - 3205 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Workflows](https://github.com/edmozley/freeitsm/wiki/Workflows)

Workflow Engine ----------------------------------------------------------------------

Cross-module automation. Other modules CALL into this engine when their
events happen (e.g. tickets/save.php calls WorkflowEngine::dispatch(
'ticket.created', ['ticket' => `$row`]) after a successful insert). The
engine finds active workflows matching the event, evaluates conditions
against the payload, executes actions in order, and writes a row to
workflow_executions for every run so the user can see what happened.

The contract is deliberately small in v1:

  - Triggers are flat event-name strings. The catalogue lives in
    `availableTriggers()`; new ones are added here and wired in their
    module's save flow.

  - Conditions are an array of {field, op, value} objects. The engine
    reads `field` from the payload via dot-notation (`ticket.priority`).
    All conditions must match (AND). If empty, the workflow always fires.

  - Actions are an array of {type, args} objects executed in order.
    The catalogue lives in `availableActions()`; the handlers live as
    private methods on this class so each action's contract is in one
    place.

  - Execution is synchronous in v1. Each event triggers a same-request
    pass through the engine. An async queue + retries are planned but
    not built — keep actions fast and idempotent.

Failures don't propagate. The engine catches per-step exceptions, logs
them in the execution row, and continues. The point is that a buggy
workflow can't take down the host module's request.

## Classes

- [WorkflowEngine](/code/classes/WorkflowEngine.md) (class, line 38) - Workflow Engine ----------------------------------------------------------------------

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 581 | `WorkflowEngine::webhookFormats` | read [webhook_message_formats](/code/tables/webhook_message_formats.md) |  |
| 1023 |  | read [forms](/code/tables/forms.md) |  |
| 1506 | `WorkflowEngine::dispatch` | read [workflows](/code/tables/workflows.md) |  |
| 1534 | `WorkflowEngine::manualFire` | read [workflows](/code/tables/workflows.md) |  |
| 1611 | `WorkflowEngine::recordAbortedRun` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`, `finished_datetime`, `step_log`, `error_message`) |  |
| 1623 | `WorkflowEngine::recordAbortedRun` | **update** [workflows](/code/tables/workflows.md) (`last_run_datetime`, `last_run_status`) |  |
| 1685 | `WorkflowEngine::runInner` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`) |  |
| 1719 | `WorkflowEngine::runInner` | **update** [workflow_executions](/code/tables/workflow_executions.md) (`status`, `finished_datetime`, `step_log`, `error_message`) |  |
| 1729 | `WorkflowEngine::runInner` | **update** [workflows](/code/tables/workflows.md) (`last_run_datetime`, `last_run_status`, `run_count`) |  |
| 2037 | `WorkflowEngine::runActionList` | **insert** [workflow_executions](/code/tables/workflow_executions.md) (`workflow_id`, `workflow_name`, `trigger_event`, `trigger_payload`, `status`, `is_dry_run`, `started_datetime`) |  |
| 2048 | `WorkflowEngine::runActionList` | **update** [workflow_executions](/code/tables/workflow_executions.md) (`status`, `finished_datetime`, `step_log`, `error_message`) |  |
| 2194 | `WorkflowEngine::action_attach_checklist` | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |
| 2201 | `WorkflowEngine::action_attach_checklist` | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 2208 | `WorkflowEngine::action_attach_checklist` | **insert** [ticket_checklists](/code/tables/ticket_checklists.md) (`ticket_id`, `template_id`, `title`, `closure_mode`, `created_by_id`, `created_datetime`) |  |
| 2213 | `WorkflowEngine::action_attach_checklist` | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 2217 | `WorkflowEngine::action_attach_checklist` | **insert** [ticket_checklist_items](/code/tables/ticket_checklist_items.md) (`ticket_checklist_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`) |  |
| 2271 | `WorkflowEngine::action_set_ticket_status` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2282 | `WorkflowEngine::action_set_ticket_status` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2313 | `WorkflowEngine::action_set_ticket_status` | **update** [tickets](/code/tables/tickets.md) |  |
| 2338 | `WorkflowEngine::action_set_ticket_priority` | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 2342 | `WorkflowEngine::action_set_ticket_priority` | **update** [tickets](/code/tables/tickets.md) (`priority_id`, `updated_datetime`) |  |
| 2360 | `WorkflowEngine::action_assign_ticket` | read [analysts](/code/tables/analysts.md) |  |
| 2367 | `WorkflowEngine::action_assign_ticket` | **update** [tickets](/code/tables/tickets.md) (`assigned_analyst_id`, `owner_id`, `updated_datetime`) |  |
| 2392 | `WorkflowEngine::action_add_ticket_note` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 2469 | `WorkflowEngine::action_send_email` | read [target_mailboxes](/code/tables/target_mailboxes.md) |  |
| 2600 | `WorkflowEngine::action_create_task` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 2601 | `WorkflowEngine::action_create_task` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 2605 | `WorkflowEngine::action_create_task` | read [task_priorities](/code/tables/task_priorities.md) |  |
| 2612 | `WorkflowEngine::action_create_task` | read [tasks](/code/tables/tasks.md) |  |
| 2617 | `WorkflowEngine::action_create_task` | **insert** [tasks](/code/tables/tasks.md) (`title`, `description`, `status_id`, `priority_id`, `assigned_analyst_id`, `ticket_id`, `board_position`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 2726 | `WorkflowEngine::action_create_ticket` | read [users](/code/tables/users.md) |  |
| 2730 | `WorkflowEngine::action_create_ticket` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 2740 | `WorkflowEngine::action_create_ticket` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 2750 | `WorkflowEngine::action_create_ticket` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_team_id`, `assigned_analyst_id`, `user_id`, `created_datetime`, `updated_datetime`) |  |
| 2772 | `WorkflowEngine::action_create_ticket` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`, `ticket_id`) |  |
| 2786 | `WorkflowEngine::action_create_ticket` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/notifications_router.php](/code/files/includes/notifications_router.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), [includes/services/checklists.php](/code/files/includes/services/checklists.php.md), `workflow/includes/template_email.php`, `workflow/includes/email_log.php`, [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), `workflow/includes/mailbox_imap.php`, `workflow/includes/gmail.php`, [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Included by:** [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/system/db_verify.php](/code/files/api/system/db_verify.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/v1/resources/workflows.php](/code/files/api/v1/resources/workflows.php.md), [api/workflow/ai_compose.php](/code/files/api/workflow/ai_compose.php.md), [api/workflow/fire.php](/code/files/api/workflow/fire.php.md), [api/workflow/formats.php](/code/files/api/workflow/formats.php.md), [api/workflow/save.php](/code/files/api/workflow/save.php.md), [api/workflow/save_format.php](/code/files/api/workflow/save_format.php.md), [api/workflow/webhook_test.php](/code/files/api/workflow/webhook_test.php.md), [forms/edit/index.php](/code/files/forms/edit/index.php.md), [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/intune.php](/code/files/includes/intune.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/services/calendar.php](/code/files/includes/services/calendar.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md), [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md), [includes/services/contracts.php](/code/files/includes/services/contracts.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md), [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md), [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/services/software.php](/code/files/includes/services/software.php.md), [includes/services/tasks.php](/code/files/includes/services/tasks.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), [workflow/editor.php](/code/files/workflow/editor.php.md), [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md)

**Calls:** [apiSerializeArticle()](/code/functions/apiSerializeArticle.md), [apiSerializeAsset()](/code/functions/apiSerializeAsset.md), [apiSerializeCalendarEvent()](/code/functions/apiSerializeCalendarEvent.md), [apiSerializeChange()](/code/functions/apiSerializeChange.md), [apiSerializeContract()](/code/functions/apiSerializeContract.md), [apiSerializeIncident()](/code/functions/apiSerializeIncident.md), [apiSerializeLicence()](/code/functions/apiSerializeLicence.md), [apiSerializeProblem()](/code/functions/apiSerializeProblem.md), [apiSerializeSupplier()](/code/functions/apiSerializeSupplier.md), [apiSerializeTask()](/code/functions/apiSerializeTask.md), [apiSerializeTicket()](/code/functions/apiSerializeTicket.md), [buildTemplateEmailBody()](/code/functions/buildTemplateEmailBody.md), [buildTicketMergeData()](/code/functions/buildTicketMergeData.md), [catalogueRaiseTicketFromSubmissionId()](/code/functions/catalogueRaiseTicketFromSubmissionId.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [integrationsAbsoluteUrl()](/code/functions/integrationsAbsoluteUrl.md), [integrationsEscalate()](/code/functions/integrationsEscalate.md), [integrationsLinksFor()](/code/functions/integrationsLinksFor.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsProviderFor()](/code/functions/integrationsProviderFor.md), [integrationsRecordOutboundComment()](/code/functions/integrationsRecordOutboundComment.md), [notificationsHandleEvent()](/code/functions/notificationsHandleEvent.md), [projectCreateFromAction()](/code/functions/projectCreateFromAction.md), [searchIndexHandleEvent()](/code/functions/searchIndexHandleEvent.md), [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSaveSentEmail()](/code/functions/templateSaveSentEmail.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md), [ticketEmailRecipientsAllowed()](/code/functions/ticketEmailRecipientsAllowed.md), [ticketIsConfidential()](/code/functions/ticketIsConfidential.md), [ticketRedactForOutbound()](/code/functions/ticketRedactForOutbound.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [webhookDecrypt()](/code/functions/webhookDecrypt.md), [webhookEnqueue()](/code/functions/webhookEnqueue.md)

## Traps

> [!WARNING]
> **Line 2743, in `WorkflowEngine::action_create_ticket`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was. No company is stored on this ticket, which means the default one.

