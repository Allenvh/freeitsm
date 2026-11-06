# tenancyDegradeAllowed()

May an analystCanAccess*() guard degrade to "yes" after a database error?

⚠️ Every one of those guards used to end with a bare:

    } catch (Exception `$e`) {
        return true; // tenant_id column missing on a part-migrated install.
    }

The intent was right — an install that has not run Database Verify has no
tenant_id column, and must keep working exactly as it did before multi-tenancy.
The catch was the problem: it was untyped, so a lock-wait timeout, a dropped
connection, a permissions error or a deadlock all looked identical to "not
migrated yet", and every one of them GRANTED cross-tenant access. A transient
database blip was an authorisation bypass.

Only the two errors that genuinely mean "the schema hasn't caught up" are
forgiven. Anything else denies and is logged, because a guard that cannot say
no under load is not a guard.

```php
function tenancyDegradeAllowed(Exception $e): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `Exception` |  | the exception a tenancy guard's query threw |

**Returns:** `bool`

## Calls

[dbErrorIsMissingSchema()](/code/functions/dbErrorIsMissingSchema.md)

## Called from

- [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) - in `TasksService::taskAccessible`, `TasksService::ticketAccessible`
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [analystCanAccessUser()](/code/functions/analystCanAccessUser.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessProject()](/code/functions/analystCanAccessProject.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTask()](/code/functions/analystCanAccessTask.md), [analystCanAccessArticle()](/code/functions/analystCanAccessArticle.md)

