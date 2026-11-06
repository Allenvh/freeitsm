<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/**
 * GH #194: widening software identity must preserve the installation on a grown
 * database, then admit multiple sources without admitting duplicates of one.
 * MySQL's connection-local TEMPORARY table shadows the real detail table here;
 * it is dropped in finally.
 *
 * Review fix: dbVerifyInventoryUpgrade() also widens asset_disks / asset_network_adapters
 * and adds two foreign keys - on the REAL tables, which no temporary table shadows. So
 * this refuses to run until Database Verification has done all of that already; on the
 * real tables the upgrade is then a no-op, and no persistent schema or data changes.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db_verify_inventory.php';
$conn=connectToDatabase(); $pass=0; $fail=0;
$pending=[];
foreach (['asset_disks','asset_network_adapters'] as $t) {
    $col=$conn->query("SHOW COLUMNS FROM `$t` LIKE 'source'")->fetch(PDO::FETCH_ASSOC);
    if (!$col || !preg_match('/varchar\((\d+)\)/i',$col['Type'],$m) || (int)$m[1]<64) $pending[]="$t.source";
}
foreach (['fk_inventory_source_asset','fk_inventory_section_source'] as $fk) {
    $q=$conn->prepare('SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND CONSTRAINT_NAME=?');
    $q->execute([$fk]); if (!$q->fetchColumn()) $pending[]=$fk;
}
if ($pending) { fwrite(STDERR,'Run Database Verification first - not yet upgraded: ' . implode(', ',$pending) . "\n"); exit(1); }
$ok=function(string $label,bool $cond) use (&$pass,&$fail): void {
    if ($cond) { ++$pass; echo "PASS $label\n"; } else { ++$fail; echo "FAIL $label\n"; }
};
try {
    $conn->exec("CREATE TEMPORARY TABLE software_inventory_detail (
        id INT PRIMARY KEY, host_id INT NOT NULL, app_id INT NOT NULL,
        display_version VARCHAR(100), source VARCHAR(20) NOT NULL DEFAULT 'agent',
        UNIQUE KEY ux_software_detail_host_app (host_id,app_id)) ENGINE=InnoDB");
    $conn->exec("INSERT INTO software_inventory_detail VALUES (123,456,789,'existing','manual')");
    $changes=dbVerifyInventoryUpgrade($conn);
    $ok('grown software identity requires an actual upgrade', count($changes)>=2);
    $row=$conn->query('SELECT * FROM software_inventory_detail WHERE id=123')->fetch(PDO::FETCH_ASSOC);
    $ok('upgrade preserves row id, version and ownership', $row && $row['display_version']==='existing' && $row['source']==='manual');
    $conn->exec("INSERT INTO software_inventory_detail VALUES (124,456,789,'observed','rest:synthetic')");
    $ok('same installation can have another source', (int)$conn->query('SELECT COUNT(*) FROM software_inventory_detail')->fetchColumn()===2);
    $refused=false;
    try { $conn->exec("INSERT INTO software_inventory_detail VALUES (125,456,789,'duplicate','rest:synthetic')"); }
    catch (PDOException $e) { $refused=($e->errorInfo[1] ?? null)===1062; }
    $ok('same source still cannot duplicate an installation', $refused);
    $ok('second upgrade is a no-op', dbVerifyInventoryUpgrade($conn)===[]);
} finally { $conn->exec('DROP TEMPORARY TABLE IF EXISTS software_inventory_detail'); }
echo "$pass passed, $fail failed\n"; exit($fail ? 1 : 0);
