# wf_emit()

Fire a settings/CRUD workflow event ({entity}.{created|updated|deleted}) from a UI settings endpoint, without each file having to require the workflow engine. Lazily loads the engine and is fully self-safe — swallows any Throwable (including a missing engine on a minimal install) so it can NEVER affect the save it follows. Funnels into the exact same WorkflowEngine::dispatch() path as every other event, so a webhook fires identically however it was emitted.

```php
function wf_emit(string $entity, string $action, int $id, ?string $name = null): void
```

Defined in [includes/functions.php](/code/files/includes/functions.php.md), line 401.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$entity` | `string` |  | the entity key, e.g. 'asset' or 'asset_location' - the first half of the event name |
| `$action` | `string` |  | 'created', 'updated' or 'deleted' - the second half of the event name |
| `$id` | `int` |  | the id of the record that changed |
| `$name` | `?string` | `null` | its display name for the payload, if known |

**Returns:** `void`

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/delete_asset_location.php](/code/files/api/assets/delete_asset_location.php.md)
- [api/assets/delete_asset_status_type.php](/code/files/api/assets/delete_asset_status_type.php.md)
- [api/assets/delete_asset_type.php](/code/files/api/assets/delete_asset_type.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md)
- [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md)
- [api/change-management/delete_change_impact.php](/code/files/api/change-management/delete_change_impact.php.md)
- [api/change-management/delete_change_priority.php](/code/files/api/change-management/delete_change_priority.php.md)
- [api/change-management/delete_change_status.php](/code/files/api/change-management/delete_change_status.php.md)
- [api/change-management/delete_change_type.php](/code/files/api/change-management/delete_change_type.php.md)
- [api/change-management/save_change_impact.php](/code/files/api/change-management/save_change_impact.php.md)
- [api/change-management/save_change_priority.php](/code/files/api/change-management/save_change_priority.php.md)
- [api/change-management/save_change_status.php](/code/files/api/change-management/save_change_status.php.md)
- [api/change-management/save_change_type.php](/code/files/api/change-management/save_change_type.php.md)
- [api/cmdb/delete_class.php](/code/files/api/cmdb/delete_class.php.md)
- [api/cmdb/delete_class_property.php](/code/files/api/cmdb/delete_class_property.php.md)
- [api/cmdb/delete_relationship_type.php](/code/files/api/cmdb/delete_relationship_type.php.md)
- [api/cmdb/save_class.php](/code/files/api/cmdb/save_class.php.md)
- [api/cmdb/save_class_property.php](/code/files/api/cmdb/save_class_property.php.md)
- [api/cmdb/save_relationship_type.php](/code/files/api/cmdb/save_relationship_type.php.md)
- [api/contracts/delete_contact.php](/code/files/api/contracts/delete_contact.php.md)
- [api/contracts/delete_contract_status.php](/code/files/api/contracts/delete_contract_status.php.md)
- [api/contracts/delete_contract_term_tab.php](/code/files/api/contracts/delete_contract_term_tab.php.md)
- [api/contracts/delete_payment_schedule.php](/code/files/api/contracts/delete_payment_schedule.php.md)
- [api/contracts/delete_supplier_status.php](/code/files/api/contracts/delete_supplier_status.php.md)
- [api/contracts/delete_supplier_type.php](/code/files/api/contracts/delete_supplier_type.php.md)
- [api/contracts/save_contact.php](/code/files/api/contracts/save_contact.php.md)
- [api/contracts/save_contract_status.php](/code/files/api/contracts/save_contract_status.php.md)
- [api/contracts/save_contract_term_tab.php](/code/files/api/contracts/save_contract_term_tab.php.md)
- [api/contracts/save_payment_schedule.php](/code/files/api/contracts/save_payment_schedule.php.md)
- [api/contracts/save_supplier_status.php](/code/files/api/contracts/save_supplier_status.php.md)
- [api/contracts/save_supplier_type.php](/code/files/api/contracts/save_supplier_type.php.md)
- [api/problem-management/delete_problem_priority.php](/code/files/api/problem-management/delete_problem_priority.php.md)
- [api/problem-management/delete_problem_status.php](/code/files/api/problem-management/delete_problem_status.php.md)
- [api/problem-management/save_problem_priority.php](/code/files/api/problem-management/save_problem_priority.php.md)
- [api/problem-management/save_problem_status.php](/code/files/api/problem-management/save_problem_status.php.md)
- [api/software/delete_app.php](/code/files/api/software/delete_app.php.md)
- [api/software/save_app.php](/code/files/api/software/save_app.php.md)
- [api/tasks/delete_task_priority.php](/code/files/api/tasks/delete_task_priority.php.md)
- [api/tasks/delete_task_status.php](/code/files/api/tasks/delete_task_status.php.md)
- [api/tasks/delete_task_tag.php](/code/files/api/tasks/delete_task_tag.php.md)
- [api/tasks/save_task_priority.php](/code/files/api/tasks/save_task_priority.php.md)
- [api/tasks/save_task_status.php](/code/files/api/tasks/save_task_status.php.md)
- [api/tasks/save_task_tag.php](/code/files/api/tasks/save_task_tag.php.md)
- [api/tickets/delete_ticket_category.php](/code/files/api/tickets/delete_ticket_category.php.md)
- [api/tickets/delete_ticket_origin.php](/code/files/api/tickets/delete_ticket_origin.php.md)
- [api/tickets/delete_ticket_priority.php](/code/files/api/tickets/delete_ticket_priority.php.md)
- [api/tickets/delete_ticket_resolution_code.php](/code/files/api/tickets/delete_ticket_resolution_code.php.md)
- [api/tickets/delete_ticket_status.php](/code/files/api/tickets/delete_ticket_status.php.md)
- [api/tickets/delete_ticket_type.php](/code/files/api/tickets/delete_ticket_type.php.md)
- [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md)
- [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md)
- [api/tickets/save_ticket_priority.php](/code/files/api/tickets/save_ticket_priority.php.md)
- [api/tickets/save_ticket_resolution_code.php](/code/files/api/tickets/save_ticket_resolution_code.php.md)
- [api/tickets/save_ticket_status.php](/code/files/api/tickets/save_ticket_status.php.md)
- [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md)

