# includes/mfa_throttle.php

> PHP - 224 lines

MFA attempt throttling that survives the attacker throwing the session away.

── The bug this exists to close (S6, reported by Erlend Volden) ──────────────

The MFA code step counted its failures in `$_SESSION`. The session is the one
piece of state the person guessing controls completely, so the counter was
advisory: five wrong codes abandoned the challenge, the attacker dropped the
cookie, presented the password again, and got five fresh guesses.

The original code argued that this was acceptable because re-presenting the
password puts you back on a path protected by account lockout and the IP ban.
That argument was wrong for one specific reason, and it is worth stating
plainly because it is the kind of thing that reads as safe:

    A SUCCESSFUL password step RESETS failed_login_count and locked_until
    (auth/login.php). An attacker who already holds a valid password never
    trips account lockout at all — every loop begins with a success.

So the cost of unlimited guesses at a six-digit code was one extra request per
five attempts. A million codes is a weekend, not a wall.

── What this does instead ───────────────────────────────────────────────────

The count lives on the account row, where the person guessing cannot reach it,
and NOTHING on the password path clears it. Only entering a correct code does.

⚠️ THE SESSION COUNTER IS DELIBERATELY LEFT IN PLACE ALONGSIDE THIS. It is not
redundant and it is not dead code: it abandons the challenge cheaply for the
ordinary case, and — more importantly — it is what still protects an install
whose database has not been migrated yet (see the degrade rule below). Two
counters, different weaknesses, neither trusted alone.

── The degrade rule, and why it goes this way ───────────────────────────────

If mfa_failed_count / mfa_locked_until are missing (an install that has not run
Database Verification since this shipped), every function here degrades to
"no opinion" and logs once. It does NOT fail closed.

Failing closed here would refuse every MFA code on the install until an
administrator ran a migration — locking out exactly the security-conscious
users who enabled MFA, with no way back in. That is a worse outcome than
reverting to the session counter, which is what the app did until today.
Fail-closed is right for a data-access guard; it is wrong for the lock on the
front door.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mfaThrottleTable()](/code/functions/mfaThrottleTable.md) | 65 | Guard the table name. Returns the safe literal or null. Interpolating a caller-supplied table into SQL is how a helper like this turns into an injection point, so the name never travels — only the choice does. |
| [mfaThrottleSetting()](/code/functions/mfaThrottleSetting.md) | 75 | One system_settings read, with its own guard. Mirrors `getSecuritySetting()` in auth/login.php. |
| [mfaThrottleThreshold()](/code/functions/mfaThrottleThreshold.md) | 95 | How many wrong codes before the code step locks? |
| [mfaThrottleLockoutMinutes()](/code/functions/mfaThrottleLockoutMinutes.md) | 102 | How long the code step stays locked, in minutes. |
| [mfaThrottleMinutesRemaining()](/code/functions/mfaThrottleMinutesRemaining.md) | 115 | Is this account's code step locked right now? Returns whole minutes remaining, or 0 when it is free to try. Degrades to 0 when the columns are absent. |
| [mfaThrottleRecordFailure()](/code/functions/mfaThrottleRecordFailure.md) | 147 | Record one wrong code. Returns: ['locked' => bool, 'minutes' => int, 'attempts' => int, 'threshold' => int, 'tracked' => bool] |
| [mfaThrottleReset()](/code/functions/mfaThrottleReset.md) | 194 | A correct code was entered: clear the count and any lock. |
| [mfaThrottleWarnOnce()](/code/functions/mfaThrottleWarnOnce.md) | 214 | Log the missing-column case once per request rather than on every keystroke, so a un-migrated install leaves one readable line instead of a flooded log. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `mfaThrottleSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Included by:** [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md), [api/self-service/verify_login_otp.php](/code/files/api/self-service/verify_login_otp.php.md)

