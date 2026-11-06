# includes/contributors.php

> PHP - 86 lines

People who have made a substantial contribution to FreeITSM.

──────────────────────────────────────────────────────────────────────────
🔑 THIS IS A CODE FILE ON PURPOSE, NOT A DATABASE TABLE.

A table would need a migration, a schema entry, an admin screen to edit it,
and permissions deciding who may — all so that a list which grows a handful
of times a year can be changed without a deploy. It is also not an
operator's data: it is part of what FreeITSM *is*, and it should read the
same on every install rather than being something each site curates.

So: add a person here, commit it, and it ships with the next release.
──────────────────────────────────────────────────────────────────────────

Each entry:
  name     how they asked to be credited — use exactly what they gave you
  github   username without the @, or null if they would rather not be linked
  date     the contribution, YYYY-MM-DD. Displayed as a month and year.
  what     two or three sentences. Say what they actually did and why it
           mattered; "helped with the project" credits nobody.

⚠️ Ask before adding someone. A person's name and GitHub handle on a public
product page is their decision, not ours — Sandy was asked in #138 and chose
how he wanted to be credited.

Newest first.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [getContributors()](/code/functions/getContributors.md) | 36 | Returns the people credited for substantial contributions, newest first. |

## How it hangs together

**Included by:** [system/contributors/index.php](/code/files/system/contributors/index.php.md)

