<?php
/**
 * The Zabbix half of system/integrations/help.php.
 *
 * Its own file for the same reason as help_slack.php: Zabbix shares nothing with
 * a tracker's setup - no connection, no project, nothing FreeITSM calls. Zabbix
 * calls in.
 *
 * ⚠️ The two traps in "If something is wrong" were found by running the setup
 * against a real Zabbix 7.0 (tests/zabbix/run.php), not guessed: a new media type
 * starts switched off, and Zabbix never notifies the user who made an update.
 *
 * Included by help.php inside .help-content; the enclosing markup is that file's.
 */
?>
<!-- 1 ───────────────────────────────────────────── -->
<div class="help-section" id="overview">
    <div class="help-section-header">
        <span class="help-section-num">1</span>
        <h3>What this does</h3>
    </div>
    <p>
        Zabbix watches your servers and network and raises a <strong>problem</strong> when something
        crosses a line - a disk nearly full, a host not answering. Without this, somebody has to notice
        it on the Zabbix screen and log a ticket by hand.
    </p>
    <p>Once this is set up:</p>
    <ul>
        <li><strong>A problem opens an incident</strong>, titled with the host and the problem, at the priority you map its severity to.</li>
        <li><strong>The server is linked.</strong> If a CMDB item has the same name as the Zabbix host, the ticket is linked to it - and filed in that item's company.</li>
        <li><strong>A repeat is not a second ticket.</strong> An update to the same problem - an acknowledgement, a comment - is added to the ticket's thread.</li>
        <li><strong>A recovery resolves it.</strong> When the problem clears, a "recovered" message is added and the ticket is moved to Resolved (or whatever you choose).</li>
    </ul>
    <p>Every alert received is listed at the bottom of the Zabbix page, with what was done with it.</p>
    <p>
        <strong>Zabbix calls FreeITSM - never the other way round.</strong> FreeITSM does not need your Zabbix
        server's address or a Zabbix password. The address on the Zabbix page is FreeITSM's own, for you to give to Zabbix.
    </p>
</div>

<!-- 2 ───────────────────────────────────────────── -->
<div class="help-section" id="token">
    <div class="help-section-header">
        <span class="help-section-num">2</span>
        <h3>Make the token</h3>
    </div>
    <p>
        On <strong>System &rsaquo; Integrations &rsaquo; Zabbix</strong>, click <strong>Make token</strong>. The token
        is the password Zabbix sends with every alert; anything without it is refused. Until there is a token
        the integration is off.
    </p>
    <p>
        Check the <strong>Address</strong> above it is one your Zabbix server can reach. It comes from the
        public base URL (<strong>Tickets &rsaquo; Settings &rsaquo; Email templates</strong>); if Zabbix is on your
        own network, the internal address of this server is often the right one to give it instead.
    </p>
</div>

<!-- 3 ───────────────────────────────────────────── -->
<div class="help-section" id="mediatype">
    <div class="help-section-header">
        <span class="help-section-num">3</span>
        <h3>Create the media type in Zabbix</h3>
    </div>
    <p>A <strong>media type</strong> is Zabbix's word for a way of sending a notification. This one sends it to FreeITSM.</p>
    <p><strong>The quick way - import it.</strong> On the FreeITSM Zabbix page, check <em>FreeITSM's address</em> is the one
       your Zabbix should use, then click <strong>Download for Zabbix import</strong>. In Zabbix, go to
       <strong>Alerts &rsaquo; Media types</strong>, click <strong>Import</strong>, choose the file and import it. It arrives
       complete: the address, your token, the script, the three message templates, switched on. Then go to step 4.</p>
    <p>Keep the file to yourself - it holds the token. After <strong>New token</strong>, download and import it again.</p>
    <p><strong>Or by hand:</strong></p>
    <ol>
        <li>In Zabbix, go to <strong>Alerts &rsaquo; Media types</strong> and click <strong>Create media type</strong>.</li>
        <li>Name it <strong>FreeITSM</strong> and choose the type <strong>Webhook</strong>.</li>
        <li>Under <strong>Parameters</strong>, first <strong>delete the five Zabbix fills in</strong> (URL, HTTPProxy, To, Subject, Message) - they belong to its example script. Names are case-sensitive, so its <em>Message</em> is not our <em>message</em>. Then add one line for each line in <em>Media type parameters</em> on the FreeITSM page: the name on the left of the <code>=</code>, the value on the right. Leave the values in braces exactly as they are - they are Zabbix macros, filled in for each alert.</li>
        <li>Paste the <em>Media type script</em> into <strong>Script</strong>.</li>
        <li>Leave <code>http_proxy</code> empty unless your Zabbix server reaches FreeITSM through a proxy - then put the proxy's address there.</li>
        <li>On the <strong>Message templates</strong> tab, add <em>Problem</em>, <em>Problem recovery</em> and <em>Problem update</em> - Zabbix fills each in; just add them. Without them Zabbix has no message to send and the action quietly does nothing.</li>
        <li>Make sure <strong>Enabled</strong> is ticked, and save.</li>
    </ol>
