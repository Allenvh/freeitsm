# projectsPickableFor()

The picker on the record's page: live projects this analyst may CHANGE, in the record's company (for company records), not already linked, matching `$q`. At most 20.

```php
function projectsPickableFor(
    PDO $conn,
    ActorContext $ctx,
    string $kind,
    int $targetId,
    string $q
): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 449.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the record's link kind, e.g. 'contract' |
| `$targetId` | `int` |  | the record's id |
| `$q` | `string` |  | the search text; matches the project name, or its id by the digits in it |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 457 | `projectsPickableFor` | read [projects](/code/tables/projects.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [projectCanChange()](/code/functions/projectCanChange.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkKinds()](/code/functions/projectLinkKinds.md), [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md), [projectLinkTenantOf()](/code/functions/projectLinkTenantOf.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)

