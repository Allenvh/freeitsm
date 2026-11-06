# projectLinksReady()

Before Database Verification the tables are not there: no links, never an error. With `$kind`, that one kind's table (3.3.0) - so an upgrade that adds a kind (problem) hides only that kind until Verification, not every link.

```php
function projectLinksReady(PDO $conn, ?string $kind = null): bool
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `?string` | `null` | one link kind to check, e.g. 'problem'; null asks whether any kind is usable |

**Returns:** `bool`

## Calls

[projectLinkKinds()](/code/functions/projectLinkKinds.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinks()](/code/functions/projectLinks.md), [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkRemove()](/code/functions/projectLinkRemove.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md), [projectsLinkedTo()](/code/functions/projectsLinkedTo.md), [projectsPickableFor()](/code/functions/projectsPickableFor.md)

