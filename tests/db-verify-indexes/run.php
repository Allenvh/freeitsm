<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Database Verification — the index backfill list understands FULLTEXT.
 *
 * `database/freeitsm.sql` builds a NEW install; Database Verification upgrades an
 * EXISTING one. Indexes bridge the two through a GENERATED mirror
 * (includes/db_verify_indexes.php) so a grown install can be given an index it
 * never received. Until 2026-08 that mirror recorded a single boolean per index —
 * unique or not — which had nowhere to put a third kind.
 *
 * Full-text search needs that third kind, and the failure mode of getting it
 * wrong is quiet rather than loud:
 *
 *  1. TYPE LOSS. A `FULLTEXT KEY` parsed as an ordinary `KEY` still produces the
 *     right NUMBER of indexes, so a count check passes while every full-text
 *     search silently returns nothing on upgraded installs.
 *
 *  2. THE TRUTHY TRAP. The old third element was a bool read as
 *     `$unique ? 'UNIQUE KEY' : 'KEY'`. The new one is a string — and the string
 *     'key' is TRUTHY, so any code path still using that ternary would build a
 *     UNIQUE index over columns that are full of duplicates.
 *
 *  3. DRIFT. The mirror is generated, so the generator and the drift self-check
 *     must agree about what an index "is". They share one parser precisely so
 *     they cannot disagree — this suite pins that.
 *
 *   php tests/db-verify-indexes/run.php
 *
 * ⚠️ Every "it parsed correctly" assertion is paired with a control proving the
 * check can actually fail — a suite that cannot go red is not evidence.
 */

require_once __DIR__ . '/../../includes/db_verify_index_parse.php';

$pass = $fail = 0;
function ok(string $what, bool $cond) {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  ok    $what\n"; }
    else       { $fail++; echo "  FAIL  $what\n"; }
}
function section(string $s) { echo "\n$s\n" . str_repeat('-', strlen($s)) . "\n"; }

