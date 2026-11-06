# enforcePasswordChangeGate()

Stop an analyst who owes a password change from doing anything else.

Deliberately narrow:
  - only when `$_SESSION`['password_expired'] is set, which only login sets;
  - only for ANALYST sessions (portal users have no such flow);
  - never for the handful of scripts they need in order to comply or leave.

API callers get a 403 with a machine-readable marker rather than a redirect,
because a `fetch()` following a 302 to an HTML page produces a baffling error.

```php
function enforcePasswordChangeGate(): void
```

Defined in [includes/password_gate.php](/code/files/includes/password_gate.php.md), line 32.

**Returns:** `void`

## Called from

- [includes/password_gate.php](/code/files/includes/password_gate.php.md)

