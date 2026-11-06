# LmsLearner

> Class in [includes/lms_access.php](/code/files/includes/lms_access.php.md), line 32

WHO IS TAKING A COURSE.

The LMS used to know one kind of learner — an analyst — and passed a bare
`int `$analystId`` everywhere. A course can now be given to a self-service
portal user, who lives in `users` and has no analyst record at all, so the
identity has to carry WHICH TABLE it means. That is this class, and it is why
every function below takes one instead of an int.

🔑 A CLASS RATHER THAN TWO NULLABLE INTS. The alternative was
`(?int `$analystId`, ?int `$userId`)` with an unwritten "exactly one of these"
rule — the shape the portal password-reset tables were deliberately split to
avoid, for the same reason: nothing enforces it, and the day somebody passes
both, or neither, the LMS shows one person another person's training record.
Here the object cannot be constructed in an invalid state.

## Methods

| Method | What it does |
|---|---|
| [__construct()](#__construct) | Private: learners are built with `analyst()` or `user()`. |
| [analyst()](#analyst) | A learner for an analyst id, or null when the id is not positive. |
| [user()](#user) | A learner for a self-service user id, or null when the id is not positive. |
| [fromSession()](#fromsession) | Whoever is signed in, from either front door. |
| [fromRequest()](#fromrequest) | The learner this REQUEST is acting as. |
| [requestWantsPortal()](#requestwantsportal) | Did this request come through the portal? Read from the query string even on a POST, so one rule covers the JSON-bodied endpoints as well. |
| [type()](#type) | Whether the learner is an analyst or a self-service user. |
| [id()](#id) | The analysts.id or users.id of the learner, depending on `type()`. |
| [isAnalyst()](#isanalyst) | True when the learner is an analyst. |
| [key()](#key) | 'analyst:12' — for logs and array keys, never for storage. |

### __construct

*private* - line 48

Private: learners are built with `analyst()` or `user()`.

```php
function __construct(string $type, int $id)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | 'analyst' or 'user' (self::ANALYST or self::USER) |
| `$id` | `int` |  | the analysts.id or users.id, matching `$type` |

### analyst

*public static* - line 59

A learner for an analyst id, or null when the id is not positive.

```php
function analyst(int $id): ?self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `int` |  | the analysts.id; 0 or less gives null |

**Called from:** [lms/player.php](/code/files/lms/player.php.md)

### user

*public static* - line 69

A learner for a self-service user id, or null when the id is not positive.

```php
function user(int $id): ?self
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `int` |  | the self-service users.id; 0 or less gives null |

**Called from:** [includes/people.php](/code/files/includes/people.php.md), [self-service/course.php](/code/files/self-service/course.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md)

### fromSession

*public static* - line 97

Whoever is signed in, from either front door.

🔴 ONE BROWSER REALLY DOES HOLD BOTH IDENTITIES. The analyst app and the
self-service portal are the same host and share one PHP session, so
anybody who signs into both — which is every administrator who has ever
looked at the portal — has `analyst_id` AND `ss_user_id` sitting side by
side. There is then no such thing as "who is signed in": there are two
answers, and only the REQUEST knows which one is acting.

⚠️ THIS USED TO PREFER THE ANALYST UNCONDITIONALLY, and the reasoning was
wrong. Measured: an administrator opened the portal's own Training page
and was shown the ADMINISTRATOR's ten courses, complete with their scores
and their overdue flags, under a heading saying "Courses you have been
asked to complete". Worse than the wrong list — the progress endpoints
resolve the same way, so taking one of those courses from the portal would
have written the attempt onto the analyst's training record while the page
that let them in had gated it as the portal user. Two identities, and the
gate and the writer disagreeing about which one was acting.

🔑 So the CONTEXT decides, via `fromRequest()` below, and this remains the
plain analyst-first reading for the analyst app's own pages.

```php
function fromSession(): ?self
```

### fromRequest

*public static* - line 117

The learner this REQUEST is acting as.

The LMS endpoints are shared by the analyst app and the portal, so they
have to be told which front door a call came through: `as=portal` means
the portal, anything else means the analyst app.

🔑 THIS CANNOT BE USED TO BECOME SOMEBODY ELSE, which is why a plain query
parameter is safe here. It does not name an identity — it chooses between
the identities ALREADY PROVEN by this session's own cookies. Somebody with
no portal login who passes `as=portal` gets their analyst identity back,
or nothing; they cannot reach a `users` row they have not signed in as.

```php
function fromRequest(): ?self
```

**Called from:** [api/lms/course_content.php](/code/files/api/lms/course_content.php.md), [api/lms/my_courses.php](/code/files/api/lms/my_courses.php.md), [api/lms/native_progress.php](/code/files/api/lms/native_progress.php.md), [api/lms/scorm_data.php](/code/files/api/lms/scorm_data.php.md), [includes/lms_access.php](/code/files/includes/lms_access.php.md)

### requestWantsPortal

*private static* - line 129

Did this request come through the portal? Read from the query string even on a POST, so one rule covers the JSON-bodied endpoints as well.

```php
function requestWantsPortal(): bool
```

### type

*public* - line 135

Whether the learner is an analyst or a self-service user.

```php
function type(): string
```

### id

*public* - line 137

The analysts.id or users.id of the learner, depending on `type()`.

```php
function id(): int
```

### isAnalyst

*public* - line 139

True when the learner is an analyst.

```php
function isAnalyst(): bool
```

### key

*public* - line 142

'analyst:12' — for logs and array keys, never for storage.

```php
function key(): string
```

