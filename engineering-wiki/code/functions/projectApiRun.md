# projectApiRun()

Run a service call and turn any failure into the UI's {success:false} shape.

```php
function projectApiRun(callable $fn): void
```

Defined in [includes/projects/api_bootstrap.php](/code/files/includes/projects/api_bootstrap.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `callable` |  | the work to run; a ServiceError or any other failure becomes a {success:false} reply |

**Returns:** `void`

## Calls

[projectApiFail()](/code/functions/projectApiFail.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/capacity.php](/code/files/api/projects/capacity.php.md)
- [api/projects/delete.php](/code/files/api/projects/delete.php.md)
- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [api/projects/list.php](/code/files/api/projects/list.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [api/projects/save.php](/code/files/api/projects/save.php.md)
- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [api/projects/stage_delete.php](/code/files/api/projects/stage_delete.php.md)
- [api/projects/stage_reorder.php](/code/files/api/projects/stage_reorder.php.md)
- [api/projects/stage_save.php](/code/files/api/projects/stage_save.php.md)
- [api/projects/task_assign.php](/code/files/api/projects/task_assign.php.md)
- [api/projects/task_create.php](/code/files/api/projects/task_create.php.md)
- [api/projects/templates.php](/code/files/api/projects/templates.php.md)
- [api/projects/tools.php](/code/files/api/projects/tools.php.md)

