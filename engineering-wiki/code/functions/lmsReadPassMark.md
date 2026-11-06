# lmsReadPassMark()

Read a pass mark off the request. Null means "no pass mark" — the course is marked complete on reaching the end rather than passed/failed. Anything outside 0-100 is a bad request rather than something to silently clamp: a course whose pass mark isn't what the author typed is worse than an error.

```php
function lmsReadPassMark(array $input, &$error)
```

Defined in [api/lms/course.php](/code/files/api/lms/course.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$input` | `array` |  | the decoded request; pass_mark is read if present |
| `$error` |  |  | set by reference to a message when pass_mark is not a whole number from 0 to 100 |

## Called from

- [api/lms/course.php](/code/files/api/lms/course.php.md)

