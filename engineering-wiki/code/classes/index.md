# Classes

| Class | File | Methods | What it is |
|---|---|--:|---|
| [ActorContext](/code/classes/ActorContext.md) | [includes/service_context.php](/code/files/includes/service_context.php.md) | 6 | Who is acting, and what they may see — the only auth-derived facts a service needs. Both adapters produce the same shape from their own transport. |
| [AnalystsService](/code/classes/AnalystsService.md) | [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | 17 |  |
| [AssetFieldsService](/code/classes/AssetFieldsService.md) | [includes/services/asset_fields.php](/code/files/includes/services/asset_fields.php.md) | 33 |  |
| [AssetImportService](/code/classes/AssetImportService.md) | [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) | 31 |  |
| [AssetsService](/code/classes/AssetsService.md) | [includes/services/assets.php](/code/files/includes/services/assets.php.md) | 27 |  |
| [AssetTagsService](/code/classes/AssetTagsService.md) | [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) | 18 |  |
| [Audience](/code/classes/Audience.md) | [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md) | 5 | Who is allowed to read a Knowledge article. |
| [AzureDevOpsProvider](/code/classes/AzureDevOpsProvider.md) | [includes/integrations/AzureDevOpsProvider.php](/code/files/includes/integrations/AzureDevOpsProvider.php.md) | 38 |  |
| [CalDavCalendarProvider](/code/classes/CalDavCalendarProvider.md) | [includes/calendar_sync/CalDavCalendarProvider.php](/code/files/includes/calendar_sync/CalDavCalendarProvider.php.md) | 31 |  |
| [CalendarEventMissing](/code/classes/CalendarEventMissing.md) | [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md) | 0 | The event we were told to update is no longer there. |
| [CalendarService](/code/classes/CalendarService.md) | [includes/services/calendar.php](/code/files/includes/services/calendar.php.md) | 8 |  |
| [CalendarSubscriptionMissing](/code/classes/CalendarSubscriptionMissing.md) | [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md) | 0 | The subscription we tried to renew has already lapsed. |
| [CalendarSyncProvider](/code/classes/CalendarSyncProvider.md) | [includes/calendar_sync/CalendarSyncProvider.php](/code/files/includes/calendar_sync/CalendarSyncProvider.php.md) | 13 | CalendarSyncProvider — the provider-agnostic contract for putting an analyst's scheduled work into the calendar they actually live in (GH discussion #75). |
| [Cap](/code/classes/Cap.md) | [includes/capabilities.php](/code/files/includes/capabilities.php.md) | 0 | The capability keys. THE TYPE. Every guard call site names one of these constants. |
| [ChangesService](/code/classes/ChangesService.md) | [includes/services/changes.php](/code/files/includes/services/changes.php.md) | 19 |  |
| [ChecklistsService](/code/classes/ChecklistsService.md) | [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) | 29 |  |
| [CmdbService](/code/classes/CmdbService.md) | [includes/services/cmdb.php](/code/files/includes/services/cmdb.php.md) | 20 |  |
| [ContractsService](/code/classes/ContractsService.md) | [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | 22 |  |
| [CostCentresService](/code/classes/CostCentresService.md) | [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) | 17 |  |
| [DateFmt](/code/classes/DateFmt.md) | [includes/timezone.php](/code/files/includes/timezone.php.md) | 9 | Per-user date & time FORMAT (GH #105). |
| [DomainsService](/code/classes/DomainsService.md) | [includes/services/domains.php](/code/files/includes/services/domains.php.md) | 36 |  |
| [EwMarkdown](/code/classes/EwMarkdown.md) | [includes/engineering_wiki/markdown.php](/code/files/includes/engineering_wiki/markdown.php.md) | 18 | Engineering wiki - a small Markdown renderer written for the wiki, so the pages look and read like GitHub without pulling in a library. |
| [EwScanner](/code/classes/EwScanner.md) | [includes/engineering_wiki/scanner.php](/code/files/includes/engineering_wiki/scanner.php.md) | 62 |  |
| [FilesAcl](/code/classes/FilesAcl.md) | [includes/files/acl.php](/code/files/includes/files/acl.php.md) | 11 | Files — who may do what to a folder. The ONE place that answers it. |
| [FormsService](/code/classes/FormsService.md) | [includes/services/forms.php](/code/files/includes/services/forms.php.md) | 56 |  |
| [FreeitsmProvider](/code/classes/FreeitsmProvider.md) | [includes/messaging/FreeitsmProvider.php](/code/files/includes/messaging/FreeitsmProvider.php.md) | 4 |  |
| [HandoverTemplates](/code/classes/HandoverTemplates.md) | [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md) | 16 |  |
| [HypervisorHttp](/code/classes/HypervisorHttp.md) | [includes/hypervisor_http.php](/code/files/includes/hypervisor_http.php.md) | 2 |  |
| [I18n](/code/classes/I18n.md) | [includes/i18n.php](/code/files/includes/i18n.php.md) | 13 | i18n — Internationalisation |
| [IssueDoc](/code/classes/IssueDoc.md) | [includes/integrations/IssueDoc.php](/code/files/includes/integrations/IssueDoc.php.md) | 17 | IssueDoc — one description/comment, written once, rendered per provider. |
| [IssueTrackerProvider](/code/classes/IssueTrackerProvider.md) | [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md) | 18 | IssueTrackerProvider — the provider-agnostic contract for an external issue tracker (Jira first; GitHub, GitLab and Azure DevOps behind the same door). |
| [JiraProvider](/code/classes/JiraProvider.md) | [includes/integrations/JiraProvider.php](/code/files/includes/integrations/JiraProvider.php.md) | 31 |  |
| [JsonSchema4](/code/classes/JsonSchema4.md) | [api/v1/dev/jsonschema4.php](/code/files/api/v1/dev/jsonschema4.php.md) | 12 |  |
| [KnowledgeAclCache](/code/classes/KnowledgeAclCache.md) | [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) | 3 | Per-request memo for the four things the access list needs to look up: the permission model, whether any rules exist at all, the rules themselves, and the folder tree. Read once per request rather than once per query — a page that lists articles and then fetches one would otherwise ask four times over. |
| [KnowledgeService](/code/classes/KnowledgeService.md) | [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) | 14 |  |
| [KnowledgeViewer](/code/classes/KnowledgeViewer.md) | [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) | 16 | WHO is asking. Not the same thing as ActorContext (service_context.php), which models an *analyst* performing a write — a Knowledge reader may be a portal user or an anonymous web-chat visitor, neither of whom has an analyst id at all. |
| [LmsLearner](/code/classes/LmsLearner.md) | [includes/lms_access.php](/code/files/includes/lms_access.php.md) | 10 | WHO IS TAKING A COURSE. |
| [MandatoryFieldsService](/code/classes/MandatoryFieldsService.md) | [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) | 9 |  |
| [MattermostProvider](/code/classes/MattermostProvider.md) | [includes/messaging/MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md) | 19 |  |
| [MessagingProvider](/code/classes/MessagingProvider.md) | [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md) | 14 | MessagingProvider — the provider-agnostic contract for a chat channel (WhatsApp today; SMS/others later). Everything above this line — webhook ingestion, ticket creation, the reply path — talks to this interface and never knows whether Twilio or Meta Cloud is actually live. |
| [MetaCloudProvider](/code/classes/MetaCloudProvider.md) | [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md) | 9 |  |
| [MicrosoftCalendarProvider](/code/classes/MicrosoftCalendarProvider.md) | [includes/calendar_sync/MicrosoftCalendarProvider.php](/code/files/includes/calendar_sync/MicrosoftCalendarProvider.php.md) | 17 |  |
| [MorningChecksService](/code/classes/MorningChecksService.md) | [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md) | 18 |  |
| [NetworkMapperService](/code/classes/NetworkMapperService.md) | [includes/services/network_mapper.php](/code/files/includes/services/network_mapper.php.md) | 17 |  |
| [NotificationsService](/code/classes/NotificationsService.md) | [includes/services/notifications.php](/code/files/includes/services/notifications.php.md) | 18 |  |
| [ProblemsService](/code/classes/ProblemsService.md) | [includes/services/problems.php](/code/files/includes/services/problems.php.md) | 15 |  |
| [ProjectReportsService](/code/classes/ProjectReportsService.md) | [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) | 20 |  |
| [ProjectsService](/code/classes/ProjectsService.md) | [includes/services/projects.php](/code/files/includes/services/projects.php.md) | 39 |  |
| [ProjectTemplatesService](/code/classes/ProjectTemplatesService.md) | [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) | 9 |  |
| [ProjectToolsService](/code/classes/ProjectToolsService.md) | [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | 77 |  |
| [ServiceError](/code/classes/ServiceError.md) | [includes/service_context.php](/code/files/includes/service_context.php.md) | 1 | A business-rule failure raised by a service. `$kind` drives the HTTP status the API adapter emits; `$code` + the message are passed through verbatim so the API's existing error bodies stay byte-identical. |
| [ServiceStatusService](/code/classes/ServiceStatusService.md) | [includes/services/service_status.php](/code/files/includes/services/service_status.php.md) | 18 |  |
| [ServiceUptime](/code/classes/ServiceUptime.md) | [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md) | 13 |  |
| [SlackProvider](/code/classes/SlackProvider.md) | [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md) | 18 |  |
| [SoftwareService](/code/classes/SoftwareService.md) | [includes/services/software.php](/code/files/includes/services/software.php.md) | 6 |  |
| [TaskRecurrence](/code/classes/TaskRecurrence.md) | [includes/services/task_recurrence.php](/code/files/includes/services/task_recurrence.php.md) | 23 | Task recurrence — the date engine and the spawning of occurrences. |
| [TasksService](/code/classes/TasksService.md) | [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | 48 |  |
| [TeamsProvider](/code/classes/TeamsProvider.md) | [includes/messaging/TeamsProvider.php](/code/files/includes/messaging/TeamsProvider.php.md) | 14 |  |
| [TelegramProvider](/code/classes/TelegramProvider.md) | [includes/messaging/TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md) | 14 |  |
| [Theme](/code/classes/Theme.md) | [includes/theme.php](/code/files/includes/theme.php.md) | 5 | Theme / palette registry + resolver. |
| [TicketNumbering](/code/classes/TicketNumbering.md) | [includes/ticket_numbering.php](/code/files/includes/ticket_numbering.php.md) | 23 |  |
| [TicketsService](/code/classes/TicketsService.md) | [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) | 19 |  |
| [TwilioProvider](/code/classes/TwilioProvider.md) | [includes/messaging/TwilioProvider.php](/code/files/includes/messaging/TwilioProvider.php.md) | 9 |  |
| [TypedFields](/code/classes/TypedFields.md) | [includes/typed_fields.php](/code/files/includes/typed_fields.php.md) | 12 |  |
| [Tz](/code/classes/Tz.md) | [includes/timezone.php](/code/files/includes/timezone.php.md) | 4 | Per-analyst timezone support. |
| [WorkflowEngine](/code/classes/WorkflowEngine.md) | [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | 60 | Workflow Engine ---------------------------------------------------------------------- |
| [WorkflowTemplates](/code/classes/WorkflowTemplates.md) | [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md) | 6 |  |
