# api/settings/save_system_settings.php

> API endpoint - 101 lines

API Endpoint: Save system settings

A GENERIC key/value writer, used by the five settings tabs that have no endpoint of
their own (Assets warranty/vCenter/Intune, Tickets general, System colours/security/SSO).

Because it is generic, it CANNOT be authorised with a guard at the top of the file —
one guard would have to cover five different callers with five different audiences.
It used to check only that you were logged in, which meant any analyst could post any
key and rewrite vCenter/Intune credentials, the SSO config, or the brute-force lockout
policy. So authorisation is done PER KEY instead: includes/settings_keys.php says who
owns each key, and a key nobody owns is refused outright.

## The endpoint

| | |
|---|---|
| URL | `api/settings/save_system_settings.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/settings_keys.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 81 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 86 |  | **update** [system_settings](/code/tables/system_settings.md) (`setting_value`, `updated_datetime`) |  |
| 89 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/settings_keys.php](/code/files/includes/settings_keys.php.md)

**Calls:** [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [isMaskedNoChangeValue()](/code/functions/isMaskedNoChangeValue.md), [isMaskedSettingKey()](/code/functions/isMaskedSettingKey.md), [settingKeyOwner()](/code/functions/settingKeyOwner.md)

