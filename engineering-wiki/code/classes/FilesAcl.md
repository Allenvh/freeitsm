# FilesAcl

> Class in [includes/files/acl.php](/code/files/includes/files/acl.php.md), line 39

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

## Methods

| Method | What it does |
|---|---|
| [reset()](#reset) | Forget everything cached - call after changing folders or grants. |
| [levelKey()](#levelkey) | The short key for a permission level ('view', 'download', 'upload', 'modify', 'full'), or 'none'. |
| [tree()](#tree) | Every live (not deleted) folder, keyed by id. |
| [teamIds()](#teamids) | The teams an analyst is in (active teams only). |
| [levels()](#levels) | This analyst's level on every folder they can see at all: [folderId => level]. A folder missing from the result is invisible to them. |
| [level()](#level) | The analyst's effective level on one folder - 0 when they have no grant there. |
| [can()](#can) | True when the analyst's level on the folder is at least `$need` (levels are cumulative). |
| [entries()](#entries) | Every entry that applies to a folder, for the Permissions window: its own rows, then (while inheriting) each ancestor's, marked with where they came from. Names are resolved here so the window needs nothing else. |
| [watermark()](#watermark) | Is the viewer watermark on for files in this folder? The nearest folder that says on or off decides; NULL means "as the parent", and nothing set anywhere means off. Follows the folder tree, NOT permission inheritance - a folder that stops inheriting permissions still sits inside its parent. |
| [path()](#path) | "Finance / Invoices / 2026" - for audit rows, tooltips and the address bar. |
| [descendants()](#descendants) | Ids of a folder and everything beneath it (live folders only). |

### reset

*public static* - line 54

Forget everything cached - call after changing folders or grants.

```php
function reset(): void
```

**Called from:** [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md), [api/files/upload.php](/code/files/api/files/upload.php.md)

### levelKey

*public static* - line 65

The short key for a permission level ('view', 'download', 'upload', 'modify', 'full'), or 'none'.

```php
function levelKey(int $level): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$level` | `int` |  | a level 0-5 (FilesAcl::NONE to FilesAcl::FULL); anything else gives 'none' |

**Called from:** [api/files/download.php](/code/files/api/files/download.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

### tree

*public static* - line 78

Every live (not deleted) folder, keyed by id.

```php
function tree(PDO $conn): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `FilesAcl::tree` | read [files_folders](/code/tables/files_folders.md) |  |

**Called from:** [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/list.php](/code/files/api/files/list.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/search.php](/code/files/api/files/search.php.md), [api/files/settings.php](/code/files/api/files/settings.php.md), [api/files/shortcut.php](/code/files/api/files/shortcut.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

### teamIds

*public static* - line 115

The teams an analyst is in (active teams only).

```php
function teamIds(PDO $conn, int $analystId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 117 | `FilesAcl::teamIds` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |

### levels

*public static* - line 128

This analyst's level on every folder they can see at all: [folderId => level]. A folder missing from the result is invisible to them.

```php
function levels(PDO $conn, int $analystId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 136 | `FilesAcl::levels` | read [files_permissions](/code/tables/files_permissions.md) |  |

**Called from:** [api/files/list.php](/code/files/api/files/list.php.md), [api/files/recent.php](/code/files/api/files/recent.php.md), [api/files/search.php](/code/files/api/files/search.php.md), [api/files/settings.php](/code/files/api/files/settings.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

### level

*public static* - line 178

The analyst's effective level on one folder - 0 when they have no grant there.

```php
function level(PDO $conn, int $analystId, int $folderId): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the files_folders id; a deleted or unknown folder gives 0 |

**Called from:** [api/files/download.php](/code/files/api/files/download.php.md), [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/properties.php](/code/files/api/files/properties.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md), [api/files/view.php](/code/files/api/files/view.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

### can

*public static* - line 189

True when the analyst's level on the folder is at least `$need` (levels are cumulative).

```php
function can(PDO $conn, int $analystId, int $folderId, int $need): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the files_folders id; a deleted or unknown folder never passes |
| `$need` | `int` |  | the lowest level that will do, one of FilesAcl::VIEW to FilesAcl::FULL |

### entries

*public static* - line 201

Every entry that applies to a folder, for the Permissions window: its own rows, then (while inheriting) each ancestor's, marked with where they came from. Names are resolved here so the window needs nothing else.

```php
function entries(PDO $conn, int $folderId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the files_folders id; a deleted or unknown folder gives an empty list |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 216 | `FilesAcl::entries` | read [files_permissions](/code/tables/files_permissions.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |

**Called from:** [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md)

### watermark

*public static* - line 253

Is the viewer watermark on for files in this folder? The nearest folder that says on or off decides; NULL means "as the parent", and nothing set anywhere means off. Follows the folder tree, NOT permission inheritance - a folder that stops inheriting permissions still sits inside its parent.

```php
function watermark(PDO $conn, int $folderId): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the files_folders id of the folder holding the file; unknown gives false |

**Called from:** [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/view.php](/code/files/api/files/view.php.md)

### path

*public static* - line 270

"Finance / Invoices / 2026" - for audit rows, tooltips and the address bar.

```php
function path(PDO $conn, ?int $folderId): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `?int` |  | the files_folders id; null (the top level) or a deleted folder gives '' |

**Called from:** [api/files/folder.php](/code/files/api/files/folder.php.md), [api/files/list.php](/code/files/api/files/list.php.md), [api/files/permissions.php](/code/files/api/files/permissions.php.md), [api/files/properties.php](/code/files/api/files/properties.php.md), [api/files/recent.php](/code/files/api/files/recent.php.md), [api/files/search.php](/code/files/api/files/search.php.md), [api/files/settings.php](/code/files/api/files/settings.php.md), [api/files/shortcut.php](/code/files/api/files/shortcut.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), [includes/files/audit.php](/code/files/includes/files/audit.php.md)

### descendants

*public static* - line 289

Ids of a folder and everything beneath it (live folders only).

```php
function descendants(PDO $conn, int $folderId): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$folderId` | `int` |  | the files_folders id at the top; it is always in the result, even if unknown |

**Called from:** [api/files/properties.php](/code/files/api/files/properties.php.md), [api/files/transfer.php](/code/files/api/files/transfer.php.md)

