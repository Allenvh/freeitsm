# writeupStem()

Crude suffix stemmer, so that different tenses of the same complaint match.

⚠️ NOT cosmetic — the harness in tests/knowledge-gaps caught this. Four tickets
saying "VPN keeps disconnecting", "VPN disconnects constantly", "vpn connection
drops" and "VPN disconnecting again" failed to cluster at all, purely because
"disconnects" and "disconnecting" are different strings. That is the single
most common way a real service desk phrases the same question twice, so
without this the free engine misses exactly the gaps it exists to find.

Deliberately not a full Porter implementation: this runs over subject lines of
a dozen words, and the failure mode of over-stemming (two unrelated words
colliding) is much worse here than under-stemming.

```php
function writeupStem(string $w): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 432.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$w` | `string` |  | one lower-case word from a subject line |

**Returns:** `string`

## Called from

- [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md) - in [writeupSubjectTokens()](/code/functions/writeupSubjectTokens.md)

