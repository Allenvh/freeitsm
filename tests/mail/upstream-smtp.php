<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once __DIR__.'/../../includes/mailbox_imap.php';
foreach (['send','reject'] as $mode) {
    $dir=sys_get_temp_dir().'/smtp-upgrade-'.bin2hex(random_bytes(5)); mkdir($dir,0700);
    $secret=bin2hex(random_bytes(24));
    file_put_contents($dir.'/config',json_encode(['username'=>'smtp-user','password'=>$secret,'auth'=>true]));
    $proc=proc_open(['php',__DIR__.'/smtp_fixture.php',$dir.'/config',$dir.'/ready',$dir.'/result'],[0=>['pipe','r'],1=>['file',$dir.'/log','a'],2=>['file',$dir.'/log','a']],$pipes);
    try {
        for($i=0;$i<100&&!file_exists($dir.'/ready');$i++)usleep(20000);
        $address=file_get_contents($dir.'/ready'); $port=(int)substr($address,strrpos($address,':')+1);
        $mailbox=['smtp_server'=>'127.0.0.1','smtp_port'=>$port,'smtp_encryption'=>'none','smtp_username'=>'smtp-user','smtp_password'=>$mode==='reject'?'wrong':$secret,'imap_username'=>'different-imap-user','imap_password'=>'different-imap-password','target_mailbox'=>'helpdesk@example.test'];
        try {
            imapSmtpSend($mailbox,'recipient@example.test','','Upgrade regression','<p>Reply</p>');
            if($mode==='reject')throw new LogicException('Bad password accepted');
        } catch (Exception $e) {
            if($mode!=='reject' || !str_contains($e->getMessage(),'535') || str_contains($e->getMessage(),$secret))throw $e;
        }
        proc_close($proc); $proc=null;
        $result=json_decode(file_get_contents($dir.'/result'),true);
        if($result['received']!==($mode==='send'))throw new RuntimeException('Unexpected SMTP delivery');
        echo "PASS upstream SMTP $mode with independent credentials and no OAuth\n";
    } finally {
        if(is_resource($proc)){proc_terminate($proc);proc_close($proc);}
        foreach(glob($dir.'/*') as $f)unlink($f); rmdir($dir);
    }
}
