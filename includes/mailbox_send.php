<?php
/**
 * Send one email FROM a ticket mailbox, through whatever that mailbox uses
 * (Microsoft Graph, Gmail, or IMAP/SMTP), and record it in the send log.
 *
 * Written for GH #170 (the oversized-email alert and auto-reply), and kept
 * separate from includes/template_email.php on purpose: a template email is about
 * a TICKET (merge codes, the SDREF subject, a copy filed on the ticket). This is a
 * plain message about the mailbox itself, which may have no ticket at all - a
 * rejected email never made one.
 *
 * Never throws. Returns null on success, or the reason it could not send.
 */

require_once __DIR__ . '/mailbox_provider.php';   // MailboxProvider
require_once __DIR__ . '/email_log.php';

/**
 * Sends one HTML email from a mailbox through its own provider (IMAP/SMTP, Gmail or
 * Microsoft Graph) and logs it.
 *
 * Returns null on success, or the error message when sending failed.
 *
 * @param array $mailbox  the target_mailboxes row, decrypted; its provider ('imap', 'google', else Microsoft) picks the route
 * @param string $to  one recipient address
 * @param string $subject  the subject line
 * @param string $htmlBody  the HTML body
 * @param string $route  what the send log records it as, e.g. 'oversize_alert' or 'oversize_reply'
 */
function mailboxSendSimple(PDO $conn, array $mailbox, string $to, string $subject, string $htmlBody, string $route, ?int $ticketId = null): ?string
{
    $provider = MailboxProvider::fromMailbox($mailbox);
    try {
        if ($provider === MailboxProvider::Imap) {
            require_once __DIR__ . '/mailbox_imap.php';
            imapSmtpSend($mailbox, $to, '', $subject, $htmlBody);
        } elseif ($provider === MailboxProvider::Google) {
            require_once __DIR__ . '/gmail.php';
            $cleaned = preg_replace('/[\x00-\x1F\x7F]/', '', (string)($mailbox['token_data'] ?? ''));
            $tokenData = $cleaned !== '' ? json_decode($cleaned, true) : null;
            if (!$tokenData || !isset($tokenData['access_token'])) throw new Exception('The mailbox is not signed in.');
            $token = gmailGetValidAccessToken($conn, $mailbox, $tokenData);
            if (!$token) throw new Exception('Could not obtain an access token for this mailbox.');
            gmailSendEmail($token, $to, $subject, $htmlBody, $mailbox['target_mailbox'] ?? '');
        } else {
            require_once __DIR__ . '/template_email.php';
            $graph = templateGraphContext($conn, $mailbox);
            if (!$graph) throw new Exception('Could not obtain an access token for this mailbox.');
            templateSendViaGraph($graph['token'], [
                'message' => [
                    'subject' => $subject,
                    'body' => ['contentType' => 'HTML', 'content' => $htmlBody],
                    'toRecipients' => [['emailAddress' => ['address' => $to]]],
                ],
                'saveToSentItems' => true,
            ], $graph['base']);
        }
        emailLogSent($conn, $mailbox, $route, $to, $subject, $ticketId);
        return null;
    } catch (Throwable $e) {
        emailLogFailed($conn, $mailbox, $route, $to, $subject, $e->getMessage(), $ticketId);
        return $e->getMessage();
    }
}
