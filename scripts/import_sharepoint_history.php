<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../includes/functions.php';
require_once __DIR__.'/../includes/sharepoint_import.php';
$options=getopt('',['manifest:','actor:','expected-database:','apply']);
try {
    $db=connectToDatabase();
    if (empty($options['expected-database']) || $options['expected-database']!==$db->query('SELECT DATABASE()')->fetchColumn()) throw new SharePointImportError('Supply --expected-database matching the intended database.');
    if (empty($options['manifest']) || empty($options['actor'])) throw new SharePointImportError('Supply --manifest and the active administrator --actor ID.');
    $plan=sharePointImportLoad($db,$options['manifest'],(int)$options['actor']);
    if (!array_key_exists('apply',$options)) {
        echo json_encode(['mode'=>'preview','database'=>$options['expected-database'],'tickets'=>array_map(fn($i)=>['source_id'=>$i['source_id'],'action'=>$i['action'],'ticket_id'=>$i['ticket_id'],'comments'=>count($i['history']['comments']),'files'=>count($i['history']['files'])],$plan['tickets'])],JSON_PRETTY_PRINT).PHP_EOL;
    } else {
        echo json_encode(['mode'=>'apply','tickets'=>sharePointImportApply($db,$plan,(int)$options['actor'])],JSON_PRETTY_PRINT).PHP_EOL;
    }
} catch (Throwable $e) {
    fwrite(STDERR,$e instanceof SharePointImportError?$e->getMessage().PHP_EOL:"Historical import failed. Check the manifest, schema, attachment settings and encryption key. No credential values are displayed.\n");
    exit(1);
}
