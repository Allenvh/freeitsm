# documentLinkHelpCentreDefault()

The show_in_help_centre value a NEW link to this kind of record starts with: 1 for a knowledge article (attaching a document to a customer-facing article publishes it, which is what people expect), 0 for everything else.

TRAP: new links only. Never backfill existing rows to 1 - they were attached
  before anybody was told that publishes them (see the column's TRAP in
  includes/db_verify_schema.php).

```php
function documentLinkHelpCentreDefault(string $parentType): int
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 796.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$parentType` | `string` |  | the document_links.parent_type being written, e.g. 'knowledge_article' |

**Returns:** `int`

## Called from

- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentInsertLink()](/code/functions/documentInsertLink.md)

