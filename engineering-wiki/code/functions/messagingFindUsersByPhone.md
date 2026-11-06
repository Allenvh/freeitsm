# messagingFindUsersByPhone()

Every active person whose phone or mobile is this number, compared digits-only so "+44 7700 900123" and "447700900123" agree. Returns user ids, deduplicated.

Scoped to the company the channel files tickets into (`$tenantId`): on a
multi-company install a person with no company belongs to the Default company,
as everywhere else (analystCanAccessUser()). A multi-company channel that
cannot say which company (triage, `$tenantId` null) matches NOBODY - guessing
across companies is exactly what must not happen. Single-company installs
search everyone.

Placeholders (any *@telegram.local) are never candidates: merging one chat into
another chat's placeholder is not "finding the person".

⚠️ Digits must be EQUAL. "07700 900123" (national) and "447700900123"
(international) do not match, because turning one into the other needs the
country, which we don't know. A missed match costs an analyst a click; a wrong
one hands someone else's tickets over. Known limitation, documented on the wiki.

A full scan rather than an indexed lookup: phone numbers are free text and
rarely normalised at entry, so SQL equality would miss real matches.

```php
function messagingFindUsersByPhone(PDO $conn, string $phone, ?int $tenantId): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 908.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$phone` | `string` |  | the number to look for, any format; under 7 digits matches nobody |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 914 | `messagingFindUsersByPhone` | read [users](/code/tables/users.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

