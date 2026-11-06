# ticketChecklistEmptyClosureMode()

'off' (default), 'warn', or 'block' — when closing a ticket with no SOP attached.

```php
function ticketChecklistEmptyClosureMode(PDO $conn, ?int $tenantId): string
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[tenantSetting()](/code/functions/tenantSetting.md)

## Called from

- [api/tickets/save_checklist_settings.php](/code/files/api/tickets/save_checklist_settings.php.md)
- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)
- [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) - in `ChecklistsService::assertClosureAllowed`, `ChecklistsService::recordClosureOverride`
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

