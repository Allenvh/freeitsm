<?php
/**
 * System Help — Calendar sync.
 * The big one of the six: an app registration, a permission with real blast
 * radius, a cron job, optional webhooks, and a separate feature (subscription
 * links) that people confuse with it.
 */
require __DIR__ . '/_init.php';

$helpSlug = 'calendar-sync';
require __DIR__ . '/_top.php';
?>

<!-- 1. Overview -->
<div class="help-section" id="overview">
    <div class="help-section-header"><?php echo helpSectionNum('overview'); ?>
        <div>
            <h3>Two different things on one page</h3>
            <p>Both put an analyst's scheduled work into a calendar, and they are worth telling apart before you configure either.</p>
        </div>
    </div>
    <div class="help-cards">
        <div class="help-card">
            <h4>Calendar sync (push)</h4>
            <p>FreeITSM writes appointments <strong>into the analyst's own calendar</strong>: an Exchange mailbox through Microsoft Graph, or a calendar on any <strong>CalDAV server</strong> (Nextcloud, Baïkal, Radicale, iCloud, Fastmail). Can be made two-way, so moving the appointment moves the ticket.</p>
        </div>
        <div class="help-card">
            <h4>Subscription links (feed)</h4>
            <p>The analyst publishes a <strong>secret link</strong> that any calendar app can subscribe to — Google, Apple, Outlook, anything. No app registration, no Microsoft. Read-only.</p>
        </div>
    </div>
    <div class="help-note"><strong>You can run either, both, or neither</strong>, and analysts opt in individually. If your calendars are on neither Microsoft 365 nor a CalDAV server, the subscription link is the whole answer and you can ignore the Connection section entirely.</div>
    <div class="help-note warn"><strong>"A database update is needed first."</strong> Run <a href="db-verify.php">Database Verification</a>, then come back. The page says this rather than offering settings it cannot store.</div>
</div>

<!-- 2. The permission -->
<div class="help-section" id="permission">
    <div class="help-section-header"><?php echo helpSectionNum('permission'); ?>
        <div>
            <h3>Before push will work — and what you are granting</h3>
            <p>One permission, and it is worth understanding its reach before you consent to it.</p>
        </div>
    </div>
    <p>The app registration needs <strong>Calendars.ReadWrite</strong> as an <strong>Application</strong> permission — not Delegated, which is a separate list in Azure and a common wrong turn — with admin consent granted.</p>
    <div class="help-note warn"><strong>Be aware what that permission actually allows.</strong> An Application-level Calendars.ReadWrite lets FreeITSM write to <strong>every mailbox in the tenant</strong>, not only your analysts'. It is the only shape of permission that lets a background service write to somebody's calendar without that person being signed in, which is why it is what this needs — but you should know you are granting it.</div>
    <div class="help-note ok"><strong>If that is more than you want to grant</strong>, scope the app to a mail-enabled security group containing your analysts, using an <strong>Application Access Policy</strong> in Exchange Online. The app then physically cannot reach any other mailbox. This is the recommended setup for anyone who has to justify the permission to a security team.</div>
</div>

