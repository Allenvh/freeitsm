# apiKeyKnowledgeFilter()

The Knowledge counterpart of `apiKeyTenantFilter()`.

⚠️ DO NOT REPLACE THIS WITH `apiKeyTenantFilter()`. ⚠️

It exists because NULL means the opposite in Knowledge. For tickets/problems,
`tenant_id IS NULL` = "belongs to the Default company", so the generic filter
only lets NULL rows through when the key's scope happens to include Default.
In Knowledge, `tenant_id IS NULL` = "SHARED WITH EVERY COMPANY" — an MSP's
generic "how to reset your password" serves every client — so shared articles
must be visible to EVERY key, whatever its scope.

Run a Knowledge query through the generic filter and every shared article
silently vanishes for any key not scoped to Default. The SQL looks nearly
identical; the meaning is not. See `knowledgeTenantFilterForCompany()` in
includes/tenancy.php for the session-side twin.

```php
function apiKeyKnowledgeFilter(PDO $conn, array $apiKey, string $alias = 'a'): array
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 330.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'a'` | the alias the query gives the knowledge_articles table |

**Returns:** array [sqlFragment, params]

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md)

*Nothing calls this function by name.*

