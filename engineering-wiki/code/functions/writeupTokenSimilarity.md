# writeupTokenSimilarity()

Overlap coefficient of two token sets — |A ∩ B| / min(|A|, |B|) — with a floor of two shared tokens.

⚠️ NOT Jaccard, and the harness is why. Jaccard divides by the UNION, so it
punishes a subject for the words the other one happens not to have: "VPN keeps
disconnecting every few minutes" and "VPN disconnecting again" share
everything that matters and still only score 0.29, because one of them
mentions minutes. Real subject lines vary wildly in length and filler, so the
union is the wrong denominator for the question being asked, which is "is the
shorter of these two entirely contained in the longer?".

The two-token floor is what makes that safe. The overlap coefficient alone
scores any pair sharing a single word out of a two-word subject at 0.5, so
"Broken window in the server room" and "Request access to the CAD licence
server" would cluster on the word "server". Requiring two shared tokens costs
nothing real — no genuine recurring question is identified by one word — and
removes the whole class of false cluster.

```php
function writeupTokenSimilarity(array $a, array $b): float
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 521.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | a token list from `writeupSubjectTokens()` |
| `$b` | `array` |  | another token list from `writeupSubjectTokens()` |

**Returns:** `float`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

