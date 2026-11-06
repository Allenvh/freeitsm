# asset_physical_disks

The physical drives themselves, as opposed to the lettered volumes above.  WHY A SECOND TABLE. asset_disks describes a VOLUME — a drive letter, a label, how full it is — and is rewritten on every report. A physical disk is a different thing with a different lifetime: one NVMe stick can carry C: and D:, and one volume can span two disks. Squeezing both into asset_disks would mean half the columns being NULL in every row and no honest answer to "how many drives are in this machine".  🔑 THE SERIAL IS THE POINT. The agent has collected model/serial/size/ media/interface since the beginning and the server threw all of it away — asset_disks had nowhere to put it. It is what you need when a drive fails under warranty, and what an auditor asks for when a machine is disposed of (discussion #97).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `asset_id` | `INT NOT NULL` |  |
| `model` | `VARCHAR(255) NULL` |  |
| `serial` | `VARCHAR(100) NULL` |  |
| `size_bytes` | `BIGINT NULL` |  |
| `media_type` | `VARCHAR(100) NULL` |  |
| `interface_type` | `VARCHAR(50) NULL` | Not `interface`: harmless in MySQL, but a reserved word in enough of the places this name gets copied to that the suffix is cheap insurance. |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):335 |  | delete |  |  |
| [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md):336 |  | insert | `asset_id`, `model`, `serial`, `size_bytes`, `media_type`, `interface_type` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_disks.php](/code/files/api/assets/get_asset_disks.php.md) | L58 |  |
| [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) | apiAssetPhysicalDisksList L544 |  |
| [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md) | assetDiskMatchCount L110 |  |

