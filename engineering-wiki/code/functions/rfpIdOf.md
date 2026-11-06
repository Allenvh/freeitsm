# rfpIdOf()

The RFP a child row belongs to. `$kind` names the table the id is for; every one carries rfp_id except section history (through its section).

```php
function rfpIdOf(PDO $conn, string $kind, int $id): ?int
```

Defined in [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md), line 91.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | which table the id is for: rfp, document, extracted, consolidated, conflict, section, framing or invited |
| `$id` | `int` |  | the row's id in that table |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 94 | `rfpIdOf` | read [rfps](/code/tables/rfps.md) |  |
| 95 | `rfpIdOf` | read [rfp_documents](/code/tables/rfp_documents.md) |  |
| 96 | `rfpIdOf` | read [rfp_extracted_requirements](/code/tables/rfp_extracted_requirements.md) |  |
| 97 | `rfpIdOf` | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 98 | `rfpIdOf` | read [rfp_conflicts](/code/tables/rfp_conflicts.md) |  |
| 99 | `rfpIdOf` | read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |
| 100 | `rfpIdOf` | read [rfp_document_sections](/code/tables/rfp_document_sections.md) |  |
| 101 | `rfpIdOf` | read [rfp_invited_suppliers](/code/tables/rfp_invited_suppliers.md) |  |

## Called from

- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpGate()](/code/functions/rfpGate.md)

