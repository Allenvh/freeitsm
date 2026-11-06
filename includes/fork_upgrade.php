<?php
/** Explicit, repeatable bridge from the fork's pre-4.10 schema. No runtime DDL. */
require_once __DIR__ . '/encryption.php';

function forkUpgradeColumns(PDO $db, string $table): array {
    $q = $db->prepare('SELECT column_name FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=?');
    $q->execute([$table]);
    return $q->fetchAll(PDO::FETCH_COLUMN);
}

function forkUpgradeDone(PDO $db, string $phase): bool {
    if (!forkUpgradeColumns($db, 'fork_upgrade_state')) return false;
    $s = $db->prepare('SELECT 1 FROM fork_upgrade_state WHERE phase=?');
    $s->execute([$phase]);
    return (bool)$s->fetchColumn();
}

function forkUpgrade(PDO $db, bool $apply = false, bool $finish = false): array {
    $phase = $finish ? '4.10-finish' : '4.10-prepare';
    if (forkUpgradeDone($db, $phase)) return ['phase'=>$phase, 'already_applied'=>true];
    if ($finish) return forkUpgradeTeams($db, $apply);

    // Refuse unresolved retired AD identities instead of silently enabling password login.
    if (forkUpgradeColumns($db, 'ad_profiles')) {
        $hasMappings = (bool)forkUpgradeColumns($db, 'legacy_ad_profile_migrations');
        $sql = 'SELECT COUNT(*) FROM ad_profiles p';
        if ($hasMappings) $sql .= ' LEFT JOIN legacy_ad_profile_migrations m ON m.legacy_profile_id=p.id';
        // A pending lookup that never linked an account grants nothing and can stay archived.
        $sql .= ' WHERE (p.user_id IS NOT NULL OR p.analyst_id IS NOT NULL)';
        if ($hasMappings) $sql .= ' AND m.legacy_profile_id IS NULL';
        if ((int)$db->query($sql)->fetchColumn()) {
            throw new RuntimeException('Unmigrated legacy AD profiles exist. Run scripts/migrate_legacy_ad.php before upgrading.');
        }
    }

    $columns = forkUpgradeColumns($db, 'target_mailboxes');
    $mailboxes = [];
    if (in_array('imap_password_encrypted', $columns, true)) {
        foreach ($db->query('SELECT * FROM target_mailboxes')->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if (($row['provider'] ?? '') !== 'imap_smtp') continue;
            // The upstream provider does not model independently disabled intake/outbound.
            if (!(int)($row['intake_enabled'] ?? 1) || !(int)($row['outbound_enabled'] ?? 1)) {
                throw new RuntimeException('Mailbox #'.(int)$row['id'].' needs review: independent intake/outbound switches cannot be migrated automatically.');
            }
            if (trim(decryptValue($row['smtp_username'] ?? '')) === '') {
                throw new RuntimeException('Mailbox #'.(int)$row['id'].' needs review: upstream treats blank SMTP credentials as use IMAP credentials, rather than unauthenticated relay.');
            }
            $mapped = [
                'imap_server' => decryptValue($row['imap_host'] ?: $row['imap_server']),
                'imap_password' => decryptValue($row['imap_password_encrypted'] ?? ''),
                'smtp_server' => decryptValue($row['smtp_host'] ?? ''),
                'smtp_password' => decryptValue($row['smtp_password_encrypted'] ?? ''),
                'email_folder' => trim($row['imap_folder'] ?? '') ?: ($row['email_folder'] ?: 'INBOX'),
            ];
            foreach (['imap_password','smtp_server','smtp_password'] as $key) {
                if (!empty($row[$key]) && decryptValue($row[$key]) !== $mapped[$key]) {
                    throw new RuntimeException('Mailbox #'.(int)$row['id'].' has conflicting legacy and upstream fields; review before upgrading.');
                }
            }
            $mailboxes[(int)$row['id']] = $mapped;
        }
    }

    $analystCols = forkUpgradeColumns($db, 'analysts');
    $addAdmin = !in_array('is_admin', $analystCols, true);
    $analysts = [];
    foreach ($db->query('SELECT * FROM analysts')->fetchAll(PDO::FETCH_ASSOC) as $a) {
        $s=$db->prepare('SELECT module_key FROM analyst_modules WHERE analyst_id=?'); $s->execute([$a['id']]);
        $modules=$s->fetchAll(PDO::FETCH_COLUMN);
        if (forkUpgradeColumns($db, 'analyst_access_groups')) {
            $s=$db->prepare('SELECT gm.module_key FROM analyst_access_groups a JOIN access_groups g ON g.id=a.access_group_id AND g.is_active=1 JOIN access_group_modules gm ON gm.access_group_id=g.id WHERE a.analyst_id=?');
            $s->execute([$a['id']]); $modules=array_merge($modules,$s->fetchAll(PDO::FETCH_COLUMN));
        }
        $modules=array_values(array_unique($modules));
        $flag=$a['can_access_all_modules'] ?? null;
        $all=$flag === null ? !$modules : (int)$flag === 1;
        // Preserve the fork's old implied System grant only where it actually applied.
        $admin=$addAdmin && ($all || in_array('system',$modules,true) || ($flag===null && $modules));
        $analysts[]=['id'=>(int)$a['id'],'all'=>$all,'admin'=>$admin,'modules'=>$modules];
    }
    $report=['phase'=>$phase,'mailboxes'=>count($mailboxes),'analysts'=>count($analysts),
        'administrators_to_preserve'=>$addAdmin ? count(array_filter($analysts,fn($a)=>$a['admin'])) : 0,
        'apply'=>$apply];
    if (!$apply) return $report;

    // DDL commits implicitly in MySQL; all planning and secret validation happened above.
    foreach (['imap_password','smtp_server','smtp_password'] as $col) {
        if (!in_array($col,$columns,true)) $db->exec("ALTER TABLE target_mailboxes ADD `$col` TEXT NULL");
    }
    if ($addAdmin) $db->exec('ALTER TABLE analysts ADD is_admin TINYINT(1) NOT NULL DEFAULT 0');
    if (!in_array('can_access_all_modules',$analystCols,true)) $db->exec('ALTER TABLE analysts ADD can_access_all_modules TINYINT(1) NULL');
    $db->exec('CREATE TABLE IF NOT EXISTS fork_upgrade_state (phase VARCHAR(60) PRIMARY KEY, applied_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP)');
    $db->beginTransaction();
    try {
        foreach ($mailboxes as $id=>$m) {
            $db->prepare("UPDATE target_mailboxes SET provider='imap', imap_server=?,imap_password=?,smtp_server=?,smtp_password=?,email_folder=? WHERE id=?")
                ->execute([encryptValue($m['imap_server']),encryptValue($m['imap_password']),encryptValue($m['smtp_server']),encryptValue($m['smtp_password']),$m['email_folder'],$id]);
        }
        foreach ($analysts as $a) {
            $db->prepare('UPDATE analysts SET can_access_all_modules=? WHERE id=?')->execute([(int)$a['all'],$a['id']]);
            if ($addAdmin) $db->prepare('UPDATE analysts SET is_admin=? WHERE id=?')->execute([(int)$a['admin'],$a['id']]);

        }
        $db->prepare('INSERT INTO fork_upgrade_state(phase) VALUES(?)')->execute([$phase]);
        $db->commit();
    } catch (Throwable $e) { $db->rollBack(); throw $e; }
    return $report;
}

