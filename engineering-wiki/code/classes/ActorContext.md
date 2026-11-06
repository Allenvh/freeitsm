# ActorContext

> Class in [includes/service_context.php](/code/files/includes/service_context.php.md), line 20

Who is acting, and what they may see — the only auth-derived facts a service needs. Both adapters produce the same shape from their own transport.

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Who is acting, which companies they may reach, where the call came from (ui or api), their locale and display name. |
| [fromSession()](#fromsession) | Build from the logged-in analyst session (UI adapters). |
| [fromPortalUser()](#fromportaluser) | Build from a signed-in SELF-SERVICE PORTAL user. |
| [system()](#system) | Build for something no person did: an inventory agent, Intune, a cron job (PR #164). actorId 0 is recorded as "no analyst" (NULL) in history, and companyScope null means the caller has already decided the company - an agent's key carries it, Intune's setting names it. |
| [fromApiKey()](#fromapikey) | Build from an authenticated API key row (API adapters). |
| [sessionCompanyScope()](#sessioncompanyscope) | The analyst's company scope in the API's null-means-all convention: single-company install or an all-tenant analyst → null; otherwise the explicit accessible tenant id list. (Unused by install-wide modules; the first tenant-scoped module to migrate exercises it.) |

### __construct

*public* - line 30

Who is acting, which companies they may reach, where the call came from (ui or api), their locale and display name.

```php
function __construct(
    int $actorId,
    ?array $companyScope = null,
    string $source = 'api',
    string $locale = 'en',
    string $actorName = ''
)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$actorId` | `int` |  | the acting analyst's id; 0 for a portal user or the system |
| `$companyScope` | `?array` | `null` | null = all companies, else the allowed tenant ids |
| `$source` | `string` | `'api'` | 'ui' \| 'api' |
| `$locale` | `string` | `'en'` | the actor's language code, e.g. 'en' |
| `$actorName` | `string` | `''` | the acting analyst's display name (for *_by attribution columns) |

### fromSession

*public static* - line 39

Build from the logged-in analyst session (UI adapters).

```php
function fromSession(PDO $conn): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Called from:** [api/assets/assign_asset_user.php](/code/files/api/assets/assign_asset_user.php.md), [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md), [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md), [api/assets/get_people.php](/code/files/api/assets/get_people.php.md), [api/assets/get_users_with_assets.php](/code/files/api/assets/get_users_with_assets.php.md), [api/assets/get_user_assets.php](/code/files/api/assets/get_user_assets.php.md), [api/assets/handover_templates.php](/code/files/api/assets/handover_templates.php.md), [api/assets/import_run.php](/code/files/api/assets/import_run.php.md), [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md), [api/assets/move_to_company.php](/code/files/api/assets/move_to_company.php.md), [api/assets/save_asset_custom_fields.php](/code/files/api/assets/save_asset_custom_fields.php.md), [api/assets/save_asset_field.php](/code/files/api/assets/save_asset_field.php.md), [api/assets/save_asset_field_set.php](/code/files/api/assets/save_asset_field_set.php.md), [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md), [api/assets/set_asset_field_set.php](/code/files/api/assets/set_asset_field_set.php.md), [api/assets/unassign_asset_user.php](/code/files/api/assets/unassign_asset_user.php.md), [api/assets/update_asset_field.php](/code/files/api/assets/update_asset_field.php.md), [api/calendar/delete_category.php](/code/files/api/calendar/delete_category.php.md), [api/calendar/delete_event.php](/code/files/api/calendar/delete_event.php.md), [api/calendar/save_category.php](/code/files/api/calendar/save_category.php.md), [api/calendar/save_event.php](/code/files/api/calendar/save_event.php.md), [api/change-management/delete.php](/code/files/api/change-management/delete.php.md), [api/change-management/delete_comment.php](/code/files/api/change-management/delete_comment.php.md), [api/change-management/link_ticket.php](/code/files/api/change-management/link_ticket.php.md), [api/change-management/save.php](/code/files/api/change-management/save.php.md), [api/change-management/save_cab_members.php](/code/files/api/change-management/save_cab_members.php.md), [api/change-management/save_comment.php](/code/files/api/change-management/save_comment.php.md), [api/change-management/submit_cab_vote.php](/code/files/api/change-management/submit_cab_vote.php.md), [api/change-management/unlink_ticket.php](/code/files/api/change-management/unlink_ticket.php.md), [api/change-management/update_field.php](/code/files/api/change-management/update_field.php.md), [api/checklists/delete_lookup.php](/code/files/api/checklists/delete_lookup.php.md), [api/checklists/save_lookup.php](/code/files/api/checklists/save_lookup.php.md), [api/cmdb/create_impact_diagram.php](/code/files/api/cmdb/create_impact_diagram.php.md), [api/cmdb/delete_object.php](/code/files/api/cmdb/delete_object.php.md), [api/cmdb/delete_object_relationship.php](/code/files/api/cmdb/delete_object_relationship.php.md), [api/cmdb/save_object.php](/code/files/api/cmdb/save_object.php.md), [api/cmdb/save_object_relationship.php](/code/files/api/cmdb/save_object_relationship.php.md), [api/contracts/delete_contract.php](/code/files/api/contracts/delete_contract.php.md), [api/contracts/delete_supplier.php](/code/files/api/contracts/delete_supplier.php.md), [api/contracts/save_contract.php](/code/files/api/contracts/save_contract.php.md), [api/contracts/save_contract_terms.php](/code/files/api/contracts/save_contract_terms.php.md), [api/contracts/save_supplier.php](/code/files/api/contracts/save_supplier.php.md), [api/forms/create_version.php](/code/files/api/forms/create_version.php.md), [api/forms/delete_collection.php](/code/files/api/forms/delete_collection.php.md), [api/forms/delete_form.php](/code/files/api/forms/delete_form.php.md), [api/forms/delete_submission.php](/code/files/api/forms/delete_submission.php.md), [api/forms/save_collection.php](/code/files/api/forms/save_collection.php.md), [api/forms/save_collection_forms.php](/code/files/api/forms/save_collection_forms.php.md), [api/forms/save_form.php](/code/files/api/forms/save_form.php.md), [api/forms/set_collection_closed.php](/code/files/api/forms/set_collection_closed.php.md), [api/forms/submit_form.php](/code/files/api/forms/submit_form.php.md), [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), [api/knowledge/knowledge_bulk_audience.php](/code/files/api/knowledge/knowledge_bulk_audience.php.md), [api/knowledge/knowledge_delete.php](/code/files/api/knowledge/knowledge_delete.php.md), [api/knowledge/knowledge_save.php](/code/files/api/knowledge/knowledge_save.php.md), [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md), [api/morning-checks/add_check.php](/code/files/api/morning-checks/add_check.php.md), [api/morning-checks/clear_check_result.php](/code/files/api/morning-checks/clear_check_result.php.md), [api/morning-checks/delete_check.php](/code/files/api/morning-checks/delete_check.php.md), [api/morning-checks/delete_group.php](/code/files/api/morning-checks/delete_group.php.md), [api/morning-checks/delete_status.php](/code/files/api/morning-checks/delete_status.php.md), [api/morning-checks/normalise_status.php](/code/files/api/morning-checks/normalise_status.php.md), [api/morning-checks/reorder_checks.php](/code/files/api/morning-checks/reorder_checks.php.md), [api/morning-checks/reorder_statuses.php](/code/files/api/morning-checks/reorder_statuses.php.md), [api/morning-checks/save_check_result.php](/code/files/api/morning-checks/save_check_result.php.md), [api/morning-checks/save_group.php](/code/files/api/morning-checks/save_group.php.md), [api/morning-checks/save_status.php](/code/files/api/morning-checks/save_status.php.md), [api/morning-checks/update_check.php](/code/files/api/morning-checks/update_check.php.md), [api/network-mapper/create_diagram.php](/code/files/api/network-mapper/create_diagram.php.md), [api/network-mapper/create_version.php](/code/files/api/network-mapper/create_version.php.md), [api/network-mapper/delete_diagram.php](/code/files/api/network-mapper/delete_diagram.php.md), [api/network-mapper/save_diagram.php](/code/files/api/network-mapper/save_diagram.php.md), [api/problem-management/add_note.php](/code/files/api/problem-management/add_note.php.md), [api/problem-management/delete.php](/code/files/api/problem-management/delete.php.md), [api/problem-management/link_change.php](/code/files/api/problem-management/link_change.php.md), [api/problem-management/link_ticket.php](/code/files/api/problem-management/link_ticket.php.md), [api/problem-management/save.php](/code/files/api/problem-management/save.php.md), [api/problem-management/unlink_change.php](/code/files/api/problem-management/unlink_change.php.md), [api/problem-management/unlink_ticket.php](/code/files/api/problem-management/unlink_ticket.php.md), [api/service-status/delete_impact_level.php](/code/files/api/service-status/delete_impact_level.php.md), [api/service-status/delete_incident.php](/code/files/api/service-status/delete_incident.php.md), [api/service-status/delete_incident_status.php](/code/files/api/service-status/delete_incident_status.php.md), [api/service-status/delete_incident_update.php](/code/files/api/service-status/delete_incident_update.php.md), [api/service-status/delete_service.php](/code/files/api/service-status/delete_service.php.md), [api/service-status/planned.php](/code/files/api/service-status/planned.php.md), [api/service-status/save_impact_level.php](/code/files/api/service-status/save_impact_level.php.md), [api/service-status/save_incident.php](/code/files/api/service-status/save_incident.php.md), [api/service-status/save_incident_status.php](/code/files/api/service-status/save_incident_status.php.md), [api/service-status/save_incident_update.php](/code/files/api/service-status/save_incident_update.php.md), [api/service-status/save_service.php](/code/files/api/service-status/save_service.php.md), [api/software/delete_licence.php](/code/files/api/software/delete_licence.php.md), [api/software/save_licence.php](/code/files/api/software/save_licence.php.md), [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md), [api/tasks/checklists.php](/code/files/api/tasks/checklists.php.md), [api/tasks/collaborators.php](/code/files/api/tasks/collaborators.php.md), [api/tasks/delete.php](/code/files/api/tasks/delete.php.md), [api/tasks/delete_time_entry.php](/code/files/api/tasks/delete_time_entry.php.md), [api/tasks/get_time_entries.php](/code/files/api/tasks/get_time_entries.php.md), [api/tasks/move_to_company.php](/code/files/api/tasks/move_to_company.php.md), [api/tasks/reorder.php](/code/files/api/tasks/reorder.php.md), [api/tasks/save.php](/code/files/api/tasks/save.php.md), [api/tasks/save_comment.php](/code/files/api/tasks/save_comment.php.md), [api/tasks/save_time_entry.php](/code/files/api/tasks/save_time_entry.php.md), [api/tasks/toggle_subtask.php](/code/files/api/tasks/toggle_subtask.php.md), [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md), [api/tickets/assign_ticket.php](/code/files/api/tickets/assign_ticket.php.md), [api/tickets/bulk_delete_tickets.php](/code/files/api/tickets/bulk_delete_tickets.php.md), [api/tickets/bulk_update_tickets.php](/code/files/api/tickets/bulk_update_tickets.php.md), [api/tickets/create_ticket.php](/code/files/api/tickets/create_ticket.php.md), [api/tickets/delete_analyst.php](/code/files/api/tickets/delete_analyst.php.md), [api/tickets/delete_ticket.php](/code/files/api/tickets/delete_ticket.php.md), [api/tickets/delete_time_entry.php](/code/files/api/tickets/delete_time_entry.php.md), [api/tickets/get_mandatory_fields_check.php](/code/files/api/tickets/get_mandatory_fields_check.php.md), [api/tickets/restore_ticket.php](/code/files/api/tickets/restore_ticket.php.md), [api/tickets/save_note.php](/code/files/api/tickets/save_note.php.md), [api/tickets/save_ticket_task.php](/code/files/api/tickets/save_ticket_task.php.md), [api/tickets/save_time_entry.php](/code/files/api/tickets/save_time_entry.php.md), [api/tickets/schedule_ticket.php](/code/files/api/tickets/schedule_ticket.php.md), [api/tickets/update_ticket_owner.php](/code/files/api/tickets/update_ticket_owner.php.md), [asset-management/handover.php](/code/files/asset-management/handover.php.md), [includes/domains/api_bootstrap.php](/code/files/includes/domains/api_bootstrap.php.md), [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

### fromPortalUser

*public static* - line 65

Build from a signed-in SELF-SERVICE PORTAL user.

🔴 actorId is 0 ON PURPOSE. It names an ANALYST, and a requester is not
one - there is no honest value. Anything that writes an actor must cope
with 0 by storing NULL rather than inventing a member of staff, and
ticket_audit.analyst_id is nullable for exactly this reason.

companyScope is null because a portal user reaches nothing by scope: every
portal endpoint is narrowed to rows they own, by user id, before a service
is called at all.

```php
function fromPortalUser(string $name = ''): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` | `''` | the requester's display name, for the audit trail |

**Called from:** [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md)

### system

*public static* - line 84

Build for something no person did: an inventory agent, Intune, a cron job (PR #164). actorId 0 is recorded as "no analyst" (NULL) in history, and companyScope null means the caller has already decided the company - an agent's key carries it, Intune's setting names it.

```php
function system(string $name = 'System'): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` | `'System'` | what to record as the actor in history, e.g. 'Intune' or 'Inventory agent' |

**Called from:** [api/external/software-inventory/submit/index.php](/code/files/api/external/software-inventory/submit/index.php.md), [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), [includes/intune.php](/code/files/includes/intune.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md)

### fromApiKey

*public static* - line 100

Build from an authenticated API key row (API adapters).

```php
function fromApiKey(array $apiKey): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$apiKey` | `array` |  | the authenticated API key row from `apiAuthenticate()`, with analyst_id, company_scope and analyst_name |

**Called from:** [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md), [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), [api/v1/resources/calendar.php](/code/files/api/v1/resources/calendar.php.md), [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md), [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md), [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md), [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md), [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md), [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md)

### sessionCompanyScope

*private static* - line 117

The analyst's company scope in the API's null-means-all convention: single-company install or an all-tenant analyst → null; otherwise the explicit accessible tenant id list. (Unused by install-wide modules; the first tenant-scoped module to migrate exercises it.)

```php
function sessionCompanyScope(PDO $conn, int $analystId): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 123 | `ActorContext::sessionCompanyScope` | read [analysts](/code/tables/analysts.md) |  |

