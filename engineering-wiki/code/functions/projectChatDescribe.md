# projectChatDescribe()

One line a person can read for a proposal (shown on the card).

```php
function projectChatDescribe(string $type, array $a): string
```

Defined in [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the proposal type: the tool name without 'propose_', e.g. 'task' or 'raid' |
| `$a` | `array` |  | the proposal's arguments, plus the _label and _supplier names looked up for the card |

**Returns:** `string`

## Calls

[t()](/code/functions/t.md)

## Called from

- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md)

