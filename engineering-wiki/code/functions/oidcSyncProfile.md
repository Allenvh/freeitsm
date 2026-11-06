# oidcSyncProfile()

Copy profile details from the identity provider onto a local account.

🔴 Call this ONLY after the account has passed every access check (active,
assigned to this provider). Writing first and refusing afterwards lets a
provider that is about to be turned away edit someone else's record.

Only runs when the provider's profile_sync_mode is 'always'. 'initial' is
handled at creation time (oidcCreateAnalyst / the requester INSERT), and
'never' does nothing.

```php
function oidcSyncProfile(
    PDO $conn,
    array $provider,
    string $table,
    array $record,
    array $claims,
    string $name
): void
```

Defined in [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), line 363.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the sso_providers row the user signed in with; only profile_sync_mode is read |
| `$table` | `string` |  | 'analysts' or 'users' - never user input |
| `$record` | `array` |  | the local account row as loaded (analysts or users), including id and the current profile values |
| `$claims` | `array` |  | the validated ID token claims, topped up from userinfo when sync is on |
| `$name` | `string` |  | the display name worked out from the claims; empty means leave the name alone |

**Returns:** `void`

## Calls

[oidcExtractProfileAttributes()](/code/functions/oidcExtractProfileAttributes.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md)

