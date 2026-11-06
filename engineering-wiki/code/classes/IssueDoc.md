# IssueDoc

> Class in [includes/integrations/IssueDoc.php](/code/files/includes/integrations/IssueDoc.php.md), line 38

IssueDoc — one description/comment, written once, rendered per provider.

The problem this solves: every issue tracker wants a different body format.
Jira Cloud's v3 API wants ADF (Atlassian Document Format — a nested JSON
document). Jira Data Center's v2 API wants wiki markup. GitHub and GitLab want
Markdown. Azure DevOps wants HTML. If core built a string, every one of those
would need its own string-building code and they would drift.

So core never builds a string. It builds an IssueDoc — an ordered list of
blocks — and the connector asks for the shape it needs:

    `$doc` = (new IssueDoc)
        ->heading('Raised in FreeITSM')
        ->para('Ticket ', IssueDoc::link(`$url`, 'SD-1042'), ' · Jane Doe (Acme)')
        ->rule()
        ->para(`$ticket`['description']);

    `$doc`->toAdf();        // Jira Cloud   (array, JSON-encode it)
    `$doc`->toWikiMarkup(); // Jira DC
    `$doc`->toMarkdown();   // GitHub / GitLab
    `$doc`->toHtml();       // Azure DevOps

The renderers live HERE rather than in each connector on purpose: a new
provider almost always reuses one of these four, so adding GitHub is
"renderDoc() returns `$doc`->toMarkdown()" and not a new renderer. That is what
makes the connector-per-provider cost small.

Deliberately small — heading, paragraph, link, bullet list, code block, rule.
This is not a rich-text format. It is enough to make a readable issue and no
more; anything bigger becomes a second sanitiser to keep safe.

⚠️ Text is always treated as PLAIN TEXT and escaped for the target format.
Never pass HTML in and expect it to survive — pass the text, use the blocks.

## Methods

| Method | What it does |
|---|---|
| [link()](#link) | A link, for use inside `para()`. Returns the inline marker `para()` understands. |
| [heading()](#heading) | Adds a heading block; blank text is ignored. |
| [para()](#para) | A paragraph of inline parts. Each part is either a plain string or the array returned by self::link(). Empty parts are dropped; a paragraph that ends up with nothing in it is not added at all (an empty paragraph is invalid in ADF, and pointless everywhere else). |
| [bullets()](#bullets) | Adds a bullet list, dropping blank items; nothing is added if every item is blank. |
| [code()](#code) | Adds a code block; an empty string is ignored. |
| [rule()](#rule) | Adds a horizontal rule, unless the document is empty or already ends with one. |
| [isEmpty()](#isempty) | True when no block has been added. |
| [blocks()](#blocks) | The raw block list. For tests and for a connector that needs its own renderer. |
| [toAdf()](#toadf) | Atlassian Document Format (Jira Cloud v3). Returns the PHP array — the caller JSON-encodes it, because it is usually one field inside a bigger request body. |
| [adfInlines()](#adfinlines) | Converts a paragraph's parts to ADF text nodes, giving links a link mark. |
| [toMarkdown()](#tomarkdown) | GitHub, GitLab. |
| [toWikiMarkup()](#towikimarkup) | Jira Data Center / Server (v2 API). |
| [toHtml()](#tohtml) | Azure DevOps (System.Description is HTML). |
| [toPlainText()](#toplaintext) | Plain text — for the escalate preview, and for logs. |
| [h()](#h) | HTML-escapes text for `toHtml()`. |
| [mdEscape()](#mdescape) | Markdown: neutralise the characters that would otherwise turn a ticket's text into formatting. A requester writing "use the * wildcard" must not produce italics, and "[see notes]" must not look like a broken link. |
| [wikiEscape()](#wikiescape) | Jira wiki markup: the same idea, different character set. |

### link

*public static* - line 57

A link, for use inside `para()`. Returns the inline marker `para()` understands.

```php
function link(string $href, string $text): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$href` | `string` |  | the link's URL |
| `$text` | `string` |  | the link text, plain |

**Called from:** [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

### heading

*public* - line 67

Adds a heading block; blank text is ignored.

```php
function heading(string $text): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the heading, plain text |

### para

*public* - line 83

A paragraph of inline parts. Each part is either a plain string or the array returned by self::link(). Empty parts are dropped; a paragraph that ends up with nothing in it is not added at all (an empty paragraph is invalid in ADF, and pointless everywhere else).

```php
function para(...$parts): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `...$parts` |  |  | plain strings and `link()` arrays, in reading order |

### bullets

*public* - line 109

Adds a bullet list, dropping blank items; nothing is added if every item is blank.

```php
function bullets(array $items): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$items` | `array` |  | the list items, plain text |

### code

*public* - line 127

Adds a code block; an empty string is ignored.

```php
function code(string $text): self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the code, kept verbatim |

### rule

*public* - line 136

Adds a horizontal rule, unless the document is empty or already ends with one.

```php
function rule(): self
```

### isEmpty

*public* - line 147

True when no block has been added.

```php
function isEmpty(): bool
```

### blocks

*public* - line 153

The raw block list. For tests and for a connector that needs its own renderer.

```php
function blocks(): array
```

### toAdf

*public* - line 168

Atlassian Document Format (Jira Cloud v3). Returns the PHP array — the caller JSON-encodes it, because it is usually one field inside a bigger request body.

⚠️ ADF rejects empty text nodes and empty paragraphs, which is why the
builders above refuse to create them.

```php
function toAdf(): array
```

### adfInlines

*private* - line 214

Converts a paragraph's parts to ADF text nodes, giving links a link mark.

```php
function adfInlines(array $inlines): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$inlines` | `array` |  | a paragraph's parts as `para()` stored them - strings and `link()` arrays |

### toMarkdown

*public* - line 232

GitHub, GitLab.

```php
function toMarkdown(): string
```

### toWikiMarkup

*public* - line 267

Jira Data Center / Server (v2 API).

```php
function toWikiMarkup(): string
```

### toHtml

*public* - line 302

Azure DevOps (System.Description is HTML).

```php
function toHtml(): string
```

### toPlainText

*public* - line 337

Plain text — for the escalate preview, and for logs.

```php
function toPlainText(): string
```

### h

*private* - line 376

HTML-escapes text for `toHtml()`.

```php
function h(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | plain text to escape |

### mdEscape

*private* - line 388

Markdown: neutralise the characters that would otherwise turn a ticket's text into formatting. A requester writing "use the * wildcard" must not produce italics, and "[see notes]" must not look like a broken link.

```php
function mdEscape(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | plain text to escape |

### wikiEscape

*private* - line 398

Jira wiki markup: the same idea, different character set.

```php
function wikiEscape(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | plain text to escape |

