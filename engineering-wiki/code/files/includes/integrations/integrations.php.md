# includes/integrations/integrations.php

> PHP - 1530 lines

Integrations service — loading connections, escalating a work item to an external tracker, and refreshing the status of what we have already linked.

This is the ONE place an escalation happens. The workflow action
(escalate_to_tracker) and the manual right-click both call
`integrationsEscalate()`; there is deliberately not a second implementation for
the "automatic" path, because the guards below would then exist twice and one
copy would eventually drift.

⚠️ SECURITY: `integrationsCompaniesCompatible()` is an isolation boundary, not a
validation nicety. Read its docblock before changing anything here.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md) | 39 | Are the integration tables present? |
| [integrationsAvailableProviders()](/code/functions/integrationsAvailableProviders.md) | 76 | The provider registry — the ONE list adding a tracker touches at this layer. |
| [integrationsTrackerProviders()](/code/functions/integrationsTrackerProviders.md) | 174 | Providers that are genuinely issue trackers — the ones an escalation, a "raise an issue" button or a workflow action can target. |
| [integrationsAbsoluteUrl()](/code/functions/integrationsAbsoluteUrl.md) | 199 | An ABSOLUTE url for a path in this install, for links we send OUT. |
| [integrationsSettingKeys()](/code/functions/integrationsSettingKeys.md) | 215 | The non-secret setting keys a provider declares, for use as a whitelist. |
| [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md) | 230 | One registry entry, or null if the key is not a provider we ship. |
| [integrationsBodyToText()](/code/functions/integrationsBodyToText.md) | 257 | An email body reduced to text worth putting in an issue description. |
| [integrationsProviderFor()](/code/functions/integrationsProviderFor.md) | 289 | provider string → concrete connector. |
| [integrationsDecodeCredentials()](/code/functions/integrationsDecodeCredentials.md) | 306 | Decrypt + JSON-decode a stored credentials blob (never throws). |
| [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md) | 324 | Load a connection with its secrets decrypted. Returns null if missing or if the schema is not there yet. |
| [integrationsListConnections()](/code/functions/integrationsListConnections.md) | 363 | List connections for the admin UI. |
| [integrationsCompaniesCompatible()](/code/functions/integrationsCompaniesCompatible.md) | 434 | ⚠️ THE ISOLATION BOUNDARY. Read this before touching it. |
| [integrationsEntityTenantId()](/code/functions/integrationsEntityTenantId.md) | 452 | The tenant_id of a ticket / problem / change, or null. |
| [integrationsLinksFor()](/code/functions/integrationsLinksFor.md) | 476 | Links for one work item, newest first. Empty when the schema is not ready. |
| [integrationsAlreadyLinked()](/code/functions/integrationsAlreadyLinked.md) | 501 | Is this work item already linked to this connection? (drives skip_if_linked) |
| [integrationsEscalate()](/code/functions/integrationsEscalate.md) | 528 | Escalate a work item into an external tracker: create the issue, record the link, return it. |
| [integrationsAttachmentRoot()](/code/functions/integrationsAttachmentRoot.md) | 706 | Where ticket attachments live on disk, relative to the app root. |
| [integrationsTicketAttachments()](/code/functions/integrationsTicketAttachments.md) | 732 | The attachments on a ticket that are worth sending to a tracker. |
| [integrationsFormatBytes()](/code/functions/integrationsFormatBytes.md) | 784 | "1.4 MB" / "812 KB" — for the preview and the log line. |
| [integrationsSendAttachments()](/code/functions/integrationsSendAttachments.md) | 804 | Push a ticket's attachments onto an issue that has just been created. |
| [integrationsTicketPayload()](/code/functions/integrationsTicketPayload.md) | 854 | The ticket block every tracker.* event carries, in the same shape the tickets service dispatches — so `{{ticket.requester_email}}` means the same thing whichever trigger a workflow hangs off. |
| [integrationsEmitTrackerEvent()](/code/functions/integrationsEmitTrackerEvent.md) | 924 | Fire a `tracker.*` workflow event. |
| [integrationsMapSchemaReady()](/code/functions/integrationsMapSchemaReady.md) | 969 | Is the mapping table present? Separate gate again, for the same reason as the comment map: an install that ran Database Verification for an earlier release has the connections but not this, and must degrade to "no mapping configured" rather than throwing inside an escalation. |
| [integrationsLoadMaps()](/code/functions/integrationsLoadMaps.md) | 988 | Every mapping for one connection, as [map_type][local_key] => external_key. Empty array when nothing is configured or the table is missing — mapping is always optional, and an unmapped install must keep working exactly as before. |
| [integrationsSaveMaps()](/code/functions/integrationsSaveMaps.md) | 1016 | Replace every mapping of the given types for a connection. |
| [integrationsResolveProject()](/code/functions/integrationsResolveProject.md) | 1073 | Which Jira project should this work item's issue go in? |
| [integrationsResolveIssueType()](/code/functions/integrationsResolveIssueType.md) | 1091 | Our ticket type → the tracker's issue type. Falls back to the `*` row, so an install can say "everything is a Task" without listing every type. |
| [integrationsResolvePriority()](/code/functions/integrationsResolvePriority.md) | 1110 | Our priority → the tracker's priority name. |
| [integrationsLooksLikePriorityRejection()](/code/functions/integrationsLooksLikePriorityRejection.md) | 1126 | Does this error look like the tracker rejecting our priority? |
| [integrationsEntityRouting()](/code/functions/integrationsEntityRouting.md) | 1144 | The company, department, type and priority of a work item, for routing. |
| [integrationsCommentSchemaReady()](/code/functions/integrationsCommentSchemaReady.md) | 1172 | Is the comment map present? Separate from `integrationsSchemaReady()` because it arrived a release later: an install that ran Database Verification for V1 but not since has the links but not the map, and must degrade to "no comments come back" rather than throwing inside the poll or the ticket view. |
| [integrationsCommentIsEcho()](/code/functions/integrationsCommentIsEcho.md) | 1218 | Is this inbound comment authored by the account WE authenticate as? |
| [integrationsCommentSkipReason()](/code/functions/integrationsCommentSkipReason.md) | 1243 | Should this comment event become a note? The complete decision, pure. |
| [integrationsKnownCommentIds()](/code/functions/integrationsKnownCommentIds.md) | 1263 | Comment ids already mapped for one link, in either direction. |
| [integrationsRecordOutboundComment()](/code/functions/integrationsRecordOutboundComment.md) | 1286 | Record a comment we PUSHED, so it is recognised if it comes back. |
| [integrationsCommentNoteText()](/code/functions/integrationsCommentNoteText.md) | 1311 | How an imported comment reads on the ticket. |
| [integrationsApplyCommentEvent()](/code/functions/integrationsApplyCommentEvent.md) | 1338 | Apply one canonical comment event: an internal note on the ticket, plus the map row that stops it ever being imported twice. |
| [integrationsPullComments()](/code/functions/integrationsPullComments.md) | 1414 | Pull new comments for every link on one connection. The second half of the poll cron's unit of work. |
| [integrationsRefreshConnection()](/code/functions/integrationsRefreshConnection.md) | 1468 | Refresh the cached status of every link on one connection. The poll cron's unit of work. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `integrationsSchemaReady` | read [integration_connections](/code/tables/integration_connections.md) |  |
| 45 | `integrationsSchemaReady` | read [integration_links](/code/tables/integration_links.md) |  |
| 328 | `integrationsLoadConnection` | read [integration_connections](/code/tables/integration_connections.md) |  |
| 368 | `integrationsListConnections` | read [integration_connections](/code/tables/integration_connections.md) |  |
| 481 | `integrationsLinksFor` | read [integration_links](/code/tables/integration_links.md), read [integration_connections](/code/tables/integration_connections.md) |  |
| 506 | `integrationsAlreadyLinked` | read [integration_links](/code/tables/integration_links.md) |  |
| 642 | `integrationsEscalate` | **insert** [integration_links](/code/tables/integration_links.md) (`connection_id`, `entity_type`, `entity_id`, `external_id`, `external_key`, `external_url`, `status_name`, `status_category`, `assignee_name`, `last_synced_datetime`, `last_error`, `created_by`) |  |
| 679 | `integrationsEscalate` | **update** [integration_links](/code/tables/integration_links.md) (`last_error`) |  |
| 736 | `integrationsTicketAttachments` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 869 | `integrationsTicketPayload` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |
| 974 | `integrationsMapSchemaReady` | read [integration_field_maps](/code/tables/integration_field_maps.md) |  |
| 993 | `integrationsLoadMaps` | read [integration_field_maps](/code/tables/integration_field_maps.md) |  |
| 1030 | `integrationsSaveMaps` | **delete** [integration_field_maps](/code/tables/integration_field_maps.md) |  |
| 1034 | `integrationsSaveMaps` | **insert** [integration_field_maps](/code/tables/integration_field_maps.md) (`connection_id`, `map_type`, `local_key`, `external_key`) |  |
| 1149 | `integrationsEntityRouting` | read [tickets](/code/tables/tickets.md) |  |
| 1177 | `integrationsCommentSchemaReady` | read [integration_comment_map](/code/tables/integration_comment_map.md) |  |
| 1267 | `integrationsKnownCommentIds` | read [integration_comment_map](/code/tables/integration_comment_map.md) |  |
| 1291 | `integrationsRecordOutboundComment` | **insert** [integration_comment_map](/code/tables/integration_comment_map.md) (`link_id`, `direction`, `external_comment_id`, `local_note_id`, `created_datetime`) |  |
| 1356 | `integrationsApplyCommentEvent` | **insert** [integration_comment_map](/code/tables/integration_comment_map.md) (`link_id`, `direction`, `external_comment_id`, `author_identity`, `author_name`, `created_datetime`) |  |
| 1374 | `integrationsApplyCommentEvent` | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |
| 1379 | `integrationsApplyCommentEvent` | **update** [integration_comment_map](/code/tables/integration_comment_map.md) (`local_note_id`) |  |
| 1381 | `integrationsApplyCommentEvent` | **update** [tickets](/code/tables/tickets.md) (`updated_datetime`) |  |
| 1424 | `integrationsPullComments` | read [integration_links](/code/tables/integration_links.md) |  |
| 1450 | `integrationsPullComments` | **update** [integration_connections](/code/tables/integration_connections.md) (`last_poll_watermark`) |  |
| 1473 | `integrationsRefreshConnection` | read [integration_links](/code/tables/integration_links.md) |  |
| 1492 | `integrationsRefreshConnection` | **update** [integration_links](/code/tables/integration_links.md) (`status_name`, `status_category`, `assignee_name`, `last_synced_datetime`) |  |

