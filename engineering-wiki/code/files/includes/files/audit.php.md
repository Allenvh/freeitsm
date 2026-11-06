# includes/files/audit.php

> PHP - 79 lines

Files — the audit trail. The ONE writer of files_audit.

Ed's rule: EVERY action is audited - opening a folder, viewing, downloading,
uploading, renaming, moving, copying, deleting, permission changes, taking
ownership, and every refusal. Unlike Knowledge (which keeps one view row per
person per day) nothing is collapsed: the point of this module is being able
to say exactly when somebody looked at something.

The person's name and the item's path are COPIED into the row, because the
trail has to outlive the analyst, the file and the folder it mentions.

Best effort: an unwritable log must never fail the action - but the failure
goes to error_log, because a silently broken audit table is the one failure
nobody would notice.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [filesAudit()](/code/functions/filesAudit.md) | 41 | Writes one row to the Files audit log, with the analyst's name, the target's path, IP address and user agent. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `filesAudit` | read [analysts](/code/tables/analysts.md) |  |
| 60 | `filesAudit` | **insert** [files_audit](/code/tables/files_audit.md) (`analyst_id`, `analyst_name`, `action`, `folder_id`, `item_id`, `version_id`, `target_path`, `detail`, `ip_address`, `user_agent`, `created_datetime`) |  |

## How it hangs together

**Includes:** [includes/files/acl.php](/code/files/includes/files/acl.php.md)

**Included by:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

