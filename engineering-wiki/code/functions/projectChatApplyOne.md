# projectChatApplyOne()

One proposal, through the service a person would have used.

```php
function projectChatApplyOne(PDO $conn, ActorContext $ctx, int $pid, string $type, array $a): void
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 541.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$pid` | `int` |  | the project id the change is made in |
| `$type` | `string` |  | the proposal type, e.g. 'stage', 'task', 'raid', 'member' |
| `$a` | `array` |  | the proposal's arguments as the model gave them (untrusted - the services validate) |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 546 | `projectChatApplyOne` | read [project_stages](/code/tables/project_stages.md) |  |
| 550 | `projectChatApplyOne` | read [project_stages](/code/tables/project_stages.md) |  |
| 557 | `projectChatApplyOne` | read [tasks](/code/tables/tasks.md) |  |
| 561 | `projectChatApplyOne` | read [tasks](/code/tables/tasks.md) |  |
| 637 | `projectChatApplyOne` | read [project_roles](/code/tables/project_roles.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectToolDefinitions()](/code/functions/projectToolDefinitions.md)

## Called from

- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatApply()](/code/functions/projectChatApply.md)

