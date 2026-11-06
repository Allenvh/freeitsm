# lmsAssignmentReachSql()

THE ONE PLACE THAT SAYS WHICH ASSIGNMENTS REACH A LEARNER.

Returns [sql, params] for a WHERE fragment against `lms_course_assignments ca`,
covering all three kinds of target:

  learning_group  an analyst learning group. ANALYSTS ONLY — a portal user is
                  not in `analysts` and can never match one, which is why the
                  branch is omitted entirely rather than left to return no rows.
  user_group      a shared people group, which holds both kinds of member. The
                  EXPIRY IS APPLIED HERE, at read time, exactly as Knowledge
                  does it: somebody given a fortnight's training access loses
                  it by the clock rather than by anyone remembering.
  all_users       every portal user. Matches any 'user' learner and no analyst
                  — "push it to the portal" means the portal.

🔑 Every caller composes this rather than writing its own joins. The old code
spelled the same three-table join out in five places; they agreed then, and
the first person to add a target type would have found out that they don't.

```php
function lmsAssignmentReachSql(PDO $conn, LmsLearner $learner): array
```

Defined in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 229.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$learner` | `LmsLearner` |  | the person whose assignments are wanted |

**Returns:** array{0:string,1:array}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 235 | `lmsAssignmentReachSql` | read [lms_learning_groups](/code/tables/lms_learning_groups.md), read [lms_learning_group_members](/code/tables/lms_learning_group_members.md) |  |
| 253 | `lmsAssignmentReachSql` | read [knowledge_user_groups](/code/tables/knowledge_user_groups.md), read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md) |  |

## Calls

[lmsUserGroupsAvailable()](/code/functions/lmsUserGroupsAvailable.md)

## Called from

- [includes/lms_access.php](/code/files/includes/lms_access.php.md) - in [lmsCourseAssignedTo()](/code/functions/lmsCourseAssignedTo.md), [lmsMyCourses()](/code/functions/lmsMyCourses.md)
- [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

