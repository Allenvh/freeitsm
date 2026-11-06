# knowledgeTenantFilterForCompany()

Scope a Knowledge query to the articles a given COMPANY may see.

⚠️ READ THIS BEFORE "SIMPLIFYING" IT INTO `activeTenantFilter()`. ⚠️

The SQL looks almost identical to `activeTenantFilter()` and means something
materially different, because NULL means something different in Knowledge:

  tickets / assets / changes:  tenant_id IS NULL = "unassigned, treat as the
                               DEFAULT company's" — so a NULL row is visible
                               only while Default is the active company.
  knowledge_articles:          tenant_id IS NULL = "SHARED WITH EVERY COMPANY"
                               — an MSP's generic "how to reset your password"
                               serves every client, so NULL is visible to ALL.

Feed a non-Default company through `activeTenantFilter()` and every shared
article silently disappears from it. Hence this exists.

`$tenantId` is the company whose view we want (e.g. a web chat widget's), NOT an
analyst. NULL means "no company context": only shared articles are in scope,
which is the safe reading.

Returns ['', []] — no filtering — on a single-company install (invisible at
N=1), and on an install that has not run Database Verify yet, where the column
does not exist and every article is shared by definition.

```php
function knowledgeTenantFilterForCompany(PDO $conn, ?int $tenantId, string $alias = 'a'): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 969.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'a'` | the knowledge_articles alias in the calling query; '' for none |

**Returns:** array [sqlFragment, params]

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

## Called from

- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [knowledgeTenantFilter()](/code/functions/knowledgeTenantFilter.md)