// ---------------------------------------------------------------------------
// A miniature freeitsm.sql covering every index shape the parser must handle,
// plus the three it must ignore.
// ---------------------------------------------------------------------------
$sql = <<<'SQL'
CREATE TABLE IF NOT EXISTS `search_documents` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `source_type` VARCHAR(32) NOT NULL,
    `ticket_id` INT NULL,
    `title` VARCHAR(500) NULL,
    `body` MEDIUMTEXT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_sd_ticket` (`ticket_id`),
    UNIQUE KEY `uq_sd_source` (`source_type`,`id`),
    FULLTEXT KEY `ft_sd_all` (`title`,`body`),
    FULLTEXT INDEX `ft_sd_title` (`title`),
    CONSTRAINT `fk_sd_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `widgets` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(600) NOT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_widget_name` (`name`(400))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL;

$rows = dbVerifyParseIndexesFromSql($sql);
$byName = [];
foreach ($rows as $r) $byName[$r[1]] = $r;

section('1. Every index kind is parsed, and given the right type');
ok('found exactly 5 named indexes (PRIMARY and FOREIGN KEY ignored)', count($rows) === 5);
ok("plain KEY            -> 'key'",      ($byName['idx_sd_ticket'][2]   ?? null) === 'key');
ok("UNIQUE KEY           -> 'unique'",   ($byName['uq_sd_source'][2]    ?? null) === 'unique');
ok("FULLTEXT KEY         -> 'fulltext'", ($byName['ft_sd_all'][2]       ?? null) === 'fulltext');
ok("FULLTEXT INDEX       -> 'fulltext'", ($byName['ft_sd_title'][2]     ?? null) === 'fulltext');
ok('column prefix length survives',      ($byName['idx_widget_name'][3] ?? null) === '(`name`(400))');
ok('multi-column full-text columns kept',($byName['ft_sd_all'][3]       ?? null) === '(`title`,`body`)');

// The control: if the parser quietly downgraded FULLTEXT to an ordinary key, the
// count above would STILL be 5. Assert the distinction exists at all.
$types = array_count_values(array_column($rows, 2));
ok('CONTROL — the three types are distinguishable, not all "key"',
   ($types['fulltext'] ?? 0) === 2 && ($types['unique'] ?? 0) === 1 && ($types['key'] ?? 0) === 2);

section('2. PRIMARY KEY and FOREIGN KEY are never treated as backfillable indexes');
ok('no index named like a primary key', !isset($byName['PRIMARY']));
ok('the foreign key is not in the list', !isset($byName['fk_sd_ticket']));
ok('no row has an empty name', count(array_filter($rows, fn($r) => trim($r[1]) === '')) === 0);

section('3. dbVerifyIndexTypeOf reads BOTH list formats');
ok("legacy true  -> 'unique'",  dbVerifyIndexTypeOf(true)       === 'unique');
ok("legacy false -> 'key'",     dbVerifyIndexTypeOf(false)      === 'key');
ok("'unique'     -> 'unique'",  dbVerifyIndexTypeOf('unique')   === 'unique');
ok("'fulltext'   -> 'fulltext'",dbVerifyIndexTypeOf('fulltext') === 'fulltext');
ok("'key'        -> 'key'",     dbVerifyIndexTypeOf('key')      === 'key');
ok("'FULLTEXT' (case) -> 'fulltext'", dbVerifyIndexTypeOf('FULLTEXT') === 'fulltext');
ok('anything unrecognised falls back to the SAFE kind (key, not unique)',
   dbVerifyIndexTypeOf('nonsense') === 'key');

// ⚠️ The trap this whole change exists to avoid: the string 'key' is truthy, so
// the OLD ternary would have built a UNIQUE index from it.
ok('CONTROL — the old ternary really would have got it wrong',
   ('key' ? 'UNIQUE KEY' : 'KEY') === 'UNIQUE KEY');
ok('...and the replacement gets it right',
   (['unique' => 'UNIQUE KEY', 'fulltext' => 'FULLTEXT KEY'][dbVerifyIndexTypeOf('key')] ?? 'KEY') === 'KEY');
ok('...and maps full-text to the right DDL',
   (['unique' => 'UNIQUE KEY', 'fulltext' => 'FULLTEXT KEY'][dbVerifyIndexTypeOf('fulltext')] ?? 'KEY') === 'FULLTEXT KEY');

section('4. The committed mirror is in step with the real freeitsm.sql');
$drift = dbVerifyIndexListSelfCheck();
ok('no drift between freeitsm.sql and includes/db_verify_indexes.php', $drift === []);
if ($drift) foreach (array_slice($drift, 0, 8) as $d) echo "        $d\n";

$committed = require __DIR__ . '/../../includes/db_verify_indexes.php';
ok('the committed mirror is non-empty', count($committed) > 0);
ok('every committed row carries a STRING type, not a bool',
   count(array_filter($committed, fn($r) => !is_string($r[2]))) === 0);
ok('every committed type is one we understand',
   count(array_filter($committed, fn($r) => !in_array($r[2], ['unique','fulltext','key'], true))) === 0);

// Control: the self-check must be capable of reporting drift, or "no drift" is
// meaningless. Feed it a deliberately wrong list.
$tmp = sys_get_temp_dir() . '/dbv_idx_control_' . getmypid() . '.php';
file_put_contents($tmp, "<?php\nreturn [['search_documents','ft_sd_all','key','(`title`,`body`)']];\n");
$controlSql = tempnam(sys_get_temp_dir(), 'dbv') ;
file_put_contents($controlSql, $sql);
$controlDrift = dbVerifyIndexListSelfCheck($controlSql, $tmp);
ok('CONTROL — a wrong list DOES report drift (so "no drift" means something)',
   count($controlDrift) > 0);
$sawType = (bool) array_filter($controlDrift, fn($d) => str_contains($d, 'differs'));
ok('CONTROL — and it specifically catches a FULLTEXT downgraded to KEY', $sawType);
@unlink($tmp); @unlink($controlSql);

section('5. Formatting is not drift');
// A user upgrading through the 2026-08 parser change was shown:
//   "Index task_recurrences.ix_task_recurrences_due differs — freeitsm.sql has
//    [KEY (is_active,next_due_date)], the list has [KEY (is_active, next_due_date)]"
// which is one index spelled two ways. The fresh parse stripped whitespace and
// the committed list still carried it, and the two were compared as strings.
// A difference only a diff tool can see is not drift — but the check must not
// go blind in the process, so each of these has a control.
$wsSql = tempnam(sys_get_temp_dir(), 'dbvws');
file_put_contents($wsSql,
    "CREATE TABLE IF NOT EXISTS `task_recurrences` (\n"
  . "    `id` INT NOT NULL AUTO_INCREMENT,\n"
  . "    `is_active` TINYINT(1) NOT NULL DEFAULT 1,\n"
  . "    `next_due_date` DATE NULL,\n"
  . "    PRIMARY KEY (`id`),\n"
  . "    KEY `ix_task_recurrences_due` (`is_active`,`next_due_date`)\n"
  . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;\n");
$wsList = tempnam(sys_get_temp_dir(), 'dbvwsl');
$withList = function (string $ret) use ($wsList, $wsSql) {
    file_put_contents($wsList, "<?php\nreturn $ret;\n");
    return dbVerifyIndexListSelfCheck($wsSql, $wsList);
};

ok('a space after the comma is NOT reported as drift',
   $withList("[['task_recurrences','ix_task_recurrences_due','key','(`is_active`, `next_due_date`)']]") === []);
ok('CONTROL — a REORDERED column list IS still drift',
   (bool) array_filter($withList("[['task_recurrences','ix_task_recurrences_due','key','(`next_due_date`,`is_active`)']]"),
       fn($d) => str_contains($d, 'differs')));
ok('CONTROL — a KEY promoted to UNIQUE IS still drift, despite the space',
   (bool) array_filter($withList("[['task_recurrences','ix_task_recurrences_due','unique','(`is_active`, `next_due_date`)']]"),
       fn($d) => str_contains($d, 'differs')));
ok('CONTROL — an index missing from the list IS still drift',
   (bool) array_filter($withList('[]'), fn($d) => str_contains($d, 'missing from the backfill list')));
ok('CONTROL — an index only in the list IS still drift',
   (bool) array_filter(
       $withList("[['task_recurrences','ix_task_recurrences_due','key','(`is_active`,`next_due_date`)'],"
               . "['task_recurrences','ix_gone','key','(`id`)']]"),
       fn($d) => str_contains($d, 'no longer in freeitsm.sql')));
@unlink($wsSql); @unlink($wsList);

echo "\n" . str_repeat('=', 60) . "\n";
printf("%d passed, %d failed\n", $pass, $fail);
exit($fail === 0 ? 0 : 1);
