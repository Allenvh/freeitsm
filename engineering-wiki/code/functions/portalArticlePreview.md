# portalArticlePreview()

Turn an article body into a short plain-text preview.

`strip_tags()` removes TAGS but keeps their CONTENTS, so a <script> or <style>
block spills its source into the preview as readable text ("…sign in.
alert(1)"). Those elements are dropped whole first. Not a security control —
the preview is escaped on render — just correctness.

`strip_tags()` also leaves NO separator where a tag was, so "<h3>Password
Reset</h3><p>You can…" comes out as "Password ResetYou can…". A space is
pushed in front of every tag first, then the run is collapsed — which keeps
`strip_tags()` (and its tolerance of malformed markup) rather than swapping in
a hand-rolled tag regex.

The caller cuts the body in SQL (LEFT(body, 400)), and that cut is in HTML
characters - so the plain text left over is usually far SHORTER than the
limit, and the length check below never fired: previews just stopped
mid-word ("Close ot") with nothing to say there was more. `$wasCut` is the
caller telling us the body really was longer; the half-word (and any
half-entity) at the end is then dropped and an ellipsis added.

```php
function portalArticlePreview(?string $body, int $length = 400, bool $wasCut = false): string
```

Defined in [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `?string` |  | the article body HTML; null gives '' |
| `$length` | `int` | `400` | the most characters kept before an ellipsis is added |
| `$wasCut` | `bool` | `false` | true when `$body` is the start of a longer article (CHAR_LENGTH(body) > the `LEFT()` taken) |

**Returns:** `string`

## Called from

- [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md)

