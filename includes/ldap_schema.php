<?php
/** Additive upstream LDAP prerequisites. No legacy identity/history rows are deleted. */
function ensureNativeLdapSchema(PDO $db): void {
    $columns = [
        'auth_providers' => [
            'ldap_host'=>'VARCHAR(255) NULL', 'ldap_port'=>'INT NULL', 'ldap_encryption'=>'VARCHAR(10) NULL',
            'ldap_bind_dn'=>'VARCHAR(255) NULL', 'ldap_bind_password'=>'VARCHAR(500) NULL', 'ldap_base_dn'=>'VARCHAR(255) NULL',
            'ldap_user_filter'=>'VARCHAR(500) NULL', 'ldap_attr_username'=>'VARCHAR(64) NULL', 'ldap_attr_email'=>'VARCHAR(64) NULL',
            'ldap_attr_name'=>'VARCHAR(64) NULL', 'ldap_attr_guid'=>'VARCHAR(64) NULL', 'ldap_group_base_dn'=>'VARCHAR(255) NULL',
            'ldap_group_filter'=>'VARCHAR(500) NULL', 'ldap_analyst_group'=>'VARCHAR(255) NULL', 'ldap_user_group'=>'VARCHAR(255) NULL',
            'auto_create_analysts'=>'TINYINT(1) NOT NULL DEFAULT 0',
        ],
        // NULL retains the fork's existing permission convention for untouched legacy accounts.
        'analysts'=>['can_access_all_modules'=>'TINYINT(1) NULL DEFAULT NULL'],
        'users'=>['username'=>'VARCHAR(255) NULL', 'tenant_id'=>'INT NULL'],
    ];
    foreach ($columns as $table=>$fields) {
        $present=$db->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($fields as $field=>$definition) if (!in_array($field,$present,true)) $db->exec("ALTER TABLE `$table` ADD COLUMN `$field` $definition");
    }
    $index=$db->query("SHOW INDEX FROM users WHERE Key_name='uq_users_username'")->fetch();
    if (!$index) $db->exec('ALTER TABLE users ADD UNIQUE KEY uq_users_username (username)');
    $email=$db->query("SHOW COLUMNS FROM users LIKE 'email'")->fetch(PDO::FETCH_ASSOC);
    if ($email['Null'] !== 'YES') $db->exec('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
    $db->exec('CREATE TABLE IF NOT EXISTS legacy_ad_provider_migrations (legacy_provider_id INT PRIMARY KEY, provider_id INT NOT NULL UNIQUE, migrated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB');
    $db->exec('CREATE TABLE IF NOT EXISTS legacy_ad_profile_migrations (legacy_profile_id INT PRIMARY KEY, provider_id INT NOT NULL, migrated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB');
}
