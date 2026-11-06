# Writing comments for the wiki

The Code map is built by a scanner that reads the code. It has no AI - it is a script - so it cannot guess what a function is *for*. What it can do is read comments that follow a fixed shape, and put them on the right page. A comment written this way does two jobs at once: it reads naturally in VS Code or Notepad++, and it becomes documentation in the wiki.

> [!TIP]
> Nothing here is compulsory. A function with no comment still gets a page - its name, its parameters, where it is defined and who calls it. The comments add the meaning.

## The file comment

The first `/** ... */` block in a file, before any code, describes the whole file. Its first sentence is the summary shown in lists; the rest becomes the top of the file's page.

```php
<?php
/**
 * Leases (3.5.0) - Assets -> Leases.
 *
 * A LEASE is the agreement (lessor, agreement number, cost centre...). It has
 * SCHEDULES - a group of kit with its own dates and cost...
 */
```

## The function comment

A `/** ... */` block straight above a function. The first sentence is the summary; the paragraphs after it are the explanation. Then, optionally, these tags - one per line:

| Tag | Meaning | Example |
|---|---|---|
| `@param` | what goes in | `@param int $assetId  the asset to check` |
| `@return` | what comes out | `@return ?array  the active lease row, or null` |
| `@reads` | tables (and columns) it reads, and why | `@reads lease_assets(outcome_id) - which machines are still out` |
| `@writes` | tables (and columns) it changes, and why | `@writes assets(lease_start, lease_expiry) - kept in step with the lease` |
| `@see` | another page worth reading | `@see includes/leases.php` |
| `@wiki` | the developer guide for this on the GitHub wiki - shown as a link box at the top of the page | `@wiki Leases` |

`@wiki` takes the page name as it appears in the GitHub wiki's address (`Leases`, `Projects-Internals-4-Plan-and-Timeline`) or the whole link. Only the link is shown - the wiki's text stays on GitHub. Any `github.com/edmozley/freeitsm/wiki/...` link already written in a comment is picked up the same way.

```php
/**
 * SQL: the asset has GONE BACK - it is on a lease, and none of its lease rows is
 * still out (every one is Returned, Bought out, ...).
 *
 * @param string $a  the alias the calling query gives the assets table
 * @return string    a condition to put after WHERE / AND
 * @reads lease_assets(asset_id, outcome_id), lease_outcomes(kind)
 */
function leaseGoneBackSql(string $a = 'a'): string
```

The scanner also finds the tables a function touches by reading its SQL, so `@reads` and `@writes` are about the *why*. When a tag names a table the SQL never mentions (or the other way round), the page says so - a quick way to spot a comment that has gone stale.

## API endpoints

An endpoint's file comment says what it is for. Two extra tags describe the conversation:

| Tag | Meaning |
|---|---|
| `@input` | one request field - its name, then what it is |
| `@output` | what comes back |

```php
/**
 * API: one Engineering wiki page, rendered.
 *
 * @input  page  the page path from the wiki root, without ".md"
 * @output the page rendered to HTML
 */
```

The scanner works out the rest itself - the URL, the guard that protects it (administrators only, a module, a capability), and the tables behind it.

## SQL

A line comment starting `SQL:` just above a query - or at the end of the line that holds it - is shown beside that query as its purpose.

```php
// SQL: every lease row for this asset that is still out on lease
$s = $conn->prepare("SELECT ... FROM lease_assets la ... WHERE la.asset_id = ? AND ...");
```

## Traps

A comment containing `TRAP:` marks something that will bite the next person - the reason a line looks odd, or the mistake it exists to prevent. Every trap is collected onto its file's page and onto one page that lists them all.

```php
// TRAP: a returned asset keeps lease_expiry as the record of what happened, so
// anything that alerts on lease_expiry must skip these.
```

The rule from the rest of the codebase still applies: say *what* goes wrong and *why*, so the warning still makes sense to somebody who has never seen the bug.
