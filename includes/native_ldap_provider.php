<?php
require_once __DIR__ . '/ldap.php';
/** LDAP configuration adapter for this fork's existing provider admin API. */
function saveNativeLdapProvider(PDO $db, array $d): int {
    $id=(int)($d['id']??0); $name=trim($d['display_name']??'');
    $host=trim($d['ldap_host']??''); $base=trim($d['ldap_base_dn']??''); $transport=$d['ldap_encryption']??'ldaps';
    if (mb_strlen($name)>100) throw new InvalidArgumentException('Provider name must be at most 100 characters.');
    if (!$name || !$host || !$base) throw new InvalidArgumentException('Provider name, LDAP server and base DN are required.');
    if (!filter_var($host,FILTER_VALIDATE_DOMAIN,FILTER_FLAG_HOSTNAME) && !filter_var($host,FILTER_VALIDATE_IP)) throw new InvalidArgumentException('Enter a hostname or IP address, without a URL scheme.');
    if (!in_array($transport,['none','ldaps','starttls'],true)) throw new InvalidArgumentException('Invalid LDAP transport.');
    $port=filter_var($d['ldap_port']??($transport==='ldaps'?636:389),FILTER_VALIDATE_INT);
    if (!$port || $port<1 || $port>65535) throw new InvalidArgumentException('Invalid LDAP port.');
    $existing=null;
    if ($id) { $s=$db->prepare('SELECT * FROM auth_providers WHERE id=?');$s->execute([$id]);$existing=$s->fetch(PDO::FETCH_ASSOC);if(!$existing || $existing['protocol']!=='ldap')throw new InvalidArgumentException('Create a separate LDAP provider; existing OIDC providers cannot be converted.'); }
    $defaults=ldapFlavourDefaults(($d['ldap_flavour']??'ad'));
    $values=['display_name'=>$name,'protocol'=>'ldap','issuer_url'=>'','client_id'=>'','enabled'=>!empty($d['enabled'])?1:0,
        'auto_create_users'=>!empty($d['auto_create_users'])?1:0,'auto_create_analysts'=>!empty($d['auto_create_analysts'])?1:0,
        'default_modules'=>trim($d['default_modules']??'')?:null,'sort_order'=>(int)($d['sort_order']??0),'tenant_id'=>!empty($d['tenant_id'])?(int)$d['tenant_id']:null,
        'ldap_host'=>$host,'ldap_port'=>$port,'ldap_encryption'=>$transport,'ldap_base_dn'=>$base,'ldap_bind_dn'=>trim($d['ldap_bind_dn']??''),
        'ldap_group_base_dn'=>trim($d['ldap_group_base_dn']??''),'ldap_analyst_group'=>trim($d['ldap_analyst_group']??''),'ldap_user_group'=>trim($d['ldap_user_group']??'')];
    foreach(['ldap_attr_username','ldap_attr_email','ldap_attr_name','ldap_attr_guid']as$key){$v=trim($d[$key]??$defaults[$key]);if(!preg_match('/^[a-zA-Z][a-zA-Z0-9-]*$/D',$v))throw new InvalidArgumentException('Invalid directory attribute name.');$values[$key]=$v;}
    foreach(['ldap_user_filter','ldap_group_filter']as$key){$values[$key]=trim($d[$key]??$defaults[$key]);if(!$values[$key]||!str_contains($values[$key],'%s'))throw new InvalidArgumentException('LDAP search filters must contain %s.');}
    if (!isMaskedNoChangeValue($d['ldap_bind_password']??'')) $values['ldap_bind_password']=encryptValue($d['ldap_bind_password']);
    elseif (!$id) $values['ldap_bind_password']=null;
    if ($values['ldap_bind_dn']!=='' && empty($values['ldap_bind_password']) && empty($existing['ldap_bind_password'])) throw new InvalidArgumentException('Service account password required.');
    $columns=array_keys($values);
    if($id){$sql='UPDATE auth_providers SET '.implode(',',array_map(fn($c)=>"`$c`=?",$columns)).',last_modified_datetime=UTC_TIMESTAMP() WHERE id=?';$args=[...array_values($values),$id];}
    else{$sql='INSERT INTO auth_providers (`'.implode('`,`',$columns).'`) VALUES ('.implode(',',array_fill(0,count($columns),'?')).')';$args=array_values($values);}
    $db->prepare($sql)->execute($args);
    return $id?: (int)$db->lastInsertId();
}
