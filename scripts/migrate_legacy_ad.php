<?php
if(PHP_SAPI!=='cli'){http_response_code(404);exit;}
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/legacy_ad_migration.php';
$apply=in_array('--apply',$argv,true);
try{
    echo json_encode(migrateLegacyAd(connectToDatabase(),$apply),JSON_PRETTY_PRINT).PHP_EOL;
    echo $apply?"Migration complete. Imported LDAP providers remain disabled for review.\n":"Dry run only. Back up database and encryption key before running --apply.\n";
}catch(Throwable$e){fwrite(STDERR,'Migration stopped: '.$e->getMessage().PHP_EOL);exit(1);}
