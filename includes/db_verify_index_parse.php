<?php
/**
 * Shared parser + drift self-check for the index backfill list.
 *
 * `database/freeitsm.sql` is the source of truth for the schema;
 * `includes/db_verify_indexes.php` is a GENERATED mirror of its indexes that
 * Database Verification uses to restore missing ones. The risk is the classic
 * one: someone adds an index to freeitsm.sql and forgets to regenerate, so the
 * mirror goes stale and grown installs silently miss the new index — the very
 * drift the backfill was built to end.
 *
 * These two functions close that loop. ONE parser produces the list (via
 * scripts/gen_db_verify_indexes.php) and audits it (via the self-check that
 * db_verify runs on every pass), so the generator and the checker can never
 * disagree. Mirrors the capSelfCheck() / settings-manifest self-check pattern.
 */

/**
 * Extract every named secondary index from freeitsm.sql text:
 * [table, name, type, columns]. Skips PRIMARY KEY, FOREIGN KEY, CONSTRAINT.
 *
 * `type` is 'unique', 'fulltext' or 'key'. It was a boolean (isUnique) until
 * 2026-08; full-text search needs a third kind, and a two-valued flag had
 * nowhere to put it. dbVerifyIndexTypeOf() below reads either shape, so a
 * generated list from before the change still audits and applies correctly
 * rather than silently becoming the wrong kind of index.
 *
 * @param string $sql  the full text of database/freeitsm.sql
 */
function dbVerifyParseIndexesFromSql(string $sql): array {
    preg_match_all('/CREATE TABLE(?: IF NOT EXISTS)?\s+`([a-zA-Z0-9_]+)`\s*\((.*?)\n\)\s*ENGINE/s', $sql, $blocks, PREG_SET_ORDER);
    $rows = [];
    foreach ($blocks as $b) {
        foreach (explode("\n", $b[2]) as $line) {
            $line = trim($line);
            // cols may contain nested () for prefix lengths, e.g. (`name`(400)),
            // so capture through to the last ).
            if (preg_match('/^(UNIQUE|FULLTEXT)?\s*(?:KEY|INDEX)\s+`([a-zA-Z0-9_]+)`\s*(\(.*\))\s*,?\s*$/i', $line, $m)) {
                $prefix = strtolower(trim($m[1] ?? ''));
                $rows[] = [$b[1], $m[2], $prefix === '' ? 'key' : $prefix, preg_replace('/\s+/', '', $m[3])];
            }
        }
    }
    return $rows;
}

/**
 * Normalise an index type from either list format.
 *
 * The generated list carried `true`/`false` before 2026-08 and carries
 * 'unique'/'fulltext'/'key' after it. Reading both matters because the
 * consequence of guessing is not an error — `true ? 'UNIQUE KEY' : 'KEY'`
 * would happily read the string 'key' as truthy and build a UNIQUE index on a
 * column that has duplicates, which fails loudly, or worse the reverse.
 *
 * @param mixed $type  a list entry's type: bool (old format, true = unique) or 'unique'/'fulltext'/'key';
 *     anything else reads as 'key'
 */
function dbVerifyIndexTypeOf($type): string {
    if (is_bool($type)) return $type ? 'unique' : 'key';
    $t = strtolower((string)$type);
    return in_array($t, ['unique', 'fulltext', 'key'], true) ? $t : 'key';
}

/**
 * Compare the committed generated list against a fresh parse of freeitsm.sql.
 * Returns human-readable problem strings; an empty array means they're in sync.
 *
 * Skips silently if freeitsm.sql isn't present (a trimmed deployment may not ship
 * it) — we never cry drift when we can't see the source of truth.
 *
 * @param ?string $sqlPath  path to freeitsm.sql; null means database/freeitsm.sql in this install
 * @param ?string $listPath  path to the generated index list; null means includes/db_verify_indexes.php
 */
function dbVerifyIndexListSelfCheck(?string $sqlPath = null, ?string $listPath = null): array {
    $sqlPath  = $sqlPath  ?? __DIR__ . '/../database/freeitsm.sql';
    $listPath = $listPath ?? __DIR__ . '/db_verify_indexes.php';

    if (!is_readable($sqlPath)) return [];
    if (!is_readable($listPath)) {
        return ['includes/db_verify_indexes.php is missing — run scripts/gen_db_verify_indexes.php.'];
    }

    $fresh     = dbVerifyParseIndexesFromSql((string)file_get_contents($sqlPath));
    $committed = require $listPath;

    // Key each index by "table.name" -> a signature of "type + columns". Read via
    // dbVerifyIndexTypeOf so a pre-2026-08 list reports real drift rather than 233
    // false ones caused purely by the format change.
    //
    // ⚠️ Whitespace is stripped from BOTH sides. The fresh parse already strips
    // it, but a list generated before that normalisation carries `(a, b)` where
    // the parse now yields `(a,b)` — and the two were compared as strings, so a
    // user upgrading through that boundary was shown
    //   "Index task_recurrences.ix_task_recurrences_due differs — freeitsm.sql
    //    has [KEY (is_active,next_due_date)], the list has [KEY (is_active, next_due_date)]"
    // which is the same index, spelled two ways. Reported by a user. A
    // difference only a diff tool can see is not drift.
    $sig = fn($r) => strtoupper(dbVerifyIndexTypeOf($r[2])) . ' ' . preg_replace('/\s+/', '', (string)$r[3]);
    $freshMap = $commMap = [];
    foreach ($fresh as $r)     $freshMap[$r[0] . '.' . $r[1]] = $sig($r);
    foreach ($committed as $r) $commMap[$r[0] . '.' . $r[1]] = $sig($r);

    $problems = [];
    foreach ($freshMap as $k => $v) {
        if (!isset($commMap[$k]))       $problems[] = "Index $k is in freeitsm.sql but missing from the backfill list.";
        elseif ($commMap[$k] !== $v)    $problems[] = "Index $k differs — freeitsm.sql has [$v], the list has [{$commMap[$k]}].";
    }
    foreach ($commMap as $k => $v) {
        if (!isset($freshMap[$k]))      $problems[] = "Index $k is in the backfill list but no longer in freeitsm.sql.";
    }
    return $problems;
}
