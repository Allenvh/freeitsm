# writeupSchemaReady()

Does this install have the gap tables yet?

⚠️ Load-bearing. An install that pulls this update but has not yet run
Database Verify must degrade to "the assistant has nothing to show you", NOT
to an "Unknown column" fatal on the Knowledge page. Same gate, same reason, as
`snoozeSchemaReady()` in the tickets inbox — that one would have served an empty
inbox, this one would take out the whole Knowledge module.

Cached per request: called on every render and on every row of a batch.

```php
function writeupSchemaReady(PDO $conn): bool
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)
- [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md)
- [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md)
- [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)

