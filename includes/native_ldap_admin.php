<?php
require_once __DIR__ . '/functions.php';
function nativeLdapCsrf(): string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    return $_SESSION['native_ldap_csrf'] ??= bin2hex(random_bytes(32));
}
function nativeLdapRequireAdmin(bool $mutation = false): PDO {
    if (empty($_SESSION['analyst_id'])) throw new RuntimeException('Administrator access required.');
    $db = connectToDatabase(); $id=(int)$_SESSION['analyst_id'];
    $active=$db->prepare('SELECT is_active FROM analysts WHERE id=?');$active->execute([$id]);
    if ((int)$active->fetchColumn() !== 1) throw new RuntimeException('Administrator access required.');
    $modules=getAnalystAllowedModules($db,$id);
    if ($modules !== null) {
        $s=$db->prepare("SELECT COUNT(*) FROM analyst_modules WHERE analyst_id=? AND module_key='system'");$s->execute([$id]);$explicit=(int)$s->fetchColumn();
        $s=$db->prepare("SELECT COUNT(*) FROM analyst_access_groups a JOIN access_groups g ON g.id=a.access_group_id AND g.is_active=1 JOIN access_group_modules m ON m.access_group_id=g.id WHERE a.analyst_id=? AND m.module_key='system'");$s->execute([$id]);
        if (!$explicit && !(int)$s->fetchColumn()) throw new RuntimeException('Administrator access required.');
    }
    if ($mutation && (($_SERVER['REQUEST_METHOD']??'')!=='POST' || !hash_equals(nativeLdapCsrf(),(string)($_SERVER['HTTP_X_CSRF_TOKEN']??'')))) throw new RuntimeException('Security check failed. Reload Authentication settings and retry.');
    return $db;
}
