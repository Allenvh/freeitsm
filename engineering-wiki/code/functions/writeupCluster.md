# writeupCluster()

Single-linkage clustering: grow a cluster outwards from a seed, pulling in anything close to ANY member rather than only to the seed.

`$items` must be pre-sorted by how good a SEED each one is (richest first), so a
cluster forms around its most writable ticket and inherits that subject as the
label. Each item needs an 'id' and whatever `$simFn` reads.

⚠️ THE TRANSITIVE STEP IS THE WHOLE POINT, and the harness is why. Comparing
only against the seed makes the result depend on which ticket happened to be
first. Four real VPN tickets scored: "keeps disconnecting every few minutes"
↔ "disconnecting again" 0.67, "disconnects constantly" ↔ "disconnecting
again" 0.67 — but the first against the second only 0.40, because one
mentions minutes and the other mentions home. Seeded on the wrong one, a
seed-only pass splits a genuine recurring question into fragments too small
to report, and the assistant stays silent about the thing it most needed to
say. Chaining outwards, they are one cluster of three, which is the truth.

Single linkage can over-chain in principle (A~B~C where A and C are
unrelated). Here the pairing bar is high and, in wording mode, two tokens must
match before any score is returned at all — and the harness carries a negative
control of six unrelated one-off tickets that must never end up in a cluster.

O(n²) and unapologetic: n is gap CANDIDATES inside a 90-day window — hundreds,
not millions — and a real clustering library is not worth a dependency here.

```php
function writeupCluster(array $items, callable $simFn, float $threshold): array
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 570.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$items` | `array` |  | candidates sorted best seed first; each needs an 'id' plus whatever `$simFn` reads |
| `$simFn` | `callable` |  | function(item, item): float - how alike two items are, 0-1 |
| `$threshold` | `float` |  | the lowest similarity that joins an item to a cluster |

**Returns:** `array`

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

