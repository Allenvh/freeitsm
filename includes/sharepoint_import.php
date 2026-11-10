<?php
/** Maintenance-only historical import. The HTTP/API adapters never call this. */
require_once __DIR__ . '/services/tickets.php';
require_once __DIR__ . '/uploads.php';

final class SharePointImportError extends RuntimeException {}

function sharePointImportCanonical($value) {
    if (!is_array($value)) return $value;
    if (!array_is_list($value)) ksort($value,SORT_STRING);
    foreach ($value as $key=>$child) $value[$key]=sharePointImportCanonical($child);
    return $value;
}

function sharePointImportFingerprint(array $item): string {
    $data=['payload'=>$item['payload'],'history'=>$item['history'],'source_fields'=>$item['source_fields']];
    return hash('sha256',json_encode(sharePointImportCanonical($data),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_LINE_TERMINATORS|JSON_THROW_ON_ERROR));
}

function sharePointImportDate($value): string {
    $date = is_string($value) ? DateTimeImmutable::createFromFormat('!Y-m-d H:i:s', $value, new DateTimeZone('UTC')) : false;
    if (!$date || $date->format('Y-m-d H:i:s') !== $value) throw new SharePointImportError('Invalid historical UTC date.');
    return $value;
}

function sharePointImportLedgerReady(PDO $db): bool {
    $s=$db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='sharepoint_ticket_imports'");
    $s->execute(); return (bool)$s->fetchColumn();
}

function sharePointImportExisting(PDO $db, string $source, string $id): ?array {
    if (!sharePointImportLedgerReady($db)) return null;
    $q=$db->prepare('SELECT fingerprint,ticket_id FROM sharepoint_ticket_imports WHERE source_hash=? AND source_id=?');
    $q->execute([hash('sha256',$source),$id]);
    return $q->fetch(PDO::FETCH_ASSOC) ?: null;
}

function sharePointImportFile(string $root, array $file): string {
    $relative=$file['path'] ?? '';
    if (!is_string($relative) || str_contains($relative,"\0") || preg_match('~(^[/\\\\]|^[a-zA-Z]:)~',$relative)) throw new SharePointImportError('Invalid bundle file path.');
    $path=realpath($root.DIRECTORY_SEPARATOR.$relative);
    if (!$path || !str_starts_with($path,$root.DIRECTORY_SEPARATOR) || !is_file($path)) throw new SharePointImportError('Bundle file is missing or outside the bundle.');
    if (!isset($file['sha256'],$file['size'],$file['name']) || !is_string($file['name']) || trim($file['name'])==='' || !is_int($file['size']) || !is_string($file['sha256']) || !preg_match('/^[a-f0-9]{64}$/D',$file['sha256'])
        || filesize($path)!==(int)$file['size'] || !hash_equals($file['sha256'],hash_file('sha256',$path))) throw new SharePointImportError('Bundle file size or hash changed.');
    if (filesize($path)<=0 || filesize($path)>attachmentMaxBytes()) throw new SharePointImportError('A bundle file is empty or exceeds the configured attachment size limit.');
    return $path;
}

