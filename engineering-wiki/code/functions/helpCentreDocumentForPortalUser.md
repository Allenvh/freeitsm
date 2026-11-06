# helpCentreDocumentForPortalUser()

The document a portal requester may download, or null.

Served if ANY article it is shown on is one this requester may read - one
document can be attached to several articles, so every link is tried, not
just the first. Null for every refusal alike (missing, deleted, hidden, an
article they cannot read, attached only to some other kind of record), so
the endpoint's 404 cannot be used to learn which.

```php
function helpCentreDocumentForPortalUser(PDO $conn, int $userId, int $documentId): ?array
```

Defined in [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md), line 116.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user's users.id (from the session, never the request) |
| `$documentId` | `int` |  | documents.id from the request |

**Returns:** ?array{id:int,kind:string,title:string,storage_key:?string,original_name:?string,external_url:?string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 119 | `helpCentreDocumentForPortalUser` | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md) |  |

## Calls

[documentHelpCentreReady()](/code/functions/documentHelpCentreReady.md), [portalCanReadArticle()](/code/functions/portalCanReadArticle.md)

## Called from

- [api/self-service/get_article_document.php](/code/files/api/self-service/get_article_document.php.md)

