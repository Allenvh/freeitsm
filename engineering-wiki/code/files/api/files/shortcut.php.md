# api/files/shortcut.php

> API endpoint - 207 lines

Shortcuts - Windows-style links to a file or folder.

POST {action: create, target_type: folder|item|article, target_id, where: folder|desktop, folder_id?}
       folder  = beside the target, in the folder that holds it (Upload there).
                 Not for a top-level folder - there is nowhere beside it.
                 An article (Knowledge) has no folder of its own, so it goes in
                 folder_id instead.
       desktop = on the caller's own desktop. Nobody else sees it.
       Either way the caller must be able to see the target.
  POST {action: rename, id, name}   folder shortcut: Modify on its folder;
                                    desktop shortcut: its owner.
  POST {action: delete, id}         the same. The target is never touched.
  POST {action: place, id, to: <folder id>|desktop, copy?}  drag and drop:
       move it (or copy it, Ctrl) into a folder (Upload there) or onto the
       caller's own desktop.
  GET  ?desktop=1                   the caller's desktop shortcuts, each with
                                    whether its target can still be opened.
  GET  ?articles=1&q=               Knowledge articles the caller can read, for
                                    the "Shortcut to article" picker.
  GET  ?article=<id>                one of them, title and body, for the preview
                                    window an article shortcut opens.

⚠️ A SHORTCUT GRANTS NOTHING. Opening one goes through the target's own
permissions. In a folder listing (list.php) a shortcut whose target the viewer
cannot see is left out altogether - otherwise its name would tell them the
target exists, which is exactly what "hidden means not found" forbids.

## The endpoint

| | |
|---|---|
| URL | `api/files/shortcut.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET article`, `GET articles`, `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 59 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 68 |  | read [files_shortcuts](/code/tables/files_shortcuts.md) |  |
| 104 |  | **insert** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime`) |  |
| 119 |  | **insert** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime`) |  |
| 128 |  | read [files_shortcuts](/code/tables/files_shortcuts.md) |  |
| 159 |  | **insert** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime`) |  |
| 163 |  | **update** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`) |  |
| 175 |  | **insert** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`, `target_type`, `target_id`, `name`, `created_by`, `created_datetime`) |  |
| 179 |  | **update** [files_shortcuts](/code/tables/files_shortcuts.md) (`folder_id`, `analyst_id`, `name`) |  |
| 194 |  | **update** [files_shortcuts](/code/tables/files_shortcuts.md) (`name`) |  |
| 200 |  | **delete** [files_shortcuts](/code/tables/files_shortcuts.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesArticleTarget()](/code/functions/filesArticleTarget.md), [filesAudit()](/code/functions/filesAudit.md), [filesCleanName()](/code/functions/filesCleanName.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesNameTaken()](/code/functions/filesNameTaken.md), [filesNeed()](/code/functions/filesNeed.md), [filesShortcutTarget()](/code/functions/filesShortcutTarget.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