<!-- CalDAV (#133) -->
<div class="help-section" id="caldav">
    <div class="help-section-header"><?php echo helpSectionNum('caldav'); ?>
        <div>
            <h3>A CalDAV server, instead or as well</h3>
            <p>For calendars that are not on Microsoft 365. Nextcloud, Baïkal, Radicale, SOGo, iCloud, Fastmail and anything else that speaks CalDAV.</p>
        </div>
    </div>
    <p>Press <strong>Add</strong> under Connections, choose <strong>A CalDAV server</strong> under Calendar system and enter the server's <strong>DAV address</strong>, which is not the same as its web page:</p>
    <div class="help-table"><table>
        <thead><tr><th>Server</th><th>Address to enter</th></tr></thead>
        <tbody>
            <tr><td>Baïkal</td><td><code>https://server/dav.php/</code></td></tr>
            <tr><td>Nextcloud</td><td><code>https://server/remote.php/dav/</code></td></tr>
            <tr><td>Radicale</td><td><code>https://server/</code></td></tr>
        </tbody>
    </table></div>
    <div class="help-note"><strong>🔑 There is no password on this page, on purpose.</strong> Microsoft lets one approved app write into everybody's calendar. A CalDAV server has no such thing, because each calendar belongs to its owner, so FreeITSM signs in <strong>as each analyst</strong>. Each analyst enters their own username and password (an app password, where the server offers them) under <strong>Preferences → My work calendar</strong>, presses <strong>Find</strong>, chooses a calendar and saves. The password is stored encrypted and never shown again.</div>
    <p>That changes who chooses the calendar. With Microsoft only an administrator can; with CalDAV only the analyst can, because only they have the password that reaches it. They can only pick calendars <strong>on the server you entered</strong>, and only ones that can hold events.</p>
    <p><strong>Test</strong> checks the address is a calendar server. Type a username and password into the optional sign-in boxes and it also lists the calendars that person would be offered. The sign-in is used for that test only and is not saved.</p>
    <div class="help-note"><strong>Sign-in method.</strong> Leave it on Automatic. A standard Baïkal wants Digest rather than Basic, and a server that is sent the wrong one answers exactly as if the password were wrong.</div>
    <div class="help-note ok"><strong>Everything else is the same</strong> as with Microsoft: scheduled tickets and tasks, two-way with the scheduled job, the deletion setting and its safeguards. The one difference is that CalDAV servers do not send change notifications, so changes made in a calendar arrive when the job runs.</div>
    <div class="help-note ok"><strong>You can have as many connections as you need</strong> - Microsoft 365 for most people and a Nextcloud for a few, several CalDAV servers, or several Microsoft tenants. Each analyst chooses which one their work goes to under <strong>Preferences → My work calendar</strong>; with only one connection there is nothing to choose. The analyst table shows who is on which.</div>
    <div class="help-note warn"><strong>Changing a connection between Microsoft and CalDAV</strong>, moving it to a different CalDAV server, or deleting it, first takes that connection's events back out of calendars and switches calendar sync off for the analysts using it, so each sets it up again. The page tells you how many events and analysts that is and asks before doing it. Analysts on other connections, and subscription links, are not affected.</div>
    <p>If an analyst adds a reminder or a note to one of these appointments in their own calendar app, FreeITSM keeps it when the ticket changes. It only rewrites the time, the title, the description and the link.</p>
</div>

<!-- 3. The connection -->
<div class="help-section" id="connection">
    <div class="help-section-header"><?php echo helpSectionNum('connection'); ?>
        <div>
            <h3>Setting up a Microsoft 365 connection</h3>
            <p>Press <strong>Add</strong> under Connections (or <strong>Edit</strong> on an existing one). Two ways to give it credentials.</p>
        </div>
    </div>
    <div class="help-steps">
        <div class="help-step"><div class="help-step-num">1</div><div><strong>Borrow the credentials from a mailbox</strong>, if one of your Microsoft intake mailboxes already has Azure credentials. Nothing to type. The page names which mailbox it is using, so this is never a mystery later.</div></div>
        <div class="help-step"><div class="help-step-num">2</div><div><strong>Or enter them</strong> — directory (tenant) ID, application (client) ID and client secret. The secret is stored encrypted and never shown again; leave it blank when editing to keep the one already saved.</div></div>
        <div class="help-step"><div class="help-step-num">3</div><div><strong>Press Test.</strong> It confirms the credentials work <em>and</em> that the permission has been granted — two different failures that look identical from the outside.</div></div>
        <div class="help-step"><div class="help-step-num">4</div><div><strong>Optionally probe a mailbox</strong> as part of the test, to confirm a specific address has a calendar FreeITSM can write to.</div></div>
    </div>
    <div class="help-note"><strong>"No Microsoft mailbox has credentials to borrow"</strong> is normal, not a fault. It happens when your analysts' calendars are in a different tenant from your intake mailboxes, or when your intake is not Microsoft at all. Enter credentials instead.</div>
    <p>If the test fails, the two things worth checking first are that the <strong>client secret has not expired</strong> — they do, and it is the most common cause of a sync that worked for months and then stopped — and that Calendars.ReadWrite is under <em>Application</em> permissions with consent granted.</p>
</div>

<!-- 4. Who syncs where -->
<div class="help-section" id="people">
    <div class="help-section-header"><?php echo helpSectionNum('people'); ?>
        <div>
            <h3>Which mailbox each analyst syncs to</h3>
            <p>🔑 Analysts choose <em>whether</em> their work is synced. Only an administrator chooses <em>where</em> it goes.</p>
        </div>
    </div>
    <p>The <strong>Connection</strong> column shows which connection each analyst uses. A muted name is the only connection there is, used without choosing; <strong>Not chosen yet</strong> means there are several and they have not picked one. For an analyst on a CalDAV connection the <strong>Calendar</strong> column names the calendar they chose, and there is nothing to set here.</p>
    <p>For Microsoft, by default FreeITSM uses the email address on the analyst's account — which is <strong>often not their mailbox</strong>. A local account with a made-up address, or a directory import keyed on something other than email, both produce an address that looks plausible and has no calendar behind it. Override it here.</p>
    <p>The <strong>Chosen by the analyst</strong> column is a read-out of what that person set in their own Preferences, not something you can click:</p>
    <div class="help-table"><table>
        <thead><tr><th>Shows</th><th>Meaning</th></tr></thead>
        <tbody>
            <tr><td><span class="help-pill">Off</span></td><td>They have not opted in. Nothing is written.</td></tr>
            <tr><td><span class="help-pill ok">Syncing</span></td><td>Push is on for them.</td></tr>
            <tr><td><span class="help-pill info">Subscribed</span></td><td>They are using a subscription link instead.</td></tr>
            <tr><td><span class="help-pill bad">Last sync failed</span></td><td>Something went wrong for that person — the reason is shown.</td></tr>
        </tbody>
    </table></div>
    <div class="help-note"><strong>You cannot switch sync on for somebody.</strong> That is deliberate: writing into a person's calendar without them asking is not a decision an administrator should be able to make on their behalf. If an analyst wants it, they turn it on in Preferences and you make sure the address is right.</div>
    <p>An analyst may also choose to include their <strong>tasks</strong> — planned work, due dates, or both. That is their choice too, and shown here for information.</p>
</div>

<!-- 5. Two-way -->
<div class="help-section" id="inbound">
    <div class="help-section-header"><?php echo helpSectionNum('inbound'); ?>
        <div>
            <h3>Changes made in the calendar</h3>
            <p>Move one of these appointments in Outlook and the ticket follows — but only if a scheduled job is running.</p>
        </div>
    </div>
    <div class="help-note warn"><strong>This needs <code>cron/calendar_sync_pull.php</code> on a schedule</strong> — every 5 minutes is sensible. Without it the sync is one way, which is exactly how it behaves today if you do nothing. A dead cron job never announces itself, so if changes stop arriving, check this first.</div>

    <h4>Deleting the appointment</h4>
    <p><strong>Deleting the appointment unschedules the ticket</strong> is off by default. With it on, an analyst who deletes one on their phone finds the ticket already unscheduled when they sit down — which is often exactly the point.</p>
    <div class="help-note ok"><strong>Two safeguards.</strong> Every such change is written to the ticket's history, so it is never a silent edit. And <strong>an unusually large number of deletions in one go is refused rather than obeyed</strong>, on the grounds that it is far more likely to be a fault — a sync glitch, a mailbox being rebuilt — than a genuine instruction. With the setting off, a deleted appointment simply reappears next time the ticket changes.</div>

    <h4>Notifications (optional)</h4>
    <p>Leave the <strong>Notification URL</strong> blank and the scheduled check alone is perfectly good: changes arrive within a few minutes. Fill it in and Microsoft tells FreeITSM the moment something changes, so it lands in seconds.</p>
    <ul>
        <li>It must be an <strong>HTTPS address Microsoft can reach from the internet</strong>, ending <code>/api/calendar/graph_notify.php</code>.</li>
        <li>The page can fill it in from the address you are using now — but <strong>check it is reachable from outside</strong>. Behind a proxy or a tunnel it often is not, and a wrong one fails with a message about <em>validation</em> rather than about the address, which sends people looking in the wrong place.</li>
        <li>⚠️ <strong>The scheduled job is still required either way.</strong> It renews the subscriptions, and it catches anything a missed notification would otherwise lose. Notifications are an accelerator, not a replacement.</li>
    </ul>

    <h4>Health</h4>
    <p>The page reports when calendars were last checked and how many people are subscribed. If it says calendars were last checked a long time ago, that usually means the job has stopped — and because that job also renews subscriptions, notifications stop a few days later too. If you deliberately run it less often than every half hour, this is expected and the note says so.</p>
</div>

<!-- 6. Subscription links -->
<div class="help-section" id="feeds">
    <div class="help-section-header"><?php echo helpSectionNum('feeds'); ?>
        <div>
            <h3>Subscription links</h3>
            <p>The other half of the page, and the one that works with any calendar app.</p>
        </div>
    </div>
    <p>Analysts can publish their scheduled work as a calendar subscription link. It works with Google Calendar, Apple Calendar, Outlook, Thunderbird — anything that speaks iCalendar — and needs no app registration and no Microsoft tenant.</p>
    <div class="help-note warn"><strong>A link is a secret URL, not a login.</strong> Anyone holding it can read what it shows, and it cannot be made to ask who they are. That is how calendar subscriptions work everywhere, and it is why you get a say in how much they may contain.</div>
    <div class="help-table"><table>
        <thead><tr><th>Setting</th><th>What a link may contain</th></tr></thead>
        <tbody>
            <tr><td><strong>Yes — including ticket subjects</strong></td><td>The most useful calendar. Also the most revealing if a link leaks.</td></tr>
            <tr><td><strong>Yes — ticket numbers only</strong></td><td>The analyst sees when they are busy and which ticket, without the subject being readable by anyone who finds the URL. A good middle setting.</td></tr>
            <tr><td><strong>No</strong></td><td>Links are not offered.</td></tr>
        </tbody>
    </table></div>
    <div class="help-note"><strong>Analysts may always choose to publish less than this, never more.</strong> And switching it off <strong>immediately stops links that have already been handed out</strong> — you do not have to find them, and nobody keeps working access because they subscribed before you changed your mind.</div>
    <p>See <a href="../../calendar/help.php">the Calendar guide</a> for what analysts see at their end.</p>
</div>

<?php require __DIR__ . '/_bottom.php'; ?>