function sharePointImportLoad(PDO $db, string $manifest, int $actorId): array {
    if (PHP_SAPI!=='cli' || !analystIsAdmin($db,$actorId)) throw new SharePointImportError('An active local administrator is required.');
    $root=realpath(dirname($manifest));
    if (!$root || !is_file($manifest)) throw new SharePointImportError('Historical manifest not found.');
    $application=realpath(dirname(__DIR__));
    if ($root===$application || str_starts_with($root,$application.DIRECTORY_SEPARATOR)) throw new SharePointImportError('Keep the export bundle outside the application/web root.');
    $document=json_decode(file_get_contents($manifest),true,512,JSON_THROW_ON_ERROR);
    if (($document['format']??'')!=='freeitsm-sharepoint-history-v1' || !is_string($document['source']??null) || trim($document['source'])==='' || strlen($document['source'])>4096 || !is_array($document['tickets']??null) || !$document['tickets']) throw new SharePointImportError('Unsupported or empty historical manifest.');
    $seen=[];$plan=[];
    foreach ($document['tickets'] as $item) {
        $id=(string)($item['source_id']??'');
        if (!preg_match('/^[1-9][0-9]*$/D',$id) || strlen($id)>255 || isset($seen[$id])) throw new SharePointImportError('Source IDs must be unique SharePoint item numbers.');
        $seen[$id]=true;
        if (!preg_match('/^[a-f0-9]{64}$/D',$item['fingerprint']??'')) throw new SharePointImportError('Invalid source fingerprint.');
        if (!isset($item['payload'],$item['history'],$item['source_fields']) || !hash_equals($item['fingerprint'],sharePointImportFingerprint($item))) throw new SharePointImportError('The prepared manifest changed. Regenerate it rather than editing it.');
        $history=$item['history']??[];
        $created=sharePointImportDate($history['created_at']??null);$modified=sharePointImportDate($history['modified_at']??null);
        if ($created>$modified || $modified>gmdate('Y-m-d H:i:s')) throw new SharePointImportError('Historical timestamps are out of order or in the future.');
        $payload=$item['payload']??[];
        if (empty($payload['company_id']) || !getTenantById($db,(int)$payload['company_id'])) throw new SharePointImportError('Historical tickets require an existing explicit company.');
        if (!is_array($history['files']??null) || !is_array($history['comments']??null)) throw new SharePointImportError('Historical files/comments are missing.');
        foreach ($history['files'] as $file) sharePointImportFile($root,$file);
        foreach ($history['comments'] as $comment) {
            sharePointImportDate($comment['created_at']??null);
            if (!is_string($comment['text']??null) || trim($comment['text'])==='') throw new SharePointImportError('Historical comment text is missing.');
        }
        $existing=sharePointImportExisting($db,$document['source'],$id);
        if ($existing && (!hash_equals($existing['fingerprint'],$item['fingerprint']) || !$existing['ticket_id'])) throw new SharePointImportError('Previously imported source data changed or its ticket was removed. Reconcile before importing.');
        $item['action']=$existing?'skip':'create';$item['ticket_id']=$existing ? (int)$existing['ticket_id'] : null;
        $plan[]=$item;
    }
    return ['source'=>$document['source'],'root'=>$root,'tickets'=>$plan];
}