function forkUpgradeTeams(PDO $db, bool $apply): array {
    if (!forkUpgradeDone($db,'4.10-prepare')) throw new RuntimeException('Run the prepare phase first.');
    foreach (['team_modules','sso_group_teams'] as $table) {
        if (!forkUpgradeColumns($db,$table)) throw new RuntimeException('Apply upstream database verification before --finish.');
    }
    $groups=forkUpgradeColumns($db,'access_groups') ? $db->query('SELECT * FROM access_groups WHERE is_active=1')->fetchAll(PDO::FETCH_ASSOC) : [];
    $report=['phase'=>'4.10-finish','groups_to_teams'=>count($groups),'apply'=>$apply];
    if (!$apply) return $report;
    $db->beginTransaction();
    try {
        foreach ($groups as $g) {
            $name='Imported access group #'.(int)$g['id'];
            if ($db->query('SELECT COUNT(*) FROM teams WHERE name='.$db->quote($name))->fetchColumn()) throw new RuntimeException('Imported team name already exists; review before retrying.');
            $db->prepare('INSERT INTO teams(name,description,is_active,can_access_all_modules,can_access_all_tenants) VALUES(?,?,1,0,0)')->execute([$name,mb_substr($g['display_name'],0,500)]);
            $team=(int)$db->lastInsertId();
            $db->prepare("INSERT INTO team_modules(team_id,module_key) SELECT ?,module_key FROM access_group_modules WHERE access_group_id=? AND module_key<>'system'")->execute([$team,$g['id']]);
            // Membership follows the upstream team on future mapped OIDC logins.
            $db->prepare('INSERT INTO analyst_teams(analyst_id,team_id) SELECT DISTINCT analyst_id,? FROM analyst_access_groups WHERE access_group_id=?')->execute([$team,$g['id']]);
            $db->prepare('INSERT INTO sso_group_teams(provider_id,group_name,team_id) SELECT provider_id,external_group_value,? FROM auth_provider_group_mappings WHERE access_group_id=? AND is_active=1')->execute([$team,$g['id']]);
        }
        $db->exec("INSERT INTO fork_upgrade_state(phase) VALUES('4.10-finish')");
        $db->commit();
    } catch (Throwable $e) { $db->rollBack(); throw $e; }
    return $report;
}
