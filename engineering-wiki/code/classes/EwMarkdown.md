# EwMarkdown

> Class in [includes/engineering_wiki/markdown.php](/code/files/includes/engineering_wiki/markdown.php.md), line 24

Engineering wiki - a small Markdown renderer written for the wiki, so the pages look and read like GitHub without pulling in a library.

What it understands (the GitHub-flavoured subset the wiki uses):
  # headings (with GitHub-style anchors), paragraphs, **bold**, *italic*,
  ~~strike~~, `code`, [links](other-page.md#anchor), [[wiki links]],
  <https://autolinks>, ![images](https://...), > quotes and GitHub alerts
  (> [!NOTE] / TIP / IMPORTANT / WARNING / CAUTION), - and 1. lists (nested,
  - [ ] task lists), | tables | with :alignment:, ``` fenced code ``` with a
  little highlighting for php / sql / js / json / bash, and --- rules.

TRAP: raw HTML in a page is NEVER passed through - every character of text is
escaped. The pages are files a developer edits, and a generated page quotes
code comments, so treating any of it as HTML would let a comment run script in
an administrator's browser. Anything that needs markup gets a Markdown form.

Links: the caller passes a resolver. An internal link (a relative .md path or a
[[wiki link]]) becomes <a href="?page=..." data-ew-page="..."> so the viewer can
open it in a tab; anything with a scheme opens in a new browser window.

## Methods

| Method | What it does |
|---|---|
| [render()](#render) | Render a page. |
| [blocks()](#blocks) | Renders a run of lines as block HTML - fenced code, headings, rules, quotes and alerts, tables, lists and paragraphs. |
| [startsBlock()](#startsblock) | Would this line start a new block (and so end a paragraph)? |
| [heading()](#heading) | Renders a heading with its GitHub-style anchor; the first h1 becomes the page title and h2/h3 go into the table of contents. |
| [slug()](#slug) | GitHub's anchor rule: lower case, punctuation dropped, spaces to hyphens, repeats numbered. |
| [quote()](#quote) | Renders a block quote, or a GitHub alert box when the first line is [!NOTE], [!TIP], [!IMPORTANT], [!WARNING] or [!CAUTION]. |
| [isTableRule()](#istablerule) | True when a line is a table's header separator (\| --- \| :---: \|). |
| [cells()](#cells) | Splits a table row into trimmed cells, ignoring the outer pipes and any \| inside `code` or escaped as \\|. |
| [table()](#table) | Renders a table from its header, separator and body rows, applying the column alignment. |
| [listMarker()](#listmarker) | Reads a list item marker (- * + or 1. 1)) at the start of a line; null when the line is not a list item, including a --- rule. |
| [listBlock()](#listblock) | One list (and its nested lists). |
| [codeBlock()](#codeblock) | Renders a fenced code block with its language label, highlighted when the language is one it knows. |
| [highlight()](#highlight) | Just enough colour to read by: comments, strings, numbers, keywords, variables. |
| [inlineLines()](#inlinelines) | Renders a paragraph's lines as inline HTML, turning two trailing spaces or a trailing backslash into a line break. |
| [hold()](#hold) | Parks finished HTML (code, links, escapes) behind a placeholder so later inline passes cannot touch it. |
| [inline()](#inline) | Renders inline Markdown - escapes, code, images, links, wiki links and autolinks, then bold, italic and strike - with all other text escaped. |
| [link()](#link) | Builds a link - external ones open in a new window, #anchors stay on the page, and wiki paths go through the resolver (a page it cannot find is shown as a broken link). |
| [e()](#e) | Escapes text for HTML. |

### render

*public static* - line 40

Render a page.

```php
function render(string $md, callable $resolve): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$md` | `string` |  | the Markdown source |
| `$resolve` | `callable` |  | fn(string `$href`): ?string - maps a relative link to a wiki page path |

**Returns:** array{html:string, title:?string, toc:array<int,array{level:int,text:string,id:string}>}

**Called from:** [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md)

### blocks

*private* - line 56

Renders a run of lines as block HTML - fenced code, headings, rules, quotes and alerts, tables, lists and paragraphs.

```php
function blocks(array $lines): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the page (or a quote / list item body) as lines, line endings and tabs already normalised |

### startsBlock

*private* - line 134

Would this line start a new block (and so end a paragraph)?

```php
function startsBlock(string $line): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | one source line |

### heading

*private* - line 146

Renders a heading with its GitHub-style anchor; the first h1 becomes the page title and h2/h3 go into the table of contents.

```php
function heading(int $level, string $text): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$level` | `int` |  | 1 to 6, the number of # marks |
| `$text` | `string` |  | the heading text as written, without the # marks - inline Markdown |

### slug

*private* - line 161

GitHub's anchor rule: lower case, punctuation dropped, spaces to hyphens, repeats numbered.

```php
function slug(string $text): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$text` | `string` |  | the heading as plain text (tags stripped, entities decoded); repeats are tracked per page |

### quote

*private* - line 178

Renders a block quote, or a GitHub alert box when the first line is [!NOTE], [!TIP], [!IMPORTANT], [!WARNING] or [!CAUTION].

```php
function quote(array $lines): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the quote's lines with the leading "> " already removed |

### isTableRule

*private* - line 196

True when a line is a table's header separator (| --- | :---: |).

```php
function isTableRule(string $line): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | the line straight after a candidate header row |

### cells

*private* - line 207

Splits a table row into trimmed cells, ignoring the outer pipes and any | inside `code` or escaped as \|.

```php
function cells(string $row): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `string` |  | one raw table line |

### table

*private* - line 230

Renders a table from its header, separator and body rows, applying the column alignment.

```php
function table(array $rows): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rows` | `array` |  | raw table lines: [0] the header, [1] the \| --- \| separator, then the body rows |

### listMarker

*private* - line 258

Reads a list item marker (- * + or 1. 1)) at the start of a line; null when the line is not a list item, including a --- rule.

```php
function listMarker(string $line): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$line` | `string` |  | one source line |

**Returns:** ?array{indent:int, ordered:bool, start:int, width:int}

### listBlock

*private* - line 273

One list (and its nested lists).

```php
function listBlock(array $lines, int $i): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | every line of the block being rendered |
| `$i` | `int` |  | the index in `$lines` of the list's first item |

**Returns:** array{0:string,1:int} [html, next line]

### codeBlock

*private* - line 339

Renders a fenced code block with its language label, highlighted when the language is one it knows.

```php
function codeBlock(string $code, string $lang): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `string` |  | the code between the fences, unescaped |
| `$lang` | `string` |  | the lower-cased language after the opening fence ("php", "sql"...), '' for none |

### highlight

*private* - line 355

Just enough colour to read by: comments, strings, numbers, keywords, variables.

```php
function highlight(string $code, string $kind): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$code` | `string` |  | the code to colour, unescaped (every piece is escaped here) |
| `$kind` | `string` |  | 'php', 'sql', 'js', 'sh' or 'css' - the alias `codeBlock()` mapped the language to |

### inlineLines

*private* - line 397

Renders a paragraph's lines as inline HTML, turning two trailing spaces or a trailing backslash into a line break.

```php
function inlineLines(array $lines): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$lines` | `array` |  | the paragraph's source lines, in order |

### hold

*private* - line 415

Parks finished HTML (code, links, escapes) behind a placeholder so later inline passes cannot touch it.

```php
function hold(string $html): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$html` | `string` |  | finished, already-escaped HTML |

### inline

*private* - line 426

Renders inline Markdown - escapes, code, images, links, wiki links and autolinks, then bold, italic and strike - with all other text escaped.

```php
function inline(string $t): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `string` |  | one piece of Markdown text (a line, a heading, a cell or a link label) - raw, escaped here |

### link

*private* - line 463

Builds a link - external ones open in a new window, #anchors stay on the page, and wiki paths go through the resolver (a page it cannot find is shown as a broken link).

```php
function link(string $href, string $label, bool $wiki = false): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$href` | `string` |  | the link target as written: a URL, a #anchor, a relative .md path, or a wiki path when `$wiki` |
| `$label` | `string` |  | the link text already rendered to HTML - inserted as is, so it must be escaped first |
| `$wiki` | `bool` | `false` | true for a [[wiki link]], whose path is from the wiki root and may omit ".md" |

### e

*private* - line 490

Escapes text for HTML.

```php
function e(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | plain text |

