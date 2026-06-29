<?php
session_start(['read_and_close' => true]);
require_once '../../config.php'; require_once '../../includes/functions.php'; require_once '../../includes/encryption.php'; require_once '../../includes/mail/MailboxProviderFactory.php';
header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success'=>false,'error'=>'Not authenticated']); exit; }
try { $conn=connectToDatabase(); $id=(int)($_GET['mailbox_id'] ?? 0); $st=$conn->prepare('SELECT * FROM target_mailboxes WHERE id=?'); $st->execute([$id]); $mb=decryptMailboxRow($st->fetch(PDO::FETCH_ASSOC)); SmtpMailer::test($mb); error_log('audit: mailbox_connection_test type=smtp mailbox_id='.$id); echo json_encode(['success'=>true]); } catch(Exception $e) { error_log('audit: mailbox_connection_test_failed type=smtp'); echo json_encode(['success'=>false,'error'=>$e->getMessage()]); }
