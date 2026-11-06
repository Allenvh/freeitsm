# ticket_assets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `created_by_analyst_id` | `INT NULL` |  |
| `created_by_user_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md):169 |  | insert | `ticket_id`, `asset_id`, `created_datetime`, `created_by_user_id` |  |
| [api/tickets/delete_ticket_asset.php](/code/files/api/tickets/delete_ticket_asset.php.md):41 |  | delete |  |  |
| [api/tickets/delete_ticket_asset.php](/code/files/api/tickets/delete_ticket_asset.php.md):47 |  | delete |  |  |
| [api/tickets/save_ticket_asset.php](/code/files/api/tickets/save_ticket_asset.php.md):74 |  | insert | `ticket_id`, `asset_id`, `created_datetime`, `created_by_analyst_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_tickets.php](/code/files/api/assets/get_asset_tickets.php.md) | L50, L75 |  |
| [api/system/debug-tools/D002_delete_ticket.php](/code/files/api/system/debug-tools/D002_delete_ticket.php.md) | L344 |  |
| [api/tickets/delete_ticket_asset.php](/code/files/api/tickets/delete_ticket_asset.php.md) | L35 |  |
| [api/tickets/get_ticket_assets.php](/code/files/api/tickets/get_ticket_assets.php.md) | L39 |  |
| [api/tickets/search_linkable_assets.php](/code/files/api/tickets/search_linkable_assets.php.md) | L58 |  |

