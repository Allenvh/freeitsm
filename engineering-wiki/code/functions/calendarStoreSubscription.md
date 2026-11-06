# calendarStoreSubscription()

Records an analyst's change-notification subscription (id, expiry, secret) and clears any stored error, since subscribing succeeded.

```php
function calendarStoreSubscription(
    PDO $conn,
    int $analystId,
    string $id,
    string $expires,
    ?string $secret
): void
```

Defined in [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md), line 503.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `string` |  | the provider's subscription id |
| `$expires` | `string` |  | when the subscription lapses, as the provider returned it (naive local datetime) |
| `$secret` | `?string` |  | the client state Graph echoes back on each notification; kept unchanged on a renewal |

**Returns:** `void`

**Writes:** calendar_enrolments(subscription_id, subscription_expires, subscription_secret, last_error)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 512 | `calendarStoreSubscription` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`subscription_id`, `subscription_expires`, `subscription_secret`, `last_error`) |  |

## Called from

- [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md) - in [calendarSyncEnsureSubscription()](/code/functions/calendarSyncEnsureSubscription.md)

