# api/cmdb/get_audit.php

> API endpoint - 31 lines

API: Run the CMDB data-quality audit for the analyst's current company.

Read-only and advisory — it reports, it never edits. See includes/cmdb_audit.php
for what each check means and why it is framed around impact analysis.

## The endpoint

| | |
|---|---|
| URL | `api/cmdb/get_audit.php` |
| Protected by | Module access: cmdb (requireModuleAccessJson) |
| Called from | [cmdb/audit.js](/code/files/cmdb/audit.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/cmdb_audit.php](/code/files/includes/cmdb_audit.php.md)

**Calls:** [cmdbRunAudit()](/code/functions/cmdbRunAudit.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

