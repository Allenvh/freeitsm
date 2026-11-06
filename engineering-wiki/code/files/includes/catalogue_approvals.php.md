# includes/catalogue_approvals.php

> PHP - 644 lines

Catalogue-request approvals (#928).

A self-service catalogue item is a form (`is_portal_visible = 1`). When that form is
marked `requires_approval` and has an `approver_id`, a portal submission does NOT go
straight to a ticket — it waits, `approval_status = 'pending'`, for the designated
approver to sign it off. Approve raises the ticket (the same way the portal's own
new-ticket path does, so tenancy and requester identity are correct) and stamps
`form_submissions.ticket_id`; reject records the decision and raises nothing.

WHY A DESIGNATED ANALYST, NOT THE REQUESTER'S MANAGER
----------------------------------------------------
The classic business version routes to the requester's line manager, but portal
users have no manager relationship in this app yet (only a company). So v1 reuses the
Change Management "single approver" idea: one analyst signs a catalogue item off. A
manager relationship — and manager-based routing — is a later slice.

WHY THE APPROVER IS SNAPSHOTTED
-------------------------------
`form_submissions.approver_id` is copied from the form AT SUBMIT TIME. Editing the
catalogue item later re-routes FUTURE requests, never ones already waiting — the
person who was told "your manager will approve this" stays the one who can.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [catalogueApprovalGate()](/code/functions/catalogueApprovalGate.md) | 54 | Should a submission of this form be gated behind approval, and by whom? |
| [catalogueApprovalDecide()](/code/functions/catalogueApprovalDecide.md) | 70 | Approve or reject a pending catalogue request. |
| [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md) | 222 | Raise the ticket an approved request becomes. |
| [catalogueOverrideOrDefault()](/code/functions/catalogueOverrideOrDefault.md) | 353 | An overridden lookup id, or the install's default for that table. |
| [catalogueRaiseTicketFromSubmissionId()](/code/functions/catalogueRaiseTicketFromSubmissionId.md) | 371 | Raise a ticket from a submission id, loading the row the way the approval path does. The entry point for callers that hold an id rather than a row — the workflow engine's create_ticket action, and (next) a form's own action list. |
| [catalogueAnswerText()](/code/functions/catalogueAnswerText.md) | 399 | One stored answer as the words a person would use. |
| [catalogueFormActionLists()](/code/functions/catalogueFormActionLists.md) | 464 | A form's three action lists, loaded lazily. |
| [catalogueSubmissionAnswerMap()](/code/functions/catalogueSubmissionAnswerMap.md) | 500 | A submission's answers as a label-keyed map, plus the first email answer. |
| [catalogueSubmissionBodyHtml()](/code/functions/catalogueSubmissionBodyHtml.md) | 529 | The submitted answers as a safe (fully-escaped) HTML summary for the ticket body. |
| [catalogueApprovalsList()](/code/functions/catalogueApprovalsList.md) | 566 | The approver's inbox list. |
| [catalogueSubmissionAnswers()](/code/functions/catalogueSubmissionAnswers.md) | 617 | label + value pairs for one submission (checkboxes flattened). |
| [catalogueScalar()](/code/functions/catalogueScalar.md) | 639 | Runs a prepared query and returns its first column of the first row (false when there is no row). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `catalogueApprovalDecide` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 122 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment`, `ticket_id`) |  |
| 129 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment`) |  |
| 194 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`ticket_id`) |  |
| 236 | `catalogueCreateTicketFromSubmission` | read [users](/code/tables/users.md) |  |
| 240 | `catalogueCreateTicketFromSubmission` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `created_at`) |  |
| 247 | `catalogueCreateTicketFromSubmission` | read [users](/code/tables/users.md) |  |
| 306 | `catalogueCreateTicketFromSubmission` | **insert** [tickets](/code/tables/tickets.md) (`ticket_number`, `subject`, `status_id`, `priority_id`, `department_id`, `ticket_type_id`, `assigned_team_id`, `assigned_analyst_id`, `user_id`, `tenant_id`, `created_datetime`, `updated_datetime`) |  |
| 323 | `catalogueCreateTicketFromSubmission` | **insert** [emails](/code/tables/emails.md) (`subject`, `from_address`, `from_name`, `to_recipients`, `received_datetime`, `body_preview`, `body_content`, `body_type`, `has_attachments`, `importance`, `is_read`, `ticket_id`) |  |
| 333 | `catalogueCreateTicketFromSubmission` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |
| 373 | `catalogueRaiseTicketFromSubmissionId` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 502 | `catalogueSubmissionAnswerMap` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |
| 531 | `catalogueSubmissionBodyHtml` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |
| 568 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [tickets](/code/tables/tickets.md) |  |
| 604 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 605 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 606 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 619 | `catalogueSubmissionAnswers` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/services/forms.php](/code/files/includes/services/forms.php.md)

**Included by:** [api/forms/catalogue_approvals.php](/code/files/api/forms/catalogue_approvals.php.md), [api/forms/decide_catalogue_approval.php](/code/files/api/forms/decide_catalogue_approval.php.md), [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md)

## Traps

> [!WARNING]
> **Line 301, in `catalogueCreateTicketFromSubmission`:** the install's numbering, never a number made here - see `messagingGenerateTicketNumber()` for what went wrong when it was.

