# leaseDelete()

Delete a lease, its schedules and its asset rows. The assets themselves keep the lease dates they were given - deleting a record of the agreement does not rewrite what the asset says happened.

```php
function leaseDelete(PDO $conn, ActorContext $ctx, int $id): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 312.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the leases id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 315 | `leaseDelete` | **delete** [leases](/code/tables/leases.md) |  |

## Calls

[documentsDetachParent()](/code/functions/documentsDetachParent.md), [leaseLoad()](/code/functions/leaseLoad.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)

## Called from

- [api/assets/leases/delete.php](/code/files/api/assets/leases/delete.php.md)

