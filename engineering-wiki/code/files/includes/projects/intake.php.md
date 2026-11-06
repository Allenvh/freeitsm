# includes/projects/intake.php

> PHP - 214 lines

Projects - intake and approval (3.3.0): proposing a project, and approving it before it can start.

A PROPOSAL is an ordinary project with status 'proposed' and
approval_status 'pending'. The case for it is its business_case; its own
figures are estimated_cost and estimated_benefit; when it came from a form,
form_submission_id and proposed_by_name / proposed_by_email say who asked.

WAYS IN
 - A form: the workflow action create_project (Forms -> a form's Submitted or
   Approved list, or any rule in Workflows) - `projectCreateFromAction()`.
 - The New project button, when project_proposal_approval is 'all'.

─────────────────────────────────────────────────────────────────────────────
THE JUDGEMENT CALLS ARE SETTINGS (Projects -> Settings -> General):
  project_proposal_approval   forms | all | off - which new projects wait
  project_proposal_approver   managers | person (+ project_proposal_approver_id)
  project_proposal_on_approve proposed | active - what approving does
🔑 Whoever holds Manage Projects can ALWAYS decide - a named approver who has
left must not strand every proposal.
🔑 approval_status NULL means "needs none": every project from before 3.3.0,
and every one created while the setting says it needs none. Turning the
setting on later never strands an existing project.
🔑 A pending proposal cannot go active, on hold or closed - only stay
proposed or be cancelled (withdrawn). ProjectsService::updateProject() asks
`projectProposalBlocksStatus()`.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectIntakeReady()](/code/functions/projectIntakeReady.md) | 34 | Has Database Verification added the columns? Everything here is quiet before it. |
| [projectProposalNeedsApproval()](/code/functions/projectProposalNeedsApproval.md) | 49 | Does a new project wait for approval? `$fromForm` = it was proposed through a form. |
| [projectProposalNamedApprover()](/code/functions/projectProposalNamedApprover.md) | 57 | The named approver, when the setting names one and they are still an active analyst. |
| [projectCanDecideProposal()](/code/functions/projectCanDecideProposal.md) | 68 | May this analyst approve or reject proposals? The named approver, and Manage Projects always. |
| [projectProposalApprovers()](/code/functions/projectProposalApprovers.md) | 79 | Who is told a proposal is waiting: the named approver if there is one, otherwise everybody who holds Manage Projects (admins included). |
| [projectProposalBlocksStatus()](/code/functions/projectProposalBlocksStatus.md) | 95 | A pending proposal may only stay proposed or be withdrawn (cancelled). |
| [projectProposalDetail()](/code/functions/projectProposalDetail.md) | 105 | The proposal panel on a project's page; null when it never needed approval. |
| [projectProposalApprovalColumn()](/code/functions/projectProposalApprovalColumn.md) | 140 | Proposals waiting for a decision, per project id - for the portfolio's view and its chip. |
| [projectCreateFromAction()](/code/functions/projectCreateFromAction.md) | 154 | The workflow action create_project: a proposal from a form (or any rule). Answers fill what the action leaves blank - the same rule the form-to-ticket path follows: an override always wins, the submission fills the rest. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectIntakeReady` | read [projects](/code/tables/projects.md) |  |
| 62 | `projectProposalNamedApprover` | read [analysts](/code/tables/analysts.md) |  |
| 83 | `projectProposalApprovers` | read [analysts](/code/tables/analysts.md) |  |
| 108 | `projectProposalDetail` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 161 | `projectCreateFromAction` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 173 | `projectCreateFromAction` | read [users](/code/tables/users.md) |  |
| 205 | `projectCreateFromAction` | **update** [projects](/code/tables/projects.md) (`form_submission_id`, `proposed_by_name`, `proposed_by_email`, `created_by_id`) |  |
| 207 | `projectCreateFromAction` | read [projects](/code/tables/projects.md) |  |

## How it hangs together

**Includes:** [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md)

**Included by:** [api/projects/get.php](/code/files/api/projects/get.php.md), [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [catalogueSubmissionAnswers()](/code/functions/catalogueSubmissionAnswers.md), [projectCode()](/code/functions/projectCode.md), [projectIsManager()](/code/functions/projectIsManager.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectPriorities()](/code/functions/projectPriorities.md), [projectSetting()](/code/functions/projectSetting.md)

