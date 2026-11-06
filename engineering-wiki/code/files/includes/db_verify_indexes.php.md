# includes/db_verify_indexes.php

> PHP - 540 lines

GENERATED — do not edit by hand.

Every named secondary index in database/freeitsm.sql: [table, name, type,
columns], where type is 'unique', 'fulltext' or 'key'. Consumed by
api/system/db_verify.php to restore indexes a grown install is missing.
Regenerate with scripts/gen_db_verify_indexes.php after changing an index in
freeitsm.sql.

## How it hangs together

**Included by:** [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

