# integrationsBodyToText()

An email body reduced to text worth putting in an issue description.

⚠️ `strip_tags()` alone is NOT enough, and this is not theoretical — the first
real ticket tried produced a Jira description containing several hundred lines
of the email's CSS. strip_tags removes the TAGS but keeps the CONTENT of
<style> and <script>, so a marketing-styled email dumps its entire stylesheet
into the description.

So: kill those elements outright, turn block ends into newlines so paragraphs
survive, then collapse the whitespace an HTML mail is full of (&nbsp; runs,
tabs, CRLF pairs).

`$maxChars` is a sanity cap, not a policy — a 200KB newsletter helps nobody, and
a dev reading the issue wants the request, not the footer. The link back to
the ticket is always there for the full thread.

```php
function integrationsBodyToText(
    ?string $raw,
    string $bodyType = 'text',
    int $maxChars = 8000
): string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 257.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the email body as stored; null or '' gives '' |
| `$bodyType` | `string` | `'text'` | 'html' or 'text'; a body containing <html or <div is treated as HTML whatever this says |
| `$maxChars` | `int` | `8000` | the most characters kept before a "truncated" note is appended |

**Returns:** `string`

## Called from

- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)

