# api/myaccount/toggle_trust_device.php

> API endpoint - 47 lines

API: Toggle trusted device preference for current analyst POST - Flips trust_device_enabled on/off. If disabling, clears all trusted devices.

## The endpoint

| | |
|---|---|
| URL | `api/myaccount/toggle_trust_device.php` |
| Protected by | Signed-in analyst (session check) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [analysts](/code/tables/analysts.md) |  |
| 34 |  | **update** [analysts](/code/tables/analysts.md) (`trust_device_enabled`) |  |
| 39 |  | **delete** [trusted_devices](/code/tables/trusted_devices.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