function sharePointImportApply(PDO $db, array $plan, int $actorId): array {
    if (PHP_SAPI!=='cli' || !analystIsAdmin($db,$actorId)) throw new SharePointImportError('An active local administrator is required.');
    $db->exec('CREATE TABLE IF NOT EXISTS sharepoint_ticket_imports (
        source_hash CHAR(64) NOT NULL,source_id VARCHAR(255) NOT NULL,source_ref TEXT NOT NULL,
        fingerprint CHAR(64) NOT NULL,ticket_id INT NULL,source_json LONGTEXT NOT NULL,
        imported_by INT NOT NULL,imported_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY(source_hash,source_id),
        CONSTRAINT fk_sharepoint_import_ticket FOREIGN KEY(ticket_id) REFERENCES tickets(id) ON DELETE SET NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    $name=$db->prepare('SELECT full_name FROM analysts WHERE id=?');$name->execute([$actorId]);
    $ctx=new ActorContext($actorId,null,'migration','en',(string)$name->fetchColumn());
    $result=[];
    foreach ($plan['tickets'] as $item) {
        $written=[];
        try {
            $db->beginTransaction();
            // INSERT's unique key serialises competing imports. The ledger and ticket
            // commit together; a crash cannot leave an unrecorded committed ticket.
            try {
                $db->prepare('INSERT INTO sharepoint_ticket_imports(source_hash,source_id,source_ref,fingerprint,source_json,imported_by) VALUES(?,?,?,?,?,?)')->execute([
                    hash('sha256',$plan['source']),$item['source_id'],$plan['source'],$item['fingerprint'],json_encode($item,JSON_THROW_ON_ERROR),$actorId]);
            } catch (PDOException $e) {
                $db->rollBack();
                $existing=sharePointImportExisting($db,$plan['source'],$item['source_id']);
                if (!$existing || !hash_equals($existing['fingerprint'],$item['fingerprint']) || !$existing['ticket_id']) throw new SharePointImportError('Source item conflicts with an existing import.');
                $result[]=['source_id'=>$item['source_id'],'action'=>'skipped','ticket_id'=>(int)$existing['ticket_id']];
                continue;
            }
            $history=$item['history'];
            $ticketId=TicketsService::createTicket($db,$ctx,(int)$item['payload']['company_id'],$item['payload'],null,
                'Historical SharePoint import; source item '.$item['source_id'].'; original dates preserved.','migration',$history);
            $q=$db->prepare('SELECT id FROM emails WHERE ticket_id=? AND is_initial=1 ORDER BY id LIMIT 1');$q->execute([$ticketId]);$emailId=(int)$q->fetchColumn();
            if ($history['files']) {
                // SharePoint files may have been staff-only. Do not silently make
                // them requester downloads merely because the ticket now has a portal.
                $db->prepare("INSERT INTO emails(subject,from_address,from_name,to_recipients,received_datetime,body_preview,body_content,body_type,has_attachments,is_read,ticket_id,is_initial,direction) VALUES(?,'','Historical SharePoint files','',?,'Historical staff-only files','<p>Historical associated files (staff only).</p>','html',1,1,?,0,'HistoricalInternal')")
                    ->execute([$item['payload']['subject'],$history['created_at'],$ticketId]);
                $emailId=(int)$db->lastInsertId();
            }
            $db->prepare('INSERT INTO ticket_notes(ticket_id,analyst_id,note_text,is_internal,created_datetime) VALUES(?,?,?,1,UTC_TIMESTAMP())')->execute([$ticketId,$actorId,$history['internal_context']??'Historical SharePoint import']);
            foreach ($history['comments'] as $comment) {
                $email=strtolower(trim((string)($comment['author_email']??'')));
                $author=$db->prepare('SELECT id FROM analysts WHERE LOWER(email)=?');$author->execute([$email]);$matches=$email!==''?$author->fetchAll(PDO::FETCH_COLUMN):[];
                $noteActor=count($matches)===1?(int)$matches[0]:$actorId;
                $attribution='Imported SharePoint comment '.$comment['source_comment_id'].'; original author: '.($comment['author_name']?:'unknown').($email!==''?' <'.$email.'>':'');
                $db->prepare('INSERT INTO ticket_notes(ticket_id,analyst_id,note_text,is_internal,created_datetime) VALUES(?,?,?,1,?)')->execute([$ticketId,$noteActor,$attribution."\n\n".$comment['text'],$comment['created_at']]);
            }
            foreach ($history['files'] as $file) {
                $source=sharePointImportFile($plan['root'],$file);
                $sub=floor($emailId/1000).'/'.$emailId;
                $bytes=file_get_contents($source);
                if (!is_string($bytes) || !hash_equals($file['sha256'],hash('sha256',$bytes))) throw new SharePointImportError('A source file changed while importing; the source ticket was rolled back.');
                $stored=uploadStoreBytes($bytes,$file['name'],dirname(__DIR__).'/tickets/attachments/'.$sub,attachmentRejectPolicy($db),attachmentAllowedTypes($db));
                if (!$stored['stored']) throw new SharePointImportError('A historical file was refused by the configured attachment policy; the entire ticket was rolled back.');
                $written[]=$stored['path'];
                $db->prepare('INSERT INTO email_attachments(email_id,filename,content_type,file_path,file_size,is_inline) VALUES(?,?,?,?,?,0)')->execute([$emailId,$stored['original_name'],$stored['mime'],$sub.'/'.$stored['stored_name'],$stored['size']]);
            }
            if ($history['files']) $db->prepare('UPDATE emails SET has_attachments=1 WHERE id=?')->execute([$emailId]);
            // No original closure timestamp exists in this schema: leave it unknown.
            // Do not fabricate a closure time from Modified or the migration time.
            $db->prepare('UPDATE tickets SET created_datetime=?,updated_datetime=?,closed_datetime=NULL WHERE id=?')->execute([$history['created_at'],$history['modified_at'],$ticketId]);
            $db->prepare('UPDATE sharepoint_ticket_imports SET ticket_id=? WHERE source_hash=? AND source_id=?')->execute([$ticketId,hash('sha256',$plan['source']),$item['source_id']]);
            $db->commit();
            $result[]=['source_id'=>$item['source_id'],'action'=>'created','ticket_id'=>$ticketId,'comments'=>count($history['comments']),'files'=>count($history['files'])];
        } catch (Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            foreach ($written as $path) @unlink($path);
            if ($e instanceof SharePointImportError) throw $e;
            throw new SharePointImportError('Import stopped at source item '.$item['source_id'].'. That ticket was rolled back; earlier committed items remain. Check its required fields and configuration before resuming.');
        }
    }
    return $result;
}
