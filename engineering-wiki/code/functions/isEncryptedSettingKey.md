# isEncryptedSettingKey()

Should this system_settings key be encrypted at rest?

A RULE first, a list second. This used to be an allow-list of known secrets,
which fails open by construction: every new secret is stored in the clear until
somebody remembers to register it, and nothing anywhere complains. That is not a
hypothetical — problem_ai_api_key shipped that way (see the comment on the list
above), and auditing a live install while fixing F3 turned up five more nobody
had registered: csat_token_secret and the four *_cron_token values, all sitting
in plaintext. The cron tokens authenticate endpoints that are deliberately
reachable without a login, so they are bearer credentials.

Inverting it means a key called *_password / *_secret / *_token / *_api_key is
encrypted the moment it exists, and the failure mode of forgetting becomes
"encrypted something harmless" instead of "leaked something that mattered".

Masking is deliberately NOT inverted — see `isMaskedSettingKey()`. Encryption is
invisible to the user, so defaulting it on costs nothing; masking changes what an
administrator can see and copy (the cron tokens have to stay readable to build a
cron URL), so it stays an explicit, per-key decision.

Safe to switch on for values already stored in plaintext: `decryptValue()` passes
an unencrypted value straight through, so old rows keep reading and re-encrypt on
the next save. db_verify re-encrypts the existing ones in place.

```php
function isEncryptedSettingKey($key)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 192.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | a system_settings.setting_key |

## Calls

[isSecretSettingName()](/code/functions/isSecretSettingName.md)

## Called from

- [api/cmdb/get_ai_settings.php](/code/files/api/cmdb/get_ai_settings.php.md)
- [api/knowledge/get_email_settings.php](/code/files/api/knowledge/get_email_settings.php.md)
- [api/knowledge/send_share_email.php](/code/files/api/knowledge/send_share_email.php.md)
- [api/rfp-builder/get_ai_settings.php](/code/files/api/rfp-builder/get_ai_settings.php.md)
- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)
- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)
- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatGetSetting()](/code/functions/csatGetSetting.md)
- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneGetSettings()](/code/functions/intuneGetSettings.md)
- [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md) - in [rfpAiGetSettings()](/code/functions/rfpAiGetSettings.md)

