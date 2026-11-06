# rfpAiConsolidate()

Run Pass 2 (consolidate + categorise + detect conflicts) for an RFP.

Input is an array of associative arrays, each representing one row
from rfp_extracted_requirements joined with department info:
  id, requirement_text, requirement_type, source_quote, department_name

Returns the parsed JSON structure plus token usage. Caller is
responsible for inserting the categories / consolidated rows /
sources / conflicts; this function only calls the AI and logs.

Output:
  payload      array  Parsed { categories, consolidated_requirements, conflicts }
  tokens_in    int
  tokens_out   int
  cache_read   int|null
  cache_write  int|null
  duration_ms  int

```php
function rfpAiConsolidate(PDO $conn, int $rfpId, array $extractedRows): array
```

Defined in [includes/rfp_ai.php](/code/files/includes/rfp_ai.php.md), line 749.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rfpId` | `int` |  | the rfps id, for the log |
| `$extractedRows` | `array` |  | rfp_extracted_requirements rows: id, requirement_text, requirement_type, source_quote, department_name |

**Returns:** `array`

## Calls

[rfpAiCall()](/code/functions/rfpAiCall.md), [rfpAiLogAction()](/code/functions/rfpAiLogAction.md)

*Nothing calls this function by name.*

