<?php
/**
 * Telling one database error from another.
 *
 * Several places in the app need to tolerate a schema that hasn't caught up
 * yet — a table Database Verify hasn't created, a column an older install is
 * still missing — while NOT tolerating anything else. Written as a bare
 * `catch (Exception $e)` that assumption quietly widens: a lock-wait timeout,
 * a dropped connection or a permissions error all look identical to "not
 * migrated yet", and whatever the catch block does for a fresh install it now
 * also does during a database hiccup on a live one.
 *
 * That is fail-open by accident, and it is how includes/tenancy.php came to
 * grant cross-tenant access on any transient error. These helpers exist so a
 * catch block can say precisely which error it is willing to absorb.
 *
 * Codes are MySQL's, matched on SQLSTATE first (portable, and always present
 * on a PDOException) with the driver-specific code as a second opinion.
 */

/**
 * "That table doesn't exist." MySQL: SQLSTATE 42S02, driver code 1146.
 *
 * @param PDOException $e  the exception a query threw
 */
function dbErrorIsMissingTable(PDOException $e): bool
{
    if ($e->getCode() === '42S02') return true;
    return (int)($e->errorInfo[1] ?? 0) === 1146;
}

/**
 * "That column doesn't exist." MySQL: SQLSTATE 42S22, driver code 1054.
 *
 * @param PDOException $e  the exception a query threw
 */
function dbErrorIsUnknownColumn(PDOException $e): bool
{
    if ($e->getCode() === '42S22') return true;
    return (int)($e->errorInfo[1] ?? 0) === 1054;
}

/**
 * Either of the above — the two shapes a part-migrated install actually takes.
 *
 * @param PDOException $e  the exception a query threw
 */
function dbErrorIsMissingSchema(PDOException $e): bool
{
    return dbErrorIsMissingTable($e) || dbErrorIsUnknownColumn($e);
}

/**
 * What a visitor may be told about a failure: the message itself, unless it came
 * from the database - which can name tables, columns, the database user or host -
 * in which case $generic, with the real message written to the server log.
 *
 * For pages reached BEFORE sign-in (the login page, single sign-on, the one-time
 * code step), where whoever sees the message may be anybody. Reported privately,
 * October 2026, along with config.php shipping display_errors on.
 *
 * @param string $generic  what to show instead of a database error
 */
function dbSafeClientMessage(Throwable $e, string $generic): string {
    if ($e instanceof PDOException || stripos($e->getMessage(), 'SQLSTATE') !== false) {
        error_log('[' . basename($e->getFile()) . ':' . $e->getLine() . '] ' . $e->getMessage());
        return $generic;
    }
    return $e->getMessage();
}
