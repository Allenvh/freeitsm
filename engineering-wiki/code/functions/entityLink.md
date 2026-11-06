# entityLink()

The canonical deep link for a record, or NULL when the type has no page.

NULL is a real answer, not a failure: a lookup row (a ticket status, an
impact level) has no page of its own to open. Callers should render such a
reference as plain text rather than a dead anchor.

```php
function entityLink(string $type, int $id): ?string
```

Defined in [includes/entity_links.php](/code/files/includes/entity_links.php.md), line 47.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | One of the keys below. Unknown types return NULL. |
| `$id` | `int` |  | The record's own id — never a display reference such as a ticket NUMBER. The forms approvals list passed a number and produced a link that silently did nothing. |

**Returns:** string|null BASE_URL-relative path, e.g. 'tickets/?ticket_id=42'

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tasks/get.php](/code/files/api/tasks/get.php.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkDescribe()](/code/functions/domainLinkDescribe.md), [domainLinkDomainRows()](/code/functions/domainLinkDomainRows.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleTickets()](/code/functions/peopleTickets.md), [peopleAssets()](/code/functions/peopleAssets.md), [peopleContracts()](/code/functions/peopleContracts.md), [peopleDomains()](/code/functions/peopleDomains.md), [peopleProjects()](/code/functions/peopleProjects.md), [peopleForms()](/code/functions/peopleForms.md), [peopleContractorTasks()](/code/functions/peopleContractorTasks.md), [peopleSupplierContracts()](/code/functions/peopleSupplierContracts.md), [peopleSupplierAssets()](/code/functions/peopleSupplierAssets.md), [peopleSupplierDomains()](/code/functions/peopleSupplierDomains.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkDescribe()](/code/functions/projectLinkDescribe.md), [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md), [projectUnapprovedChanges()](/code/functions/projectUnapprovedChanges.md)
- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetAssets()](/code/functions/projectTargetAssets.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [entityRecentTrail()](/code/functions/entityRecentTrail.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreview()](/code/functions/recordPreview.md)
- [includes/services/notifications.php](/code/files/includes/services/notifications.php.md) - in `NotificationsService::linkFor`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::lessonToKnowledge`, `ProjectToolsService::issueToTicket`, `ProjectToolsService::raid`