## How it hangs together

**Includes:** [includes/integrations/IssueTrackerProvider.php](/code/files/includes/integrations/IssueTrackerProvider.php.md), [includes/integrations/IssueDoc.php](/code/files/includes/integrations/IssueDoc.php.md), [includes/integrations/JiraProvider.php](/code/files/includes/integrations/JiraProvider.php.md), [includes/integrations/AzureDevOpsProvider.php](/code/files/includes/integrations/AzureDevOpsProvider.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ssl.php](/code/files/includes/ssl.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md), [api/integrations/delete_connection.php](/code/files/api/integrations/delete_connection.php.md), [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md), [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md), [api/integrations/list_connections.php](/code/files/api/integrations/list_connections.php.md), [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md), [api/integrations/save_mapping.php](/code/files/api/integrations/save_mapping.php.md), [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md), [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md), [api/integrations/tracker_options.php](/code/files/api/integrations/tracker_options.php.md), [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md), [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md), [cron/integration_poll.php](/code/files/cron/integration_poll.php.md), [system/integrations/help.php](/code/files/system/integrations/help.php.md), [system/integrations/index.php](/code/files/system/integrations/index.php.md), [system/integrations/provider.php](/code/files/system/integrations/provider.php.md), [system/integrations/slack.php](/code/files/system/integrations/slack.php.md), [system/integrations/tika.php](/code/files/system/integrations/tika.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md)

