# searchIndexTicketAttachments()

Index the readable text of a ticket's attachments (discussion #53, tier 1).

⚠️ EXTRACTION IS CACHED IN `attachment_text`, WHICH IS THE POINT.
`searchIndexTicket()` reindexes the WHOLE ticket on every event, so without a
durable store a ticket with ten attachments would re-open and re-unzip all ten
every time somebody added a note. The row also survives a corpus rebuild, so
rebuilding the index never means re-reading a single file — which for tier 2's
PDFs and OCR would be hours of work and, with a paid extractor, a bill.

```php
function searchIndexTicketAttachments(
    PDO $conn,
    int $ticketId,
    ?int $tenantId,
    string $scope,
    int $maxBody
): int
```

Defined in [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `string` |  | the ticket's corpus tenant_scope, SEARCH_SCOPE_DEFAULT or SEARCH_SCOPE_COMPANY |
| `$maxBody` | `int` |  | the most characters of extracted text kept per attachment; longer text is truncated |

**Returns:** int corpus rows written

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `searchIndexTicketAttachments` | read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md), read [attachment_text](/code/tables/attachment_text.md) |  |
| 272 | `searchIndexTicketAttachments` | **insert** [attachment_text](/code/tables/attachment_text.md) (`attachment_id`, `status`, `extractor`, `extracted_text`, `chars`, `extracted_datetime`) |  |

## Calls

[attTextExtractFile()](/code/functions/attTextExtractFile.md), [attTextSupports()](/code/functions/attTextSupports.md), [searchCorpusUpsert()](/code/functions/searchCorpusUpsert.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaExtract()](/code/functions/tikaExtract.md), [tikaHandles()](/code/functions/tikaHandles.md)

## Called from

- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicket()](/code/functions/searchIndexTicket.md)

