# searchCorpusTicketScope()

Turn an email/ticket-style tenant_id into the pair the corpus stores. Mirrors `ticketTenantFilter()`'s reading of NULL.

```php
function searchCorpusTicketScope(?int $tenantId): array
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{0:?int,1:string} [tenant_id, tenant_scope]

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [searchIndexDocument()](/code/functions/searchIndexDocument.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicket()](/code/functions/searchIndexTicket.md)

