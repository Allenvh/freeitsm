# knowledgeNodePermits()

Does this node let this viewer through?

Open (is_restricted = 0)       yes, unless a DENY names one of their principals
  Restricted (is_restricted = 1) only if a GRANT names one

The polarity comes from the OBJECT, so an allow and a deny can never both be
present and there is no precedence rule to apply. Note the two directions:
on an Open node ANY matching row excludes them (deny is absolute), on a
Restricted node ANY matching row admits them (grants are additive). Different
directions, each unambiguous, both failing safe.

⚠️ A Restricted node with NO rows admits NOBODY. That is correct rather than a
bug — it is "restricted to nobody" — and it is precisely the state the
administrator floor exists to recover from.

```php
function knowledgeNodePermits(
    string $type,
    int $id,
    int $isRestricted,
    array $acl,
    array $principals
): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 659.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | 'folder' or 'article' |
| `$id` | `int` |  | the folder or article id |
| `$isRestricted` | `int` |  | 1 for a Restricted node (grants admit), 0 for an Open one (denies exclude) |
| `$acl` | `array` |  | the access rows from `knowledgeAclIndex()`, [type][object_id]['principal:id' => true] |
| `$principals` | `array` |  | the viewer's 'type:id' strings from `knowledgeViewerPrincipals()` |

**Returns:** `bool`

## Called from

- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md), [knowledgeFolderReadable()](/code/functions/knowledgeFolderReadable.md)

