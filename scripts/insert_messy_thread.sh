#!/usr/bin/env bash
#
# Insert a long, messy email chain onto a ticket — a test fixture for the
# long-message collapsing added for discussion #104.
#
# WHY THIS EXISTS
#   A clean demo database is the worst place to test this. The emails that
#   actually break a reading pane are the ones a real service desk gets:
#   Outlook top-posts with the whole chain re-quoted at every hop, "EXTERNAL
#   EMAIL" banners, four-paragraph legal disclaimers, mobile signatures, an
#   out-of-office in the middle, and a bounce. This makes one.
#
#   It deliberately includes the cases our quote stripping is KNOWN to miss —
#   a top-post with no blockquote, and a forward — because the point of a
#   fixture is to reproduce the problem, not to look tidy.
#
# SAFETY
#   Every row it writes has subject prefix [MESSY-TEST] and is listed at the
#   end, so you can undo it. Nothing existing is modified.
#
# USAGE
#   scripts/insert_messy_thread.sh                 # newest open ticket
#   scripts/insert_messy_thread.sh 109             # a specific ticket id
#   scripts/insert_messy_thread.sh --clean         # remove everything it made
#
set -euo pipefail

MYSQL="C:/wamp64/bin/mysql/mysql9.1.0/bin/mysql.exe"
DB="FREEITSM"
DBUSER="root"
# Credentials live outside the web root; see c:\wamp64\db_config.php.
# ⚠️ sed, not `grep -P`: this environment reports "-P supports only unibyte
# and UTF-8 locales" and refuses.
DBPASS="$(sed -n "s/.*DB_PASSWORD'[^']*'\([^']*\)'.*/\1/p" c:/wamp64/db_config.php 2>/dev/null | head -1)"
[ -z "$DBPASS" ] && { echo "Could not read DB_PASSWORD from c:\\wamp64\\db_config.php"; exit 1; }

# ⚠️ stderr is filtered, NOT discarded. Swallowing it made a failed INSERT
# look like a silent success under `set -e` — the exact trap this script is
# meant to help find in other people's data.
run() { "$MYSQL" -u "$DBUSER" -p"$DBPASS" "$DB" -sN -e "$1" 2>&1 | grep -v "insecure" || true; }

if [ "${1:-}" = "--clean" ]; then
    n=$(run "SELECT COUNT(*) FROM emails WHERE subject LIKE '[MESSY-TEST]%';")
    run "DELETE FROM emails WHERE subject LIKE '[MESSY-TEST]%';"
    echo "Removed $n test message(s)."
    exit 0
fi

TICKET="${1:-}"
if [ -z "$TICKET" ]; then
    TICKET=$(run "SELECT id FROM tickets ORDER BY id DESC LIMIT 1;")
fi
[ -z "$TICKET" ] && { echo "No tickets found."; exit 1; }
REF=$(run "SELECT ticket_number FROM tickets WHERE id = $TICKET;")
echo "Adding a messy chain to ticket $TICKET ($REF)…"

# ── the pieces a real chain is made of ───────────────────────────────────────
BANNER='<div style="background:#fff3cd;border:1px solid #ffeeba;padding:8px;margin-bottom:12px;font-family:Arial"><b>[EXTERNAL EMAIL]</b> Do not click links or open attachments unless you recognise the sender.</div>'

DISCLAIMER='<div style="font-size:10px;color:#888;border-top:1px solid #ccc;margin-top:24px;padding-top:8px">This e-mail and any attachments are confidential and intended solely for the addressee. If you have received this message in error please notify the sender immediately and delete it from your system. Any unauthorised copying, disclosure or distribution of the material in this e-mail is strictly forbidden. Contoso Ltd is registered in England and Wales, company number 04729213. Registered office: 14 Bishopsgate, London EC2N 3AR. Contoso Ltd accepts no liability for any loss or damage arising from the use of this e-mail or its attachments. Please consider the environment before printing this e-mail.</div>'

SIG='<div style="font-family:Arial"><br>--<br><b>Karen Whitfield</b><br>Finance Business Partner | Contoso Ltd<br>T: +44 20 7946 0821 | M: +44 7700 900142<br><a href="https://contoso.example">contoso.example</a></div>'

MOBILE_SIG='<div style="color:#777;font-size:12px"><br>Sent from my iPhone</div>'

