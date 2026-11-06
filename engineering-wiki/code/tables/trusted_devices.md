# trusted_devices

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `device_token_hash` | `VARCHAR(255) NOT NULL` |  |
| `user_agent` | `VARCHAR(500) NULL` |  |
| `ip_address` | `VARCHAR(45) NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `expires_datetime` | `DATETIME NOT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/myaccount/toggle_trust_device.php](/code/files/api/myaccount/toggle_trust_device.php.md):39 |  | delete |  |  |
| [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md):189 |  | insert | `analyst_id`, `device_token_hash`, `user_agent`, `ip_address`, `created_datetime`, `expires_datetime` |  |
| [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md):196 |  | delete |  |  |

