# includes/services/project_reports.php

> PHP - 488 lines

ProjectReportsService - a project's briefing and reports (3.2.0), and the rules for who may write, approve and delete them. The AI side is includes/projects/ai.php.

briefing    the Overview's "Brief me": anyone who can open the project may
              ask; one is kept per project (the latest), and a new one is
              not drafted while the last is under 10 minutes old - each costs
              a call to the provider.
  highlight / exception / checkpoint
              reports. Drafted by the AI or written by a person, by anyone
              who may change the project. A draft can be edited; editing an
              AI draft marks it ai_edited. APPROVING is the project
              manager's, the creator's or a Projects manager's (the same
              people who may delete the project) - a report is what the board
              reads under the project's name. An approved report is final:
              it cannot be edited, and only those same people may delete it.

3.3.0: a project can have a report drafted by itself each week, fortnight or
month (runSchedules(), from the alerts scan) - never approved by itself; and an
approved report can be emailed (send()) by the people who may approve it.

Every write is in the project's history.

## Classes

- [ProjectReportsService](/code/classes/ProjectReportsService.md) (class, line 30)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `ProjectReportsService::ready` | read [project_reports](/code/tables/project_reports.md) |  |
| 46 | `ProjectReportsService::listFor` | read [project_reports](/code/tables/project_reports.md), read [analysts](/code/tables/analysts.md) |  |
| 63 | `ProjectReportsService::latestBriefing` | read [project_reports](/code/tables/project_reports.md), read [analysts](/code/tables/analysts.md) |  |
| 104 | `ProjectReportsService::briefing` | **delete** [project_reports](/code/tables/project_reports.md) |  |
| 105 | `ProjectReportsService::briefing` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 126 | `ProjectReportsService::draftWithAi` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 154 | `ProjectReportsService::save` | **update** [project_reports](/code/tables/project_reports.md) (`title`, `body`, `ai_edited`, `updated_by_id`, `updated_datetime`) |  |
| 160 | `ProjectReportsService::save` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 182 | `ProjectReportsService::approve` | **update** [project_reports](/code/tables/project_reports.md) (`status`, `approved_by_id`, `approved_datetime`) |  |
| 203 | `ProjectReportsService::delete` | **delete** [project_reports](/code/tables/project_reports.md) |  |
| 219 | `ProjectReportsService::scheduleReady` | read [projects](/code/tables/projects.md) |  |
| 219 | `ProjectReportsService::scheduleReady` | read [project_reports](/code/tables/project_reports.md) |  |
| 229 | `ProjectReportsService::schedule` | read [projects](/code/tables/projects.md) |  |
| 250 | `ProjectReportsService::setSchedule` | **update** [projects](/code/tables/projects.md) (`report_schedule`, `report_schedule_kind`) |  |
| 268 | `ProjectReportsService::recipients` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 273 | `ProjectReportsService::recipients` | read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |
| 328 | `ProjectReportsService::send` | **update** [project_reports](/code/tables/project_reports.md) (`sent_datetime`, `sent_by_id`, `sent_to`) |  |
| 389 | `ProjectReportsService::runSchedules` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 395 | `ProjectReportsService::runSchedules` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |
| 466 | `ProjectReportsService::draftScheduled` | **insert** [project_reports](/code/tables/project_reports.md) (`project_id`, `kind`, `title`, `body`, `status`, `ai_drafted`, `ai_model`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 481 | `ProjectReportsService::load` | read [project_reports](/code/tables/project_reports.md) |  |

## How it hangs together

**Includes:** [includes/services/projects.php](/code/files/includes/services/projects.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Included by:** [api/projects/reports.php](/code/files/api/projects/reports.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

**Calls:** [projectAiAsk()](/code/functions/projectAiAsk.md), [projectAiBriefingTask()](/code/functions/projectAiBriefingTask.md), [projectAiFacts()](/code/functions/projectAiFacts.md), [projectAiReady()](/code/functions/projectAiReady.md), [projectAiReportKinds()](/code/functions/projectAiReportKinds.md), [projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectCanDelete()](/code/functions/projectCanDelete.md), [projectCode()](/code/functions/projectCode.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md), [t()](/code/functions/t.md)

