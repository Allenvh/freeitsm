# settingsGetDecrypted()

Read system_settings values with any encryption already undone.

⚠️ WHY THIS EXISTS. Inverting the secret rule (isEncryptedSettingKey() above) was
the right call — it stopped new secrets shipping in plaintext because nobody
remembered to register them — but it silently widened WHAT IS CIPHERTEXT ON DISK,
and every place still doing a bare `SELECT setting_value` kept handing that
ciphertext to whatever it fed. Two features broke that way and neither threw an
error: the webhook cron URL printed `?token=ENC%3A…` for an admin to copy, and the
knowledge base authenticated to SMTP with the literal string `ENC:…`. Both were
found by Erlend Volden on his re-review, not by us, and the reason we missed them
is that the test asserted the value in the DATABASE was ciphertext — which is
exactly what a broken read path also looks like. Assert round-trips, not storage.

So: anything that reads a setting in order to USE it goes through here. `decryptValue()`
passes plaintext straight through, so this is safe on a row that has not been migrated
yet, on a value the rule does not cover, and on a re-run.

⚠️ NOT for the settings UI. A screen that shows a secret to an administrator wants
`maskSecret()` on top of this; a screen that merely says "configured" should not
decrypt at all.

```php
function settingsGetDecrypted(PDO $conn, array $keys): array
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 360.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$keys` | `array` |  | the system_settings.setting_key values to load; an empty list returns [] |

**Returns:** array<string,string> key => plaintext value, missing keys absent

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 364 | `settingsGetDecrypted` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md)

## Called from

- [includes/encryption.php](/code/files/includes/encryption.php.md) - in [settingGetDecrypted()](/code/functions/settingGetDecrypted.md)
- [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)

