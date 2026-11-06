# contract_assets

Tickets ↔ assets (discussion #57). Two nullable creator columns because a link can be made by an analyst or by a portal user, and they are different tables. See freeitsm.sql for the full reasoning. Assets covered by a contract (#106). `reference` is the link's own text - a phone number, a line ID - because it describes the asset's place in THIS contract rather than the asset itself.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `contract_id` | `INT NOT NULL` |  |
| `asset_id` | `INT NOT NULL` |  |
| `reference` | `VARCHAR(190) NULL` |  |
| `linked_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md):179 | `contractAssetLink` | insert | `contract_id`, `asset_id`, `reference`, `linked_by_id`, `created_datetime` |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md):203 | `contractAssetSetReference` | update | `reference` |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md):217 | `contractAssetUnlink` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md) | L225 |  |
| [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md) | L62 |  |
| [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) | contractAssetSelect L64, contractsForAsset L111, contractAssetLink L184, contractAssetLoad L228, contractAssetSearch L263 |  |
| [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) | ContractsService::assertMovable L347 |  |

