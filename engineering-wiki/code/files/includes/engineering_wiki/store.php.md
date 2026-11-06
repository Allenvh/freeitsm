# includes/engineering_wiki/store.php

> PHP - 222 lines

Engineering wiki - where the pages live and how they are found.

Pages are plain Markdown files under engineering-wiki/ in the install:

  engineering-wiki/handbook/   written by people - the "why". Never touched by the scanner.
  engineering-wiki/code/       written by the scanner - the "what". Rebuilt on every scan.

A page is named by its path from that folder without ".md" ("handbook/start",
"code/tables/leases"). The folder carries .htaccess + web.config so the files are
never served directly; everything reaches the browser through the API, which is
administrator-only like the rest of System.

TRAP: every path that comes from a request goes through `ewSafePath()`. It allows
only a fixed character set, refuses "..", and the resolved file must still be
inside the wiki folder - the same three checks, in that order, everywhere.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ewRoot()](/code/functions/ewRoot.md) | 25 | The wiki folder (absolute, no trailing slash). |
| [ewSafePath()](/code/functions/ewSafePath.md) | 36 | A page path from a request, made safe, or null. Accepts "handbook/start", "/handbook/start.md", "code/files/api/x.php" (a dot is fine inside a name). |
| [ewFile()](/code/functions/ewFile.md) | 53 | The file behind a safe page path, if it exists and really is inside the wiki. |
| [ewResolveLink()](/code/functions/ewResolveLink.md) | 70 | Turn a link written on page `$from` into a page path (+ #anchor), or null when it does not lead to a wiki page. "../tables/leases.md#columns" on code/files/x -> "code/tables/leases#columns". A leading "/" means "from the wiki root" ([[wiki links]] arrive like that). |
| [ewReadPage()](/code/functions/ewReadPage.md) | 100 | A page, rendered. |
| [ewNiceName()](/code/functions/ewNiceName.md) | 123 | "code/tables/lease_assets" -> "lease_assets"; used when a page has no # title. |
| [ewTitleOf()](/code/functions/ewTitleOf.md) | 135 | The title of a page without rendering it: the first "# " line, else its file name. |
| [ewListDir()](/code/functions/ewListDir.md) | 154 | One folder of the page tree, for the sidebar (it opens folder by folder, because the code map runs to thousands of pages). |
| [ewSearch()](/code/functions/ewSearch.md) | 189 | Search every page for words. Titles count more than bodies; up to `$limit` hits. |

## How it hangs together

**Includes:** [includes/engineering_wiki/markdown.php](/code/files/includes/engineering_wiki/markdown.php.md)

**Included by:** [includes/engineering_wiki/scanner.php](/code/files/includes/engineering_wiki/scanner.php.md)

