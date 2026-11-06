<?php
/**
 * MFA attempt throttling that survives the attacker throwing the session away.
 *
 * ── The bug this exists to close (S6, reported by Erlend Volden) ──────────────
 *
 * The MFA code step counted its failures in $_SESSION. The session is the one
 * piece of state the person guessing controls completely, so the counter was
 * advisory: five wrong codes abandoned the challenge, the attacker dropped the
 * cookie, presented the password again, and got five fresh guesses.
 *
 * The original code argued that this was acceptable because re-presenting the
 * password puts you back on a path protected by account lockout and the IP ban.
 * That argument was wrong for one specific reason, and it is worth stating
 * plainly because it is the kind of thing that reads as safe:
 *
 *     A SUCCESSFUL password step RESETS failed_login_count and locked_until
 *     (auth/login.php). An attacker who already holds a valid password never
 *     trips account lockout at all — every loop begins with a success.
 *
 * So the cost of unlimited guesses at a six-digit code was one extra request per
 * five attempts. A million codes is a weekend, not a wall.
 *
 * ── What this does instead ───────────────────────────────────────────────────
 *
 * The count lives on the account row, where the person guessing cannot reach it,
 * and NOTHING on the password path clears it. Only entering a correct code does.
 *
 * ⚠️ THE SESSION COUNTER IS DELIBERATELY LEFT IN PLACE ALONGSIDE THIS. It is not
 * redundant and it is not dead code: it abandons the challenge cheaply for the
 * ordinary case, and — more importantly — it is what still protects an install
 * whose database has not been migrated yet (see the degrade rule below). Two
 * counters, different weaknesses, neither trusted alone.
 *
 * ── The degrade rule, and why it goes this way ───────────────────────────────
 *
 * If mfa_failed_count / mfa_locked_until are missing (an install that has not run
 * Database Verification since this shipped), every function here degrades to
 * "no opinion" and logs once. It does NOT fail closed.
 *
 * Failing closed here would refuse every MFA code on the install until an
 * administrator ran a migration — locking out exactly the security-conscious
 * users who enabled MFA, with no way back in. That is a worse outcome than
 * reverting to the session counter, which is what the app did until today.
 * Fail-closed is right for a data-access guard; it is wrong for the lock on the
 * front door.
 */

/** Tables that carry the MFA counter columns. Whitelist — never a caller string in SQL. */
const MFA_THROTTLE_TABLES = ['analysts', 'users'];

/** Default threshold when account lockout is switched off entirely. */
const MFA_THROTTLE_FALLBACK_THRESHOLD = 5;

/** Default lockout window in minutes. */
const MFA_THROTTLE_FALLBACK_MINUTES = 30;

/**
 * Guard the table name. Returns the safe literal or null.
 * Interpolating a caller-supplied table into SQL is how a helper like this turns
 * into an injection point, so the name never travels — only the choice does.
 *
 * @param string $table  the account table asked for - only 'analysts' or 'users' pass
 */
function mfaThrottleTable(string $table): ?string
{
    return in_array($table, MFA_THROTTLE_TABLES, true) ? $table : null;
}

/**
 * One system_settings read, with its own guard. Mirrors getSecuritySetting() in auth/login.php.
 *
 * @param string $key  the system_settings setting_key to read
 */
function mfaThrottleSetting(PDO $conn, string $key): ?string
{
    try {
        $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (string) $row['setting_value'] : null;
    } catch (Exception) {
        return null;
    }
}

/**
 * How many wrong codes before the code step locks?
 *
 * Follows the administrator's account-lockout policy so there is one number to
 * reason about — but with a floor, because max_failed_logins = 0 means "do not
 * lock accounts on bad passwords", and reading that as "allow unlimited MFA code
 * guessing" would reinstate the bug through a settings screen.
 */
function mfaThrottleThreshold(PDO $conn): int
{
    $configured = (int) (mfaThrottleSetting($conn, 'max_failed_logins') ?? 0);
    return $configured > 0 ? $configured : MFA_THROTTLE_FALLBACK_THRESHOLD;
}

/** How long the code step stays locked, in minutes. */
function mfaThrottleLockoutMinutes(PDO $conn): int
{
    $configured = (int) (mfaThrottleSetting($conn, 'lockout_duration_minutes') ?? 0);
    return $configured > 0 ? $configured : MFA_THROTTLE_FALLBACK_MINUTES;
}

