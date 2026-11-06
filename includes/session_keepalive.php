<?php
/**
 * Stop PHP's garbage collector deleting the session of somebody who is actively
 * using FreeITSM (GH #107).
 *
 * ⚠️ WHY THIS EXISTS. PHP's file session handler decides a session is idle from
 * the session FILE'S TIMESTAMP, and deletes anything older than
 * `session.gc_maxlifetime` (24 minutes by default). Nothing about that timestamp
 * is related to whether the person is still there — it is only refreshed when
 * PHP closes a session at the end of a request.
 *
 * That is normally invisible, because `session.lazy_write` (on by default) makes
 * the close do exactly the right thing: when the session data has not changed it
 * skips the pointless rewrite but still TOUCHES the file, so an active session
 * keeps its timestamp fresh. Ordinary page loads therefore keep you signed in.
 *
 * `session_start(['read_and_close' => true])` never reaches that close. It reads
 * the data and closes the session immediately — which is the whole point, since
 * it releases the session lock so one slow endpoint cannot block every other
 * request from the same browser. But it also means **the timestamp is never
 * refreshed**, so a request through such an endpoint keeps the session alive in
 * every sense except the one the garbage collector measures.
 *
 * 717 endpoints open the session that way. Anywhere you can work for 24 minutes
 * without a full page load — the Tasks board being the clearest example, since it
 * is a single page and every edit is an API call — the file quietly ages past
 * gc_maxlifetime while you work. Then any request has a 1-in-100 chance of
 * running the collector, which deletes your live session, and the next click puts
 * you on the login screen having lost nothing but your place.
 *
 * ── Why it looked like a Docker bug ──────────────────────────────────────────
 * It is not one. NOBODY IS IMMUNE — the odds simply differ by orders of
 * magnitude, which is exactly what makes it look environmental:
 *
 *   official php:*-apache      gc_probability 1, gc_divisor 100   → 1 in 100
 *                              requests runs the collector, so it fires during
 *                              an ordinary afternoon's work.
 *   WAMP (php.ini-development) probability 1, divisor 1000        → 1 in 1000.
 *                              Ten times rarer, and a developer reloading pages
 *                              constantly restarts the clock before it matters.
 *   Debian / Ubuntu packages   probability 0 — the in-process collector never
 *                              runs. But /usr/lib/php/sessionclean sweeps from
 *                              cron every 30 minutes using `find -cmin`, and
 *                              ctime is no more refreshed by a read than mtime
 *                              is. So the SAME blind spot exists; it can only
 *                              bite on a cron tick, which is rare enough to look
 *                              like immunity in any short test.
 *
 * The trigger needs both halves: 24 minutes of work with no page load, AND the
 * collector happening to run. Change either and the bug disappears, which is why
 * two people testing the same version in good faith could not reproduce it.
 *
 * ── What this does ───────────────────────────────────────────────────────────
 * Restores the timestamp update that `read_and_close` skips, and nothing else.
 * A session that is genuinely idle is still collected on exactly the old
 * schedule, because this only runs when a request is actually being served —
 * which is the correct definition of "still in use", and the one PHP intended.
 */

/**
 * Refresh the current session file's timestamp, if there is one to refresh.
 *
 * Runs on include, like the other session concerns in functions.php, because
 * every one of those 717 endpoints already includes it — which is also why this
 * must stay cheap: one stat and one touch, no session lock, no re-open.
 */
function sessionRefreshGcTimestamp(): void
{
    if (PHP_SAPI === 'cli') {
        return;                      // cron has no session to keep alive
    }

    // A session left OPEN needs no help: PHP updates the timestamp itself when it
    // closes at the end of the request. Only the already-closed case is the gap,
    // so this deliberately does nothing on ordinary page loads.
    if (session_status() !== PHP_SESSION_NONE) {
        return;
    }

    // Nothing to do for redis/memcached/database handlers — their own expiry is
    // time-based rather than file-timestamp based, and there is no file to touch.
    if (strtolower((string)ini_get('session.save_handler')) !== 'files') {
        return;
    }

    // Survives read_and_close, and is PHP's own validated id rather than anything
    // the client sent — so it is safe to build a path from. Checked anyway,
    // because a path assembled from a session id is exactly the sort of thing
    // that must never become attacker-controlled by a later refactor.
    $id = session_id();
    if ($id === '' || $id === false || !preg_match('/^[A-Za-z0-9,\-]{16,256}$/', (string)$id)) {
        return;
    }

    $path = (string)ini_get('session.save_path');

    // save_path may be "N;/path" or "N;MODE;/path", where N spreads the files over
    // nested directories. Working out the nested path means reimplementing PHP's
    // hashing, so those installs are left alone rather than guessed at — they keep
    // exactly the behaviour they have today.
    if ($path !== '' && str_contains($path, ';')) {
        $parts = explode(';', $path);
        if ((int)$parts[0] > 0) {
            return;
        }
        $path = (string)end($parts);
    }
    if ($path === '') {
        $path = sys_get_temp_dir();   // what PHP itself falls back to
    }

    $file = rtrim($path, '/\\') . DIRECTORY_SEPARATOR . 'sess_' . $id;

    // Silent on failure by design: a read-only or unwritable session directory is
    // somebody else's problem to report, and it must never turn a working request
    // into a fatal one.
    if (is_file($file)) {
        @touch($file);
    }
}

sessionRefreshGcTimestamp();
