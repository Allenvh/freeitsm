# api/files/download.php

> API endpoint - 99 lines

GET ?id=<file>[&version=<version id>] - stream a file to the browser.

Download permission on the file's folder, checked here on every request -
the stored path is never exposed and the storage root is denied to the web
server outright. Every download is audited, including which version.

Supports HTTP Range so a 2 GB download that drops can resume, and so the
phase 2 video player can seek. Always sent as an attachment with nosniff:
nothing uploaded here is ever rendered by the browser as our own page.

## The endpoint

| | |
|---|---|
| URL | `api/files/download.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET id`, `GET version` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [files_versions](/code/tables/files_versions.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesAbsolutePath()](/code/functions/filesAbsolutePath.md), [filesAudit()](/code/functions/filesAudit.md), [filesItem()](/code/functions/filesItem.md)

