# api/system/generate_encryption_key.php

> API endpoint - 55 lines

API: Generate encryption key POST - Generates a new AES-256 encryption key and writes it to the key file

## The endpoint

| | |
|---|---|
| URL | `api/system/generate_encryption_key.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

