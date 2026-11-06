# includes/engineering_wiki/markdown.php

> PHP - 495 lines

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

## Classes

- [EwMarkdown](/code/classes/EwMarkdown.md) (class, line 24) - Engineering wiki - a small Markdown renderer written for the wiki, so the pages look and read like GitHub without pulling in a library.

## How it hangs together

**Included by:** [includes/engineering_wiki/store.php](/code/files/includes/engineering_wiki/store.php.md)

