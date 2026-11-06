# includes/files/api_bootstrap.php

> PHP - 262 lines

Files — the start of every api/files/*.php endpoint.

Signed in, allowed into the module, JSON out, a connection. Kept here rather
than repeated in every endpoint so a guard cannot be forgotten on the next one.
Folder permissions are checked by each endpoint, after this, with
`filesNeed()` - which answers "not found" for anything the caller cannot see.

Lives in includes/ so it cannot be requested on its own.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [filesApiBody()](/code/functions/filesApiBody.md) | 34 | The decoded JSON body (never null). |
| [filesApiOk()](/code/functions/filesApiOk.md) | 49 | Sends a success JSON reply ({success: true} plus `$data`) and ends the request. |
| [filesApiFail()](/code/functions/filesApiFail.md) | 63 | Sends a failure JSON reply ({success: false, error}) with an optional HTTP status and extra keys, and ends the request. |
| [filesHasCap()](/code/functions/filesHasCap.md) | 75 | True when the analyst holds the capability; a failed lookup counts as no. |
| [filesNeed()](/code/functions/filesNeed.md) | 89 | Refuse unless the caller holds `$need` on the folder. Below View the folder does not exist for them, so the answer is "not found" - the same words a deleted or made-up id gets. A refusal at a level they CAN see is audited as 'denied'. |
| [filesItem()](/code/functions/filesItem.md) | 106 | A live file row, or null. |
| [filesNeedItem()](/code/functions/filesNeedItem.md) | 124 | A file the caller holds `$need` on, or "not found". |
| [filesNameTaken()](/code/functions/filesNameTaken.md) | 147 | Is a name already used by a live folder or file in this folder? (case-insensitive) |
| [filesShortcutTarget()](/code/functions/filesShortcutTarget.md) | 182 | What a shortcut points at, IF the caller can see it - else null. The one place shortcuts meet permissions: a shortcut never widens access. Returns [name, folder_id (the target folder, or the file's folder), path, level]. |
| [filesArticleTarget()](/code/functions/filesArticleTarget.md) | 208 | A Knowledge article as a shortcut target, or null when this person may not read it (no Knowledge module, another company's, restricted, archived, a draft, deleted). Knowledge's own visibility rules decide - the same clause its list uses, WITHOUT the administrator floor: that floor is an audited override for opening one article in Knowledge, never a reason for it to show up in a folder. |
| [filesFreeName()](/code/functions/filesFreeName.md) | 236 | "Report.pdf" -> "Report (2).pdf", the first free one. |
| [filesApiRun()](/code/functions/filesApiRun.md) | 253 | Run an endpoint body and turn any failure into the UI's {success:false} shape. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `filesItem` | read [files_items](/code/tables/files_items.md) |  |
| 150 | `filesNameTaken` | read [files_folders](/code/tables/files_folders.md) |  |
| 158 | `filesNameTaken` | read [files_items](/code/tables/files_items.md) |  |
| 164 | `filesNameTaken` | read [files_shortcuts](/code/tables/files_shortcuts.md) |  |
| 223 | `filesArticleTarget` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/files/acl.php](/code/files/includes/files/acl.php.md), [includes/files/audit.php](/code/files/includes/files/audit.php.md), [includes/files/storage.php](/code/files/includes/files/storage.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/files/download.php](/code/files/api/files/download.php.md), [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/item.php](/code/files/api/files/item.php.md), [api/files/list.php](/code/files/api/files/list.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/prefs.php](/code/files/api/files/prefs.php.md), [api/files/principals.php](/code/files/api/files/principals.php.md), [api/files/properties.php](/code/files/api/files/properties.php.md), [api/files/recent.php](/code/files/api/files/recent.php.md), [api/files/search.php](/code/files/api/files/search.php.md), [api/files/settings.php](/code/files/api/files/settings.php.md), [api/files/shortcut.php](/code/files/api/files/shortcut.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md), [api/files/upload.php](/code/files/api/files/upload.php.md), [api/files/view.php](/code/files/api/files/view.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [filesAudit()](/code/functions/filesAudit.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

