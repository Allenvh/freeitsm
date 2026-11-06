# includes/files/acl.php

> PHP - 307 lines

Files — who may do what to a folder. The ONE place that answers it.

─────────────────────────────────────────────────────────────────────────────
THE MODEL (agreed with Ed, 2026-10-10)
─────────────────────────────────────────────────────────────────────────────
  PESSIMISTIC. Nothing is visible to anybody until a row in files_permissions
  grants it. There is no "everyone" principal, no admin bypass and no Deny.

  Levels are cumulative - each includes every level below it:
    1 View          see it, open it in the viewer
    2 Download      take a copy away
    3 Upload        add files and subfolders, upload a new version
    4 Modify        rename, move, delete
    5 Full control  change permissions, inheritance and the watermark

  A folder with inherit_permissions = 1 also carries every entry of its
  parent (and so on up). Turning inheritance off stops that at this folder.
  A person's level on a folder is the HIGHEST of every entry that applies to
  them directly or through any team they are in.

  Files have no permissions of their own - a file takes its folder's.

⚠️ Admins are NOT special here. Cap::FILES_FOLDERS (which admins hold, like
every capability) lets someone TAKE OWNERSHIP - add a Full control row for
themselves, audited - but until they do, they see no more than anyone else.
Never add an is_admin shortcut to this file.

⚠️ HIDDEN MEANS NOT FOUND. A folder you cannot View does not exist for you:
endpoints answer "not found", never "access denied", so its existence cannot
be probed one id at a time.

Performance: the whole tree and the asker's grants are read once per request
(two queries) and resolved in PHP. Trees of tens of thousands of folders are
fine; if that ever stops being true, materialise levels per principal.

## Classes

- [FilesAcl](/code/classes/FilesAcl.md) (class, line 39) - Files — who may do what to a folder. The ONE place that answers it.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `FilesAcl::tree` | read [files_folders](/code/tables/files_folders.md) |  |
| 117 | `FilesAcl::teamIds` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 136 | `FilesAcl::levels` | read [files_permissions](/code/tables/files_permissions.md) |  |
| 216 | `FilesAcl::entries` | read [files_permissions](/code/tables/files_permissions.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

## How it hangs together

**Included by:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), [includes/files/audit.php](/code/files/includes/files/audit.php.md)

