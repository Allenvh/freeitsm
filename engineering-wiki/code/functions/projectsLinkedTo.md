# projectsLinkedTo()

The projects one record (asset, change, ticket, contract, CI, article) is linked to that this analyst may see. Empty - never an error - when they cannot open Projects, the other module, or the record, so a page can call it unconditionally.

```php
function projectsLinkedTo(PDO $conn, ActorContext $ctx, string $kind, int $targetId): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 426.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the record's link kind, e.g. 'asset' |
| `$targetId` | `int` |  | the record's id |

**Returns:** `array`

## Calls

[projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkKinds()](/code/functions/projectLinkKinds.md), [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)

