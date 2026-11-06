# includes/mailbox_imap.php

> PHP - 733 lines

Basic IMAP / SMTP Mailbox Helper Functions

Provides read (IMAP) + send (SMTP) operations for "basic" mailboxes that
authenticate with a plain username + password instead of OAuth — a hosting /
cPanel inbox, Fastmail, Zoho, etc.

Mirrors the shape of includes/mailbox_graph.php (Microsoft) and includes/gmail.php
(Google): every message is normalised to the SAME Graph-like array structure so the
downstream import code in check_mailbox_email.php needs no provider-specific handling.

Reading uses PHP's `imap_*` extension. Sending uses a minimal, dependency-free
SMTP client (fsockopen) — the repo vendors no mail library, so a small
self-contained sender is proportional. The message itself (HTML body, inline
images, attachments) is built by includes/mime_message.php, shared with the
Gmail send path. See the Basic-IMAP-Mailboxes wiki page for the design.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [imapExtensionAvailable()](/code/functions/imapExtensionAvailable.md) | 24 | True when the PHP IMAP extension is loaded (self-hosters must enable it). |
| [imapMailboxRef()](/code/functions/imapMailboxRef.md) | 35 | Build the IMAP connection reference string, e.g. {mail.example.com:993/imap/ssl}INBOX. Encryption: 'ssl' → implicit TLS, 'tls' → STARTTLS, anything else → plaintext. |
| [imapStreamFor()](/code/functions/imapStreamFor.md) | 61 | Open (and cache within the request) an IMAP stream for a mailbox/folder. The cache lets the inbound poll reuse one authenticated connection across the fetch + per-email post-actions rather than logging in repeatedly. |
| [imapGetEmails()](/code/functions/imapGetEmails.md) | 98 | Fetch unread messages, normalised to the Graph-like structure the importer expects. Bodies are fetched with FT_PEEK so messages stay UNSEEN until the configured post-import action runs (mark read / delete / move). |
| [imapNormaliseMessage()](/code/functions/imapNormaliseMessage.md) | 130 | Build one normalised (Graph-like) message array from a UID. |
| [imapWalkStructure()](/code/functions/imapWalkStructure.md) | 226 | Recursively walk an `imap_fetchstructure()` tree, accumulating decoded HTML/plain body text and Graph-shaped attachment entries. |
| [imapDecodePart()](/code/functions/imapDecodePart.md) | 310 | Decode a transfer-encoded body part (base64 / quoted-printable / raw). |
| [imapToUtf8()](/code/functions/imapToUtf8.md) | 327 | Convert a string to UTF-8 from the part's declared charset (best effort). |
| [imapDecodeHeader()](/code/functions/imapDecodeHeader.md) | 352 | Decode a MIME-encoded header value (e.g. =?UTF-8?B?...?=) to a UTF-8 string. |
| [imapParseAddressList()](/code/functions/imapParseAddressList.md) | 376 | Parse an RFC 2822 address-list header into the Graph-like recipient shape. Uses imap_rfc822_parse_adrlist when available; falls back to a light regex. |
| [imapHandleAfterProcessing()](/code/functions/imapHandleAfterProcessing.md) | 419 | Apply the post-import (or post-reject) action to a message by UID. Mirrors `handleEmailAfterProcessing()`'s action vocabulary for Microsoft/Google. |
| [imapSmtpSend()](/code/functions/imapSmtpSend.md) | 464 | Send an HTML email via SMTP (with auth). Minimal, dependency-free. |
| [imapSmtpClientHostname()](/code/functions/imapSmtpClientHostname.md) | 567 | Derive a sensible EHLO hostname from the sender address. |
| [imapSplitAddresses()](/code/functions/imapSplitAddresses.md) | 577 | Split a "a@b; c@d, e@f" string into a list of valid addresses. |
| [imapSmtpCommand()](/code/functions/imapSmtpCommand.md) | 595 | Send one SMTP command line and assert the reply code. |
| [imapSmtpExpect()](/code/functions/imapSmtpExpect.md) | 606 | Read a (possibly multi-line) SMTP reply and assert its status code. |
| [imapListFolders()](/code/functions/imapListFolders.md) | 632 | List every folder on the server, with the {host} prefix stripped. |
| [imapVerifyFolder()](/code/functions/imapVerifyFolder.md) | 684 | Verify a folder exists in a basic IMAP mailbox and report its message counts. |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/mime_message.php](/code/files/includes/mime_message.php.md)

**Included by:** [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md), [includes/mailbox_send.php](/code/files/includes/mailbox_send.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Calls:** [mimeBuildMessage()](/code/functions/mimeBuildMessage.md)

