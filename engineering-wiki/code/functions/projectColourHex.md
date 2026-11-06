# projectColourHex()

The first colour of a project's palette key - for a dot or a chip outside the module.

```php
function projectColourHex(?string $key): string
```

Defined in [includes/projects/methodologies.php](/code/files/includes/projects/methodologies.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `?string` |  | the project's palette key from projects.colour, e.g. 'coral'; unknown or null gives coral |

**Returns:** `string`

## Calls

[projectColours()](/code/functions/projectColours.md)

## Called from

- [api/tasks/get.php](/code/files/api/tasks/get.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md)