</div>

<!-- 4 ───────────────────────────────────────────── -->
<div class="help-section" id="action">
    <div class="help-section-header">
        <span class="help-section-num">4</span>
        <h3>Tell Zabbix which alerts to send</h3>
    </div>
    <ol>
        <li>Give the media type to a user: <strong>Users &rsaquo; Users</strong>, open the user, <strong>Media</strong> tab, <strong>Add</strong>, choose <strong>FreeITSM</strong>, and type anything in <em>Send to</em> (it is not used).</li>
        <li>Go to <strong>Alerts &rsaquo; Actions &rsaquo; Trigger actions</strong> and create an action. Its conditions choose which problems become incidents - a host group, a minimum severity, or both.</li>
        <li>On the <strong>Operations</strong> tab, add an operation that sends a message to that user through <strong>FreeITSM</strong>.</li>
        <li><strong>Add the same under Recovery operations</strong>, or tickets are never resolved. Add it under <em>Update operations</em> too, if you want acknowledgements and comments in Zabbix to reach the ticket.</li>
    </ol>
</div>

<!-- 5 ───────────────────────────────────────────── -->
<div class="help-section" id="choices">
    <div class="help-section-header">
        <span class="help-section-num">5</span>
        <h3>Choose what the incidents look like</h3>
    </div>
    <ul>
        <li><strong>Raised as</strong> - the requester the tickets come from. The default address can never receive mail, so no acknowledgement email goes anywhere. Use a real monitoring mailbox if you would rather it did.</li>
        <li><strong>Company</strong> (with more than one company) - where an incident goes when its server is not in the CMDB. A server that is goes to its own company.</li>
        <li><strong>Team</strong> and <strong>Origin</strong> - optional, applied to every incident.</li>
        <li><strong>Severity &rarr; priority</strong> - starts from your own priorities: Disaster gets the most urgent, High the next, Average your default, and the rest the least urgent. Change any of them.</li>
        <li><strong>When the problem clears</strong> - the status a recovery moves the ticket to, or <em>Leave the status alone</em> if you would rather a person decided.</li>
    </ul>
</div>

<!-- 6 ───────────────────────────────────────────── -->
<div class="help-section" id="try">
    <div class="help-section-header">
        <span class="help-section-num">6</span>
        <h3>Try it</h3>
    </div>
    <p>Two halves, tested from either end:</p>
    <ul>
        <li><strong>FreeITSM's half - Send a test alert</strong> on the Zabbix page. A test problem goes through exactly what a real alert does, raising an incident titled <code>[Zabbix] freeitsm-test: Test alert from FreeITSM</code> with your settings. Then <strong>Send the recovery</strong> and watch it resolve. The test incident stays so you can see what a real one looks like; close it like any other ticket.</li>
        <li><strong>The network half - Test</strong> on the media type in Zabbix. Only this proves your Zabbix server can reach FreeITSM. Within a few seconds it appears under <strong>Recent alerts</strong>.</li>
    </ul>
</div>

<!-- 7 ───────────────────────────────────────────── -->
<div class="help-section" id="trouble">
    <div class="help-section-header">
        <span class="help-section-num">7</span>
        <h3>If something is wrong</h3>
    </div>
    <ul>
        <li><strong>Nothing arrives.</strong> In Zabbix, look at <strong>Reports &rsaquo; Action log</strong>: every attempt is there with its error. <em>Media type disabled</em> means the Enabled tick was left off - a new media type can start switched off.</li>
        <li><strong>"FreeITSM answered 403".</strong> The token in Zabbix does not match. Copy it again from the FreeITSM page - after <strong>New token</strong>, the old one stops working at once.</li>
        <li><strong>A problem arrives but its recovery never does.</strong> The action has no recovery operation.</li>
        <li><strong>Updates never reach the ticket.</strong> The action has no update operation - or the update was made by the very user the alerts go to. Zabbix never notifies the user who made an update, so send the alerts to a user of their own.</li>
        <li><strong>The ticket is not linked to the server.</strong> No CMDB item has the host's name (or its short name, without the domain) - or two companies both have one, in which case none is linked rather than the wrong one.</li>
        <li><strong>The status did not change on recovery.</strong> Recent alerts says why - usually a closure rule (a mandatory field, a checklist) that a person has to satisfy.</li>
    </ul>
</div>
