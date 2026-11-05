<?php
require_once __DIR__ . '/ldap.php';
require_once __DIR__ . '/ldap_schema.php';
/** Explicit CLI-only migration; no automatic email merges and no default enablement. */
function migrateLegacyAd(PDO $db, bool $apply = false): array {
    $exists = $db->query("SHOW TABLES LIKE 'ad_providers'")->fetchColumn();
    if (!$exists) return ['providers'=>0,'profiles'=>0,'message'=>'No legacy AD tables found.'];
    $legacyProviders=$db->query('SELECT * FROM ad_providers ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    $profiles=$db->query('SELECT * FROM ad_profiles ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
    $settings=$db->query('SELECT * FROM ad_settings WHERE id=1')->fetch(PDO::FETCH_ASSOC) ?: [];
    $plan=['providers'=>count($legacyProviders),'profiles'=>0,'pending_profiles'=>0,'disabled_imports'=>true];
    if (!$apply) {
        foreach($profiles as$p)if($p['user_id']||$p['analyst_id'])$plan['profiles']++;else$plan['pending_profiles']++;
        return $plan;
    }
    ensureNativeLdapSchema($db); // DDL first: MySQL implicitly commits DDL.
    $db->beginTransaction();
    try {
        $map=[];
        foreach($legacyProviders as$p){
            $st=$db->prepare('SELECT provider_id FROM legacy_ad_provider_migrations WHERE legacy_provider_id=?');$st->execute([$p['id']]);$existing=$st->fetchColumn();
            if($existing){$map[(int)$p['id']]=(int)$existing;continue;}
            $c=json_decode($p['config_json'],true,512,JSON_THROW_ON_ERROR);
            $attributes=[$c['username_attribute'],$c['email_attribute'],$c['display_attribute']];
            foreach($attributes as$a)if(!preg_match('/^[a-zA-Z][a-zA-Z0-9-]*$/D',$a))throw new RuntimeException('Review invalid legacy directory attribute.');
            $values=[mb_substr($p['name'],0,60).' (imported; review before enabling)',$c['primary'],(int)$c['port'],$c['transport']==='ldaps'?'ldaps':'starttls',
                $c['bind_identity'],$p['password_encrypted'],$c['user_base']?:$c['base_dn'],
                '(&(objectClass=user)(|('.$c['username_attribute'].'=%s)('.$c['email_attribute'].'=%s)(userPrincipalName=%s)))',
                $c['username_attribute'],$c['email_attribute'],$c['display_attribute'],'objectGUID',$c['group_base']?:$c['base_dn'],
                !empty($c['nested_groups'])?'(&(objectClass=group)(member:1.2.840.113556.1.4.1941:=%s))':'(&(objectClass=group)(member=%s))',
                (int)$p['priority']];
            $db->prepare("INSERT INTO auth_providers(display_name,protocol,issuer_url,client_id,enabled,auto_create_users,auto_create_analysts,ldap_host,ldap_port,ldap_encryption,ldap_bind_dn,ldap_bind_password,ldap_base_dn,ldap_user_filter,ldap_attr_username,ldap_attr_email,ldap_attr_name,ldap_attr_guid,ldap_group_base_dn,ldap_group_filter,sort_order) VALUES (?,'ldap','','',0,0,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")->execute($values);
            $native=(int)$db->lastInsertId();$map[(int)$p['id']]=$native;
            $db->prepare('INSERT INTO legacy_ad_provider_migrations(legacy_provider_id,provider_id)VALUES(?,?)')->execute([$p['id'],$native]);
        }
        foreach($profiles as$p){
            if(!$p['user_id']&&!$p['analyst_id']){$plan['pending_profiles']++;continue;}
            $st=$db->prepare('SELECT 1 FROM legacy_ad_profile_migrations WHERE legacy_profile_id=?');$st->execute([$p['id']]);if($st->fetchColumn())continue;
            if ($p['disabled']) throw new RuntimeException('Disabled legacy profile #'.$p['id'].' requires explicit review before migration.');
            $st=$db->prepare('SELECT * FROM ad_identities WHERE profile_id=? ORDER BY provider_id');$st->execute([$p['id']]);$links=$st->fetchAll(PDO::FETCH_ASSOC);
            $selected=null;
            foreach($links as$link)if((int)$link['provider_id']===(int)$p['last_provider_id'])$selected=$link;
            if(!$selected&&count($links)===1)$selected=$links[0];
            if(!$selected)throw new RuntimeException('Profile #'.$p['id'].' has no unambiguous proven authentication identity; review before migration.');
            $provider=$map[(int)$selected['provider_id']]??null;if(!$provider)throw new RuntimeException('Legacy directory mapping missing.');
            if(!preg_match('/^[a-f0-9]{32}$/D',$selected['object_guid']))throw new RuntimeException('Invalid legacy GUID for profile #'.$p['id']);
            $subject=ldapStringifyId(hex2bin($selected['object_guid']));
            foreach(['analysts'=>$p['analyst_id'],'users'=>$p['user_id']]as$table=>$id){
                if(!$id)continue;
                if($table==='analysts'&&(int)$id===(int)($settings['emergency_analyst_id']??0))throw new RuntimeException('Emergency local administrator cannot be migrated.');
                $st=$db->prepare("SELECT auth_provider_id FROM $table WHERE id=? FOR UPDATE");$st->execute([$id]);$assigned=$st->fetch(PDO::FETCH_ASSOC);
                if(!$assigned||!empty($assigned['auth_provider_id']))throw new RuntimeException('Review existing sign-in assignment for '.$table.' #'.$id);
                $db->prepare("UPDATE $table SET auth_provider_id=? WHERE id=?")->execute([$provider,$id]);
                $identity=$table==='analysts'?'analyst_sso_identities':'user_sso_identities';$column=$table==='analysts'?'analyst_id':'user_id';
                $db->prepare("INSERT INTO $identity($column,provider_id,subject,email,linked_datetime)VALUES(?,?,?,?,UTC_TIMESTAMP())")->execute([$id,$provider,$subject,$p['email']]);
            }
            if($p['analyst_id']){
                // Snapshot only successful effective grants; an outage must never expand them.
                $state=json_decode($p['authorization_json']??'{}',true,512,JSON_THROW_ON_ERROR);
                $modules=$p['authorization_status']==='current'?($state['modules']??[]):[];
                if($p['disabled'])$modules=[];
                $db->prepare('DELETE FROM analyst_modules WHERE analyst_id=?')->execute([$p['analyst_id']]);
                $db->prepare('DELETE FROM analyst_access_groups WHERE analyst_id=?')->execute([$p['analyst_id']]);
                foreach(array_unique($modules)as$module)$db->prepare('INSERT INTO analyst_modules(analyst_id,module_key)VALUES(?,?)')->execute([$p['analyst_id'],$module]);
                $db->prepare('UPDATE analysts SET can_access_all_modules=0 WHERE id=?')->execute([$p['analyst_id']]);
            }
            $db->prepare('INSERT INTO legacy_ad_profile_migrations(legacy_profile_id,provider_id)VALUES(?,?)')->execute([$p['id'],$provider]);
            $plan['profiles']++;
        }
        $db->exec('UPDATE ad_settings SET enabled=0 WHERE id=1');
        $db->commit();return $plan;
    }catch(Throwable$e){if($db->inTransaction())$db->rollBack();throw$e;}
}