/**
 * Is this account's code step locked right now? Returns whole minutes remaining,
 * or 0 when it is free to try. Degrades to 0 when the columns are absent.
 *
 * @param string $table  the account table: 'analysts' or 'users'
 * @param int $accountId  the account's id in that table; 0 or less always gives 0
 */
function mfaThrottleMinutesRemaining(PDO $conn, string $table, int $accountId): int
{
    $t = mfaThrottleTable($table);
    if ($t === null || $accountId <= 0) {
        return 0;
    }
    try {
        $stmt = $conn->prepare("SELECT mfa_locked_until FROM {$t} WHERE id = ?");
        $stmt->execute([$accountId]);
        $until = $stmt->fetchColumn();
        if (!$until) {
            return 0;
        }
        $untilTs = strtotime((string) $until . ' UTC');
        $nowTs   = time();
        return $untilTs > $nowTs ? max(1, (int) ceil(($untilTs - $nowTs) / 60)) : 0;
    } catch (Exception $e) {
        mfaThrottleWarnOnce($e);
        return 0;
    }
}

/**
 * Record one wrong code. Returns:
 *   ['locked' => bool, 'minutes' => int, 'attempts' => int, 'threshold' => int, 'tracked' => bool]
 *
 * `tracked` is false when the columns are missing, so the caller can tell
 * "counted" from "could not count" rather than reading 0 as a clean slate.
 *
 * @param string $table  the account table: 'analysts' or 'users'
 * @param int $accountId  the account's id in that table; 0 or less counts nothing
 */
function mfaThrottleRecordFailure(PDO $conn, string $table, int $accountId): array
{
    $threshold = mfaThrottleThreshold($conn);
    $out = ['locked' => false, 'minutes' => 0, 'attempts' => 0, 'threshold' => $threshold, 'tracked' => false];

    $t = mfaThrottleTable($table);
    if ($t === null || $accountId <= 0) {
        return $out;
    }

    try {
        // Increment in the statement itself. Read-then-write would let two
        // requests racing the same account each read 4 and each write 5.
        $conn->prepare("UPDATE {$t} SET mfa_failed_count = mfa_failed_count + 1 WHERE id = ?")
             ->execute([$accountId]);

        $stmt = $conn->prepare("SELECT mfa_failed_count FROM {$t} WHERE id = ?");
        $stmt->execute([$accountId]);
        $attempts = (int) $stmt->fetchColumn();

        $out['tracked']  = true;
        $out['attempts'] = $attempts;

        if ($attempts >= $threshold) {
            $minutes = mfaThrottleLockoutMinutes($conn);
            $conn->prepare(
                "UPDATE {$t} SET mfa_locked_until = DATE_ADD(UTC_TIMESTAMP(), INTERVAL ? MINUTE) WHERE id = ?"
            )->execute([$minutes, $accountId]);
            $out['locked']  = true;
            $out['minutes'] = $minutes;
        }
    } catch (Exception $e) {
        mfaThrottleWarnOnce($e);
    }

    return $out;
}

/**
 * A correct code was entered: clear the count and any lock.
 *
 * ⚠️ This is the ONLY thing that clears it. In particular the password step must
 * never call it — that is precisely the reset the attack relied on.
 *
 * @param string $table  the account table: 'analysts' or 'users'
 * @param int $accountId  the account's id in that table
 */
function mfaThrottleReset(PDO $conn, string $table, int $accountId): void
{
    $t = mfaThrottleTable($table);
    if ($t === null || $accountId <= 0) {
        return;
    }
    try {
        $conn->prepare("UPDATE {$t} SET mfa_failed_count = 0, mfa_locked_until = NULL WHERE id = ?")
             ->execute([$accountId]);
    } catch (Exception $e) {
        mfaThrottleWarnOnce($e);
    }
}

/**
 * Log the missing-column case once per request rather than on every keystroke,
 * so a un-migrated install leaves one readable line instead of a flooded log.
 *
 * @param Exception $e  the exception from the failed query, normally a missing mfa_* column
 */
function mfaThrottleWarnOnce(Exception $e): void
{
    static $warned = false;
    if ($warned) {
        return;
    }
    $warned = true;
    error_log('mfa_throttle: falling back to the session counter — ' . $e->getMessage()
              . ' (run Database Verification to add mfa_failed_count / mfa_locked_until)');
}