# One hop of an Outlook-style top-post: the new text, then the whole prior
# chain re-quoted with a header block. No <blockquote> anywhere — which is
# exactly why our stripping misses it.
hop() {  # $1 = new text, $2 = prior chain
    printf '%s' "<div style=\"font-family:Arial\">$1</div>$SIG<div style=\"border-top:1px solid #ccc;margin-top:18px;padding-top:12px;font-family:Arial\"><b>From:</b> Service Desk &lt;support@freeitsm.co.uk&gt;<br><b>Sent:</b> 12 August 2026 09:14<br><b>To:</b> Karen Whitfield &lt;k.whitfield@contoso.example&gt;<br><b>Subject:</b> RE: [$REF] Expenses portal rejecting receipts</div>$2"
}

C1="<div style=\"font-family:Arial\">Thanks for getting back to me. I tried again this morning and it is still doing it.</div>"
C2=$(hop "I have attached a screenshot. It happens on Chrome and on Edge, and my colleague Ravi sees the same thing." "$C1")
C3=$(hop "Any update on this please? The month end deadline is Friday and we have about forty receipts stuck." "$C2")
C4=$(hop "Following up again. I appreciate you are busy but this is now blocking the whole finance team." "$C3")
C5=$(hop "Adding Priya from Procurement who has the same problem on her account, so it is not just Finance." "$C4")

# ── the rows ─────────────────────────────────────────────────────────────────
add() {  # $1 = direction, $2 = subject, $3 = body, $4 = from
    local body="${3//\'/\'\'}"
    run "INSERT INTO emails (ticket_id, direction, subject, body_content, body_type, from_address, from_name, to_recipients, received_datetime, is_read)
         VALUES ($TICKET, '$1', '[MESSY-TEST] $2', '$body', 'html', '$4', 'Karen Whitfield', 'support@freeitsm.co.uk', UTC_TIMESTAMP(), 1);"
}

add Inbound  "Expenses portal rejecting receipts"          "$BANNER$C5$DISCLAIMER" "k.whitfield@contoso.example"
add Inbound  "Automatic reply: Expenses portal rejecting"  "<div style=\"font-family:Arial\">I am out of the office until Monday 18 August with limited access to email. For anything urgent please contact the Finance mailbox.</div>$DISCLAIMER" "p.okafor@contoso.example"
add Inbound  "FW: Expenses portal rejecting receipts"      "$BANNER<div style=\"font-family:Arial\">Forwarding for visibility — see the chain below, this has been going on for over a week.</div>$MOBILE_SIG<div style=\"border-top:1px solid #ccc;margin-top:18px;padding-top:12px\"><b>---------- Forwarded message ----------</b></div>$C5$DISCLAIMER" "d.mercer@contoso.example"
add Inbound  "Undeliverable: Expenses portal rejecting"    "<div style=\"font-family:Arial\">Your message could not be delivered to one or more recipients.</div><pre style=\"font-size:11px;color:#555\">Diagnostic information for administrators:
Generating server: EX19-HUB-02.contoso.example
p.okafor@contoso.example
Remote server returned '550 5.1.1 RESOLVER.ADR.RecipNotFound; not found'
Original message headers:
Received: from EX19-MBX-04.contoso.example (10.44.19.22) by
 EX19-HUB-02.contoso.example (10.44.19.11) with Microsoft SMTP Server
 (version=TLS1_2, cipher=TLS_ECDHE_RSA_WITH_AES_256_GCM_SHA384) id 15.2.1544.4;
 Tue, 12 Aug 2026 09:14:22 +0100</pre>$C5" "postmaster@contoso.example"
add Inbound  "RE: Expenses portal rejecting receipts"      "<div style=\"font-family:Arial\">Understood, thank you.</div>$MOBILE_SIG" "k.whitfield@contoso.example"

# Two real duplicates, because a long ticket is more often the same message
# five times than one long message. The first is a distribution list
# delivering twice — byte-identical. The second is the sender resending
# after hearing nothing, identical but for one appended line, which is the
# case an exact hash would miss.
add Inbound  "Expenses portal rejecting receipts"          "$BANNER$C5$DISCLAIMER" "k.whitfield@contoso.example"
add Inbound  "Expenses portal rejecting receipts (resend)" "$BANNER$C5$DISCLAIMER<div>Resending as I have not heard back.</div>" "k.whitfield@contoso.example"

echo
run "SELECT CONCAT('  ', id, '  ', direction, '  ', CHAR_LENGTH(body_content), ' chars  ', LEFT(subject, 46)) FROM emails WHERE subject LIKE '[MESSY-TEST]%' ORDER BY id;"
echo
echo "Done. Open ticket $TICKET ($REF) in the inbox."
echo "Undo with: scripts/insert_messy_thread.sh --clean"
