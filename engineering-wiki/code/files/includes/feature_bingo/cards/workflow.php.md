# includes/feature_bingo/cards/workflow.php

> PHP - 523 lines

Feature Bingo cards - Workflows: having workflows, the trigger families, the action types, webhook formats, scheduled triggers and the module's settings. See includes/feature_bingo.php for the format.

workflows.trigger_event is one event name; workflows.actions is JSON written by
`json_encode()` (api/workflow/save.php, create_from_template.php, api/v1), so an
action appears as "type":"send_email" with no spaces. Only ACTIVE workflows
that the demo importer did not create count.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 84 |  | read [system_settings](/code/tables/system_settings.md) |  |

