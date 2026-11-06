# ssSendSystemEmail()

Send a system email to one recipient. Returns true on success, false if no mailbox is configured or the send fails (caller decides what to do — the verification flow fails closed rather than pretend it sent).

```php
function ssSendSystemEmail(
    PDO $conn,
    string $to,
    string $subject,
    string $htmlBody,
    string $route = 'portal'
): bool
```

Defined in [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$to` | `string` |  | the recipient's email address |
| `$subject` | `string` |  | the email subject |
| `$htmlBody` | `string` |  | the email body as HTML, already escaped by the caller |
| `$route` | `string` | `'portal'` | a key of EMAIL_LOG_ROUTES for the send log, e.g. 'portal' or 'training' |

**Returns:** `bool`

## Calls

[emailLogFailed()](/code/functions/emailLogFailed.md), [emailLogSent()](/code/functions/emailLogSent.md), [gmailGetValidAccessToken()](/code/functions/gmailGetValidAccessToken.md), [gmailSendEmail()](/code/functions/gmailSendEmail.md), [imapSmtpSend()](/code/functions/imapSmtpSend.md), [ssGetSendingMailbox()](/code/functions/ssGetSendingMailbox.md), [templateGraphContext()](/code/functions/templateGraphContext.md), [templateSendViaGraph()](/code/functions/templateSendViaGraph.md)

## Called from

- [api/assets/email_handover.php](/code/files/api/assets/email_handover.php.md)
- [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md)
- [api/lms/reminder_settings.php](/code/files/api/lms/reminder_settings.php.md)
- [api/lms/tests.php](/code/files/api/lms/tests.php.md)
- [api/self-service/register.php](/code/files/api/self-service/register.php.md)
- [api/self-service/request_password_reset.php](/code/files/api/self-service/request_password_reset.php.md)
- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md)
- [includes/lms_reminders.php](/code/files/includes/lms_reminders.php.md) - in [lmsRemindersRun()](/code/functions/lmsRemindersRun.md)
- [includes/managers.php](/code/files/includes/managers.php.md) - in [managersEmailNewTicket()](/code/functions/managersEmailNewTicket.md)
- [includes/notifications_router.php](/code/files/includes/notifications_router.php.md) - in [notificationsTaskAssignedEmail()](/code/functions/notificationsTaskAssignedEmail.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::send`
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksContractorEmail()](/code/functions/tasksContractorEmail.md)

