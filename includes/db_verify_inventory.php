<?php
/** Grown-install inventory upgrade, after db_verify has added the schema columns. */
function dbVerifyInventoryUpgrade(PDO $conn): array
{
    $changes=[];
    foreach (['asset_disks','asset_network_adapters','software_inventory_detail'] as $table) {
        $col=$conn->query("SHOW COLUMNS FROM `$table` LIKE 'source'")->fetch(PDO::FETCH_ASSOC);
        if ($col && preg_match('/^varchar\((\d+)\)$/i',$col['Type'],$match) && (int)$match[1]<64) {
            $conn->exec("ALTER TABLE `$table` MODIFY source VARCHAR(64) NOT NULL DEFAULT 'agent'");
            $changes[]="Widened $table.source (existing values preserved)";
        }
    }
    $indexes=$conn->query('SHOW INDEX FROM software_inventory_detail')->fetchAll(PDO::FETCH_ASSOC);
    $names=array_column($indexes,'Key_name'); $alter=[];
    if (in_array('ux_software_detail_host_app',$names,true)) $alter[]='DROP INDEX ux_software_detail_host_app';
    if (!in_array('ux_software_detail_host_app_source',$names,true)) $alter[]='ADD UNIQUE KEY ux_software_detail_host_app_source (host_id,app_id,source)';
    if ($alter) {
        // One atomic DDL: never expose the table with neither uniqueness rule.
        $conn->exec('ALTER TABLE software_inventory_detail ' . implode(', ',$alter));
        $changes[]='Software observations are unique per host/application/source';
    }
    // GH #200 Part B: a section can now be on record before it has ever succeeded (a failed
    // first attempt), and the agent is no analyst - so these four relax to NULL. Relaxing
    // NOT NULL cannot invalidate a row that already exists.
    foreach ($conn->query('SHOW COLUMNS FROM asset_inventory_sections')->fetchAll(PDO::FETCH_ASSOC) as $col) {
        if (in_array($col['Field'],['observed_at','imported_at','imported_by_id','fingerprint'],true) && $col['Null']==='NO') {
            $conn->exec('ALTER TABLE asset_inventory_sections MODIFY `' . $col['Field'] . '` ' . strtoupper($col['Type']) . ' NULL');
            $changes[]='asset_inventory_sections.' . $col['Field'] . ' may be empty until a section first succeeds';
        }
    }
    foreach ([['asset_inventory_sources','fk_inventory_source_asset','asset_id','assets'],
              ['asset_inventory_sections','fk_inventory_section_source','inventory_source_id','asset_inventory_sources']] as [$table,$name,$column,$parent]) {
        $find=$conn->prepare('SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME=? AND CONSTRAINT_NAME=?');
        $find->execute([$table,$name]);
        if (!$find->fetchColumn()) {
            $conn->exec("ALTER TABLE `$table` ADD CONSTRAINT `$name` FOREIGN KEY (`$column`) REFERENCES `$parent` (id) ON DELETE CASCADE");
            $changes[]="Added $name";
        }
    }
    return $changes;
}
