# EwScanner

> Class in [includes/engineering_wiki/scanner.php](/code/files/includes/engineering_wiki/scanner.php.md), line 29

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Sets the app root, the output folder (engineering-wiki/code) and the folder that holds the scan state. |
| [step()](#step) | Do up to `$seconds` of work. Call again until 'done' is true. |
| [runAll()](#runall) | Run to the end (command line). |
| [report()](#report) | Builds the progress answer `step()` returns - phase, files done of total, files changed, pages written and a message. |
| [ensureDirs()](#ensuredirs) | Creates the output and state folders if needed; throws when the web server cannot write to them. |
| [phaseList()](#phaselist) | Phase 1 - walks the app and queues every file that is new or whose size or SHA-1 changed since the manifest, and drops deleted files from it. |
| [walk()](#walk) | Calls `$fn` for every .php and .js file under a folder, skipping SKIP_DIRS, hidden folders, folders whose .htaccess denies all, minified scripts and files over 1.5 MB. |
| [phaseParse()](#phaseparse) | Phase 2 - parses queued files until the time budget runs out, storing each file's parse in the manifest and saving progress every few seconds. |
| [parsePhp()](#parsephp) | Everything worth knowing about one PHP file. |
| [hasModifier()](#hasmodifier) | True when the declaration at token `$i` carries the given modifier (static, abstract...) among the few tokens before it. |
| [visibility()](#visibility) | Returns 'public', 'protected' or 'private' for the declaration at token `$i`, or '' when none is written. |
| [params()](#params) | The parameters between ( and the matching ). |
| [tidyParams()](#tidyparams) | Trims each parsed parameter's type and default (whitespace in a default collapsed to single spaces). |
| [evalPath()](#evalpath) | Work out the file an include points at: string pieces, __DIR__, dirname(__DIR__[, n]). |
| [normalise()](#normalise) | Resolves '.' and '..' in a relative path; null when it climbs above the app root or ends up empty. |
| [resolveRel()](#resolverel) | Resolves a script src or link found in a page to a path from the app root; null for external URLs and PHP-built paths. |
| [sqlOf()](#sqlof) | Is this string SQL? Which tables does it read and which does it write (with the columns written)? |
| [parseDoc()](#parsedoc) | A /** doc comment * / split into summary, body and `@tags`. |
| [lineDoc()](#linedoc) | A run of // lines, or a plain block comment, used as a description when there is no doc comment. Decorative rules (// ----, // ====) and a lone TRAP are not descriptions. |
| [trapText()](#traptext) | Cleans a TRAP: comment down to its text - comment markers stripped, from "TRAP:" to the end of its paragraph, on one line. |
| [parseJs()](#parsejs) | JavaScript: its functions (with /** comments) and the API endpoints it calls. |
| [phaseWrite()](#phasewrite) | Phase 3 - builds every page (files, functions, classes, tables, indexes, endpoints, traps) from the manifest, writes only those that changed and deletes pages no longer produced. |
| [cleanEmptyDirs()](#cleanemptydirs) | Removes empty folders under the output, keeping _scan; returns true when the folder itself is empty. |
| [matchEndpoint()](#matchendpoint) | "api/tickets/save.php" from a JS reference like "../api/tickets/save.php" or "api/tickets/save.php". |
| [link()](#link) | A Markdown link to a wiki page. |
| [fileLink()](#filelink) | A link to a file's page. |
| [fnLink()](#fnlink) | A link to a function's page, labelled `name()`. |
| [tableLink()](#tablelink) | A link to a table's page. |
| [fnSlug()](#fnslug) | A function or class name made safe for a page file name. |
| [mdText()](#mdtext) | Text safe inside a link label or table cell. |
| [cell()](#cell) | Text made safe for a Markdown table cell - pipes escaped, line breaks flattened. |
| [code()](#code) | Text as inline code (any backtick inside becomes a quote). |
| [docMd()](#docmd) | A parsed doc comment as Markdown - the wiki link box, then the summary (optional) and the body. |
| [wikiLinks()](#wikilinks) | The GitHub wiki pages a comment points at, as a box that stands out at the top of the page - a LINK only; the wiki's text is never copied into FreeITSM. From "@wiki Page-Name" (or a full URL) and from any github.com/edmozley/freeitsm/wiki/... link already written in the comment. |
| [prose()](#prose) | Comment prose into Markdown: keep paragraphs and lists, stop "*" and "_" in code-ish text turning into emphasis. |
| [signature()](#signature) | A function's signature as written, wrapped one parameter per line when it is over 100 characters. |
| [paramsTable()](#paramstable) | The parameters table - name, type, default, and the meaning from its `@param` tag. |
| [standardParam()](#standardparam) | The meaning of a parameter that means the same everywhere (handbook/standard-parameters.md), used only when the function's own comment says nothing about it. Marked "standard" so a reader knows where it came from. |
| [tagRows()](#tagrows) | The doc comment's tags of the wanted kinds, as [tag, value] pairs. |
| [filePage()](#filepage) | Builds a file's page - its description, the endpoint box for an API file, its functions and classes, its SQL, what it includes and is included by, what it calls, and its traps. |
| [guardText()](#guardtext) | How an endpoint is protected: the checks it makes itself, else the ones made by a file it includes (a shared bootstrap like includes/files/api_bootstrap.php), at the top level of that file - a check inside a function only guards that function's callers. |
| [guardsOf()](#guardsof) | The sign-in and permission checks a file makes at its top level (admin guard, module access, capability), each suffixed with `$via`. |
| [sqlMd()](#sqlmd) | The Database table for a list of queries - line, function, tables read or written (with columns), and the SQL: note. |
| [trapsMd()](#trapsmd) | The Traps section - each trap as a warning box with its line and function. |
| [seeLink()](#seelink) | An `@see` value as a file link when it names a file in the app, otherwise as code. |
| [functionPage()](#functionpage) | Builds a function's page - description, signature, parameters, return, the tables it touches (warning when the comment names one its SQL does not), what it calls, its callers and its traps. |
| [classPage()](#classpage) | Builds a class's page - its description, then each method with its signature, parameters, tags, SQL and callers. |
| [tablePage()](#tablepage) | Builds a table's page - its columns from the schema with their comments, then who writes it and who reads it. |
| [indexPage()](#indexpage) | Builds the Code map front page - counts of files, endpoints, functions, classes, tables and traps, with links. |
| [functionsIndex()](#functionsindex) | Builds the A-Z list of functions outside classes, with file and summary. |
| [classesIndex()](#classesindex) | Builds the list of classes, with file, method count and summary. |
| [tablesIndex()](#tablesindex) | Builds the list of tables, with column count, how many files read and write each, and the first sentence of its note. |
| [filesIndex()](#filesindex) | Builds the files index - a count per top folder, then the top-level files with their summaries. |
| [endpointsPage()](#endpointspage) | Builds the API endpoints page - every api/ PHP file by folder, with its protection, summary and the scripts that call it. |
| [trapsPage()](#trapspage) | Builds the page listing every TRAP: comment, file by file. |
| [loadSchema()](#loadschema) | Loads the table definitions from includes/db_verify_schema.php, with the comments written above each table and above or beside each column as notes. |
| [loadManifest()](#loadmanifest) | Loads the saved manifest, unless it was written by a different VERSION of the scanner (which forces a full re-read). |
| [saveManifest()](#savemanifest) | Saves the manifest. |
| [saveState()](#savestate) | Saves the scan's progress; a finished scan saves an empty state. |
| [readJson()](#readjson) | Reads a JSON file from the state folder, or `$default` when it is missing or unreadable. |
| [writeJson()](#writejson) | Writes a JSON file to the state folder through a temporary file and a rename, so a reader never sees half a file. |
| [lastScan()](#lastscan) | When the last scan finished, and what it did - for the reader's Rescan button. |

### __construct

*public* - line 46

Sets the app root, the output folder (engineering-wiki/code) and the folder that holds the scan state.

```php
function __construct()
```

### step

*public* - line 62

Do up to `$seconds` of work. Call again until 'done' is true.

```php
function step(float $seconds = 8.0, bool $fresh = false): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seconds` | `float` | `8.0` | time budget in seconds; parsing stops at it, a write phase once started runs to the end |
| `$fresh` | `bool` | `false` | true drops saved progress (state.json) and lists again; unchanged files are still skipped |

**Returns:** array{done:bool, phase:string, done_files:int, total_files:int, changed:int, message:string}

### runAll

*public* - line 92

Run to the end (command line).

```php
function runAll(?callable $progress = null): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$progress` | `?callable` | `null` | called after every 20-second step with that step's `report()` array; null for none |

### report

*private* - line 107

Builds the progress answer `step()` returns - phase, files done of total, files changed, pages written and a message.

```php
function report(string $phase, string $message): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$phase` | `string` |  | 'list', 'parse', 'write', 'done', or 'busy' when another scan holds the lock |
| `$message` | `string` |  | a line to show the person running the scan; '' when there is nothing to say |

### ensureDirs

*private* - line 121

Creates the output and state folders if needed; throws when the web server cannot write to them.

```php
function ensureDirs(): void
```

### phaseList

*private* - line 134

Phase 1 - walks the app and queues every file that is new or whose size or SHA-1 changed since the manifest, and drops deleted files from it.

```php
function phaseList(): void
```

### walk

*private* - line 167

Calls `$fn` for every .php and .js file under a folder, skipping SKIP_DIRS, hidden folders, folders whose .htaccess denies all, minified scripts and files over 1.5 MB.

```php
function walk(string $abs, string $rel, callable $fn): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$abs` | `string` |  | the folder's absolute path |
| `$rel` | `string` |  | the same folder from the app root; '' for the root, whose own .htaccess is not checked |
| `$fn` | `callable` |  | called as fn(string `$rel`, string `$abs`) for each file found |

### phaseParse

*private* - line 189

Phase 2 - parses queued files until the time budget runs out, storing each file's parse in the manifest and saving progress every few seconds.

```php
function phaseParse(): void
```

### parsePhp

*private* - line 214

Everything worth knowing about one PHP file.

```php
function parsePhp(string $src, string $rel): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$src` | `string` |  | the file's contents |
| `$rel` | `string` |  | the file's path from the app root - includes and script srcs are resolved from it |

### hasModifier

*private* - line 453

True when the declaration at token `$i` carries the given modifier (static, abstract...) among the few tokens before it.

```php
function hasModifier(array $tokens, int $i, callable $sig, int $mod): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tokens` | `array` |  | the file's `token_get_all()` output |
| `$i` | `int` |  | the index of the T_FUNCTION / T_CLASS token |
| `$sig` | `callable` |  | `parsePhp()`'s significant-token finder: fn(int `$i`, int `$dir`): int, -1 when there is none |
| `$mod` | `int` |  | the modifier's token id, e.g. T_STATIC |

### visibility

*private* - line 470

Returns 'public', 'protected' or 'private' for the declaration at token `$i`, or '' when none is written.

```php
function visibility(array $tokens, int $i, callable $sig): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tokens` | `array` |  | the file's `token_get_all()` output |
| `$i` | `int` |  | the index of the T_FUNCTION token |
| `$sig` | `callable` |  | `parsePhp()`'s significant-token finder: fn(int `$i`, int `$dir`): int, -1 when there is none |

### params

*private* - line 489

The parameters between ( and the matching ).

```php
function params(array $tokens, int $open): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tokens` | `array` |  | the file's `token_get_all()` output |
| `$open` | `int` |  | the index of the "(" that opens the parameter list |

**Returns:** array{0:array,1:int} [params, index of ")"]

### tidyParams

*private* - line 527

Trims each parsed parameter's type and default (whitespace in a default collapsed to single spaces).

```php
function tidyParams(array $ps): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ps` | `array` |  | the raw parameters `params()` collected - type, name, default, ref, variadic and an optional note |

### evalPath

*private* - line 539

Work out the file an include points at: string pieces, __DIR__, dirname(__DIR__[, n]).

```php
function evalPath(array $expr, string $rel): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$expr` | `array` |  | the tokens between include / require and its ";" |
| `$rel` | `string` |  | the including file's path from the app root |

### normalise

*private* - line 581

Resolves '.' and '..' in a relative path; null when it climbs above the app root or ends up empty.

```php
function normalise(string $path): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | a path from the app root; backslashes are read as slashes |

### resolveRel

*private* - line 598

Resolves a script src or link found in a page to a path from the app root; null for external URLs and PHP-built paths.

```php
function resolveRel(string $from, string $href): ?string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$from` | `string` |  | the path, from the app root, of the file the reference is written in |
| `$href` | `string` |  | the src as written - relative, "/"-rooted, a URL, or starting with an inline PHP echo |

### sqlOf

*private* - line 612

Is this string SQL? Which tables does it read and which does it write (with the columns written)?

```php
function sqlOf(string $s): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | a string literal (quotes may still be on), or the joined pieces of an interpolated string / heredoc |

### parseDoc

*private* - line 643

A /** doc comment * / split into summary, body and `@tags`.

```php
function parseDoc(string $raw): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the whole doc comment as written, opening and closing markers included |

### lineDoc

*private* - line 673

A run of // lines, or a plain block comment, used as a description when there is no doc comment. Decorative rules (// ----, // ====) and a lone TRAP are not descriptions.

```php
function lineDoc(array $comments): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$comments` | `array` |  | the raw comment texts in order - // lines, # lines or one block comment |

### trapText

*private* - line 699

Cleans a TRAP: comment down to its text - comment markers stripped, from "TRAP:" to the end of its paragraph, on one line.

```php
function trapText(string $raw): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | the comment holding TRAP: - a run of // lines joined with "\n", or a whole block comment |

### parseJs

*private* - line 722

JavaScript: its functions (with /** comments) and the API endpoints it calls.

```php
function parseJs(string $src, string $rel): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$src` | `string` |  | the file's contents |
| `$rel` | `string` |  | the file's path from the app root (not used yet) |

### phaseWrite

*private* - line 783

Phase 3 - builds every page (files, functions, classes, tables, indexes, endpoints, traps) from the manifest, writes only those that changed and deletes pages no longer produced.

```php
function phaseWrite(): void
```

### cleanEmptyDirs

*private* - line 883

Removes empty folders under the output, keeping _scan; returns true when the folder itself is empty.

```php
function cleanEmptyDirs(string $dir): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | the absolute folder to tidy - the output folder, then each folder below it |

### matchEndpoint

*private* - line 901

"api/tickets/save.php" from a JS reference like "../api/tickets/save.php" or "api/tickets/save.php".

```php
function matchEndpoint(string $e, array $files): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `string` |  | an endpoint path as found in the JavaScript, e.g. "../api/tickets/save.php" |
| `$files` | `array` |  | every scanned file, keyed by path from the app root (only the keys are used) |

### link

*private* - line 915

A Markdown link to a wiki page.

```php
function link(string $page, string $label): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$page` | `string` |  | the page path from the wiki root without ".md", e.g. "code/tables/leases" |
| `$label` | `string` |  | the link text, plain |

### fileLink

*private* - line 922

A link to a file's page.

```php
function fileLink(string $rel, ?string $label = null): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the file's path from the app root |
| `$label` | `?string` | `null` | the link text; null shows the path |

### fnLink

*private* - line 928

A link to a function's page, labelled `name()`.

```php
function fnLink(string $name): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the function's name as defined |

### tableLink

*private* - line 934

A link to a table's page.

```php
function tableLink(string $t): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `string` |  | the table name |

### fnSlug

*private* - line 940

A function or class name made safe for a page file name.

```php
function fnSlug(string $name): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | a function or class name |

### mdText

*private* - line 946

Text safe inside a link label or table cell.

```php
function mdText(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | plain text |

### cell

*private* - line 952

Text made safe for a Markdown table cell - pipes escaped, line breaks flattened.

```php
function cell(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | the cell's text, plain or Markdown |

### code

*private* - line 958

Text as inline code (any backtick inside becomes a quote).

```php
function code(string $s): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` | `string` |  | the text to show as code |

### docMd

*private* - line 966

A parsed doc comment as Markdown - the wiki link box, then the summary (optional) and the body.

```php
function docMd(?array $doc, bool $withSummary = true): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `?array` |  | a parsed comment from `parseDoc()` / `lineDoc()` - array{summary, body, tags}; null for none |
| `$withSummary` | `bool` | `true` | false leaves the summary out (when the page shows it elsewhere) |

### wikiLinks

*private* - line 983

The GitHub wiki pages a comment points at, as a box that stands out at the top of the page - a LINK only; the wiki's text is never copied into FreeITSM. From "@wiki Page-Name" (or a full URL) and from any github.com/edmozley/freeitsm/wiki/... link already written in the comment.

```php
function wikiLinks(array $doc): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `array` |  | a parsed comment from `parseDoc()` / `lineDoc()` - its `@wiki` tags, summary and body are read |

### prose

*private* - line 1006

Comment prose into Markdown: keep paragraphs and lists, stop "*" and "_" in code-ish text turning into emphasis.

```php
function prose(string $t): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `string` |  | comment text - a summary, body, tag value or note |

### signature

*private* - line 1019

A function's signature as written, wrapped one parameter per line when it is over 100 characters.

```php
function signature(array $fn): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `array` |  | a parsed function from `parsePhp()` - name, params and return are used |

### paramsTable

*private* - line 1034

The parameters table - name, type, default, and the meaning from its `@param` tag.

```php
function paramsTable(array $fn): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fn` | `array` |  | a parsed function from `parsePhp()` with its 'file' added - params, doc and file are used |

### standardParam

*private* - line 1057

The meaning of a parameter that means the same everywhere (handbook/standard-parameters.md), used only when the function's own comment says nothing about it. Marked "standard" so a reader knows where it came from.

```php
function standardParam(array $p, string $file): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | one parameter as `tidyParams()` leaves it - name and type are used |
| `$file` | `string` |  | the defining file's path from the app root; under api/v1/ the REST parameters count too |

### tagRows

*private* - line 1082

The doc comment's tags of the wanted kinds, as [tag, value] pairs.

```php
function tagRows(?array $doc, array $want): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$doc` | `?array` |  | a parsed comment from `parseDoc()` / `lineDoc()` - array{summary, body, tags}; null for none |
| `$want` | `array` |  | the lower-case tag names to keep, e.g. ['return', 'reads'] |

### filePage

*private* - line 1100

Builds a file's page - its description, the endpoint box for an API file, its functions and classes, its SQL, what it includes and is included by, what it calls, and its traps.

```php
function filePage(
    string $rel,
    array $d,
    array $includedBy,
    array $jsCallers,
    array $usedBy,
    array $tableSet,
    array $fnIndex
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the file's path from the app root, forward slashes, e.g. "api/tickets/save.php" |
| `$d` | `array` |  | the file's parse, from `parsePhp()` or `parseJs()` |
| `$includedBy` | `array` |  | the PHP files that include it: path => true |
| `$jsCallers` | `array` |  | the JavaScript files that call it as an endpoint: path => true |
| `$usedBy` | `array` |  | the pages that load it with a script tag: path => true |
| `$tableSet` | `array` |  | table name => true for every table in the schema, so only real tables are linked |
| `$fnIndex` | `array` |  | lower-case function name => list of its definitions (each a parsed function plus 'file') |

### guardText

*private* - line 1169

How an endpoint is protected: the checks it makes itself, else the ones made by a file it includes (a shared bootstrap like includes/files/api_bootstrap.php), at the top level of that file - a check inside a function only guards that function's callers.

```php
function guardText(string $rel, array $d): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rel` | `string` |  | the file's path from the app root, forward slashes, e.g. "api/tickets/save.php" |
| `$d` | `array` |  | the file's parse, as `parsePhp()` returns it |

### guardsOf

*private* - line 1199

The sign-in and permission checks a file makes at its top level (admin guard, module access, capability), each suffixed with `$via`.

```php
function guardsOf(array $d, string $via): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` | `array` |  | the file's parse, as `parsePhp()` returns it |
| `$via` | `string` |  | added after each check found, e.g. " (via includes/x.php)"; '' for the endpoint's own checks |

### sqlMd

*private* - line 1221

The Database table for a list of queries - line, function, tables read or written (with columns), and the SQL: note.

```php
function sqlMd(array $sql, array $tableSet): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sql` | `array` |  | query entries from `sqlOf()` with line, fn and note added, as `parsePhp()` stores them |
| `$tableSet` | `array` |  | table name => true for every table in the schema, so only real tables are linked |

### trapsMd

*private* - line 1240

The Traps section - each trap as a warning box with its line and function.

```php
function trapsMd(array $traps): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$traps` | `array` |  | the traps to show, each array{line:int, fn:?string, text:string} |

### seeLink

*private* - line 1253

An `@see` value as a file link when it names a file in the app, otherwise as code.

```php
function seeLink(string $v): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | an `@see` value as written; only its first word is used |

### functionPage

*private* - line 1269

Builds a function's page - description, signature, parameters, return, the tables it touches (warning when the comment names one its SQL does not), what it calls, its callers and its traps.

```php
function functionPage(
    array $defs,
    array $callers,
    array $files,
    array $tableSet,
    array $fnIndex
): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$defs` | `array` |  | every definition of this function name (a parsed function plus 'file'); the first is shown |
| `$callers` | `array` |  | who calls it: "file\|caller" => array{file, fn}, fn null for top-level code |
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |
| `$tableSet` | `array` |  | table name => true for every table in the schema, so only real tables are linked |
| `$fnIndex` | `array` |  | lower-case function name => list of its definitions (each a parsed function plus 'file') |

### classPage

*private* - line 1326

Builds a class's page - its description, then each method with its signature, parameters, tags, SQL and callers.

```php
function classPage(string $cls, array $c, array $callers, array $files, array $tableSet): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$cls` | `string` |  | the class name |
| `$c` | `array` |  | the class's `$classIndex` entry - file, line, doc, kind and methods |
| `$callers` | `array` |  | every caller list, keyed by function name or "Class::method" (method lower-case) |
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |
| `$tableSet` | `array` |  | table name => true for every table in the schema, so only real tables are linked |

### tablePage

*private* - line 1359

Builds a table's page - its columns from the schema with their comments, then who writes it and who reads it.

```php
function tablePage(string $t, array $def, array $use): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$t` | `string` |  | the table name |
| `$def` | `array` |  | the table's `loadSchema()` entry - array{note, columns} |
| `$use` | `array` |  | array{read?: list, write?: list} from `phaseWrite()`; empty when no SQL names the table |

### indexPage

*private* - line 1395

Builds the Code map front page - counts of files, endpoints, functions, classes, tables and traps, with links.

```php
function indexPage(array $files, array $fnIndex, array $classIndex, array $schema): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |
| `$fnIndex` | `array` |  | lower-case function name => list of its definitions (each a parsed function plus 'file') |
| `$classIndex` | `array` |  | class name => array{file, line, doc, kind, methods} (methods are parsed functions plus 'file') |
| `$schema` | `array` |  | `loadSchema()`'s result: table name => array{note, columns} |

### functionsIndex

*private* - line 1417

Builds the A-Z list of functions outside classes, with file and summary.

```php
function functionsIndex(array $fnIndex): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fnIndex` | `array` |  | lower-case function name => list of its definitions (each a parsed function plus 'file') |

### classesIndex

*private* - line 1437

Builds the list of classes, with file, method count and summary.

```php
function classesIndex(array $classIndex): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$classIndex` | `array` |  | class name => array{file, line, doc, kind, methods} (methods are parsed functions plus 'file') |

### tablesIndex

*private* - line 1454

Builds the list of tables, with column count, how many files read and write each, and the first sentence of its note.

```php
function tablesIndex(array $schema, array $use): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$schema` | `array` |  | `loadSchema()`'s result: table name => array{note, columns} |
| `$use` | `array` |  | table name => array{read?: list, write?: list}, each entry carrying the file |

### filesIndex

*private* - line 1471

Builds the files index - a count per top folder, then the top-level files with their summaries.

```php
function filesIndex(array $files): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |

### endpointsPage

*private* - line 1490

Builds the API endpoints page - every api/ PHP file by folder, with its protection, summary and the scripts that call it.

```php
function endpointsPage(array $files, array $jsCallers): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |
| `$jsCallers` | `array` |  | endpoint path => [path of each JavaScript file that calls it => true] |

### trapsPage

*private* - line 1512

Builds the page listing every TRAP: comment, file by file.

```php
function trapsPage(array $files): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | every scanned file's parse (parsePhp() / `parseJs()` result), keyed by path from the app root |

### loadSchema

*private* - line 1532

Loads the table definitions from includes/db_verify_schema.php, with the comments written above each table and above or beside each column as notes.

```php
function loadSchema(): array
```

**Returns:** array<string, array{note:string, columns:array<string,array{def:string,note:string}>}>

### loadManifest

*private* - line 1581

Loads the saved manifest, unless it was written by a different VERSION of the scanner (which forces a full re-read).

```php
function loadManifest(): void
```

### saveManifest

*private* - line 1587

Saves the manifest.

```php
function saveManifest(): void
```

### saveState

*private* - line 1589

Saves the scan's progress; a finished scan saves an empty state.

```php
function saveState(): void
```

### readJson

*private* - line 1596

Reads a JSON file from the state folder, or `$default` when it is missing or unreadable.

```php
function readJson(string $name, $default)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the file name inside _scan, e.g. 'state.json' |
| `$default` |  |  | returned when the file is missing, unreadable or holds null |

### writeJson

*private* - line 1609

Writes a JSON file to the state folder through a temporary file and a rename, so a reader never sees half a file.

```php
function writeJson(string $name, $data): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the file name inside _scan, e.g. 'manifest.json' |
| `$data` |  |  | any value `json_encode()` can write |

### lastScan

*public static* - line 1617

When the last scan finished, and what it did - for the reader's Rescan button.

```php
function lastScan(): ?array
```

