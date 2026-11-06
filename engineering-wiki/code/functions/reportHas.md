# reportHas()

Is there anything in this part of the report?

🔴 AN EMPTY LIST IS "I DID NOT FIND OUT", NOT "THERE IS NOTHING THERE".
Every block below wipes the asset's rows and reinserts, which is right when
the agent has told us what the machine currently has. It was wrong when it
had not: the DELETE ran unconditionally while the INSERT was guarded, so a
report carrying no disks emptied the table and left it empty — a WMI blip
taking 226 device rows with it, silently, until the next good run. Any
client holding an API key could do the same with `{"disks":{}}`.

Skipping the wipe is safe because the opposite case barely exists: a working
machine that can reach this endpoint has at least one volume, one drive and
one network adapter. Stale data outliving a removed disk by one run is a far
smaller problem than an inventory that empties itself.

Silence is the other half of the bug, so a skipped section is NAMED in the
response rather than looking like a successful sync of nothing.

```php
function reportHas($value): bool
```

Defined in [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), line 277.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | one section of the report, e.g. `$data`['disks']; only a non-empty array counts |

**Returns:** `bool`

## Called from

- [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md)

