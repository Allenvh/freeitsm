# integrationsEscalate()

Escalate a work item into an external tracker: create the issue, record the link, return it.

The single entry point for BOTH the workflow action and the manual button.

```php
function integrationsEscalate(PDO $conn, array $opts): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 528.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` |  | ['entity_type'=>'ticket', 'entity_id'=>1042, 'connection_id'=>3, 'target'=>['project'=>'OPS','issue_type'=>'Bug'], 'summary'=>'…', 'body'=>IssueDoc, 'analyst_id'=>7, 'skip_if_linked'=>true, 'dry_run'=>false] |

**Returns:** array the created link row, or ['skipped'=>true, …] / ['dry_run'=>true, …]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 642 | `integrationsEscalate` | **insert** [integration_links](/code/tables/integration_links.md) (`connection_id`, `entity_type`, `entity_id`, `external_id`, `external_key`, `external_url`, `status_name`, `status_category`, `assignee_name`, `last_synced_datetime`, `last_error`, `created_by`) |  |
| 679 | `integrationsEscalate` | **update** [integration_links](/code/tables/integration_links.md) (`last_error`) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [integrationsAlreadyLinked()](/code/functions/integrationsAlreadyLinked.md), [integrationsCompaniesCompatible()](/code/functions/integrationsCompaniesCompatible.md), [integrationsEmitTrackerEvent()](/code/functions/integrationsEmitTrackerEvent.md), [integrationsEntityRouting()](/code/functions/integrationsEntityRouting.md), [integrationsEntityTenantId()](/code/functions/integrationsEntityTenantId.md), [integrationsFormatBytes()](/code/functions/integrationsFormatBytes.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsLoadMaps()](/code/functions/integrationsLoadMaps.md), [integrationsLooksLikePriorityRejection()](/code/functions/integrationsLooksLikePriorityRejection.md), [integrationsProviderFor()](/code/functions/integrationsProviderFor.md), [integrationsResolveIssueType()](/code/functions/integrationsResolveIssueType.md), [integrationsResolvePriority()](/code/functions/integrationsResolvePriority.md), [integrationsResolveProject()](/code/functions/integrationsResolveProject.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [integrationsSendAttachments()](/code/functions/integrationsSendAttachments.md), [integrationsTicketAttachments()](/code/functions/integrationsTicketAttachments.md)

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_escalate_to_tracker`

