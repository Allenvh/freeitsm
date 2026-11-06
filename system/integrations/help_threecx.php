<?php
/**
 * The 3CX half of system/integrations/help.php.
 *
 * Its own file for the same reason as help_zabbix.php: 3CX shares nothing with a
 * tracker's setup - no connection, no project, nothing FreeITSM calls. 3CX calls
 * in, through the CRM template the 3CX page hands out.
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
        3CX is a phone system. Without this, an analyst answers a call not knowing who it is, looks the
        caller up by hand, and afterwards - if they remember - types a note about the call on the ticket.
    </p>
    <p>Once this is set up:</p>
    <ul>
        <li><strong>You see who is calling.</strong> While the phone rings, 3CX asks FreeITSM who the number belongs to and shows the caller's name and company.</li>
        <li><strong>Their ticket opens.</strong> 3CX opens the caller's open ticket in FreeITSM for the analyst - or their record, if they have none.</li>
        <li><strong>Every call is noted.</strong> When the call ends, an internal note goes on that ticket: in or out, how long, who took it, and the recording and 3CX's summary if your 3CX makes them. The note is written by the analyst who took the call.</li>
        <li><strong>A call can open a ticket.</strong> A call in from someone with no open ticket can open one, assigned to whoever took it.</li>
        <li><strong>Numbers dial out.</strong> Phone numbers on tickets and people get a <strong>Call</strong> button that dials through 3CX.</li>
    </ul>
    <p>Every call reported is listed at the bottom of the 3CX page, with what was done with it.</p>
    <p>
        <strong>3CX calls FreeITSM - never the other way round.</strong> FreeITSM does not need your phone system's
        address or a 3CX password. The address on the 3CX page is FreeITSM's own, for you to give to 3CX.
    </p>
</div>

<!-- 2 ───────────────────────────────────────────── -->
<div class="help-section" id="token">
    <div class="help-section-header">
        <span class="help-section-num">2</span>
        <h3>Make the token</h3>
    </div>
    <p>
        On the FreeITSM 3CX page, click <strong>Make</strong>. The token is the password 3CX uses to talk to
        FreeITSM - without the right one, nothing is answered. Nothing works until a token exists, so this also
        switches the integration on.
    </p>
    <p>
        Check <strong>FreeITSM's address</strong> above it. It starts as your public base URL. 3CX is often hosted
        in the cloud, so it has to be an address the internet can reach - not <code>localhost</code> or a name
        only your office network knows.
    </p>
</div>

<!-- 3 ───────────────────────────────────────────── -->
<div class="help-section" id="upload">
    <div class="help-section-header">
        <span class="help-section-num">3</span>
        <h3>Upload the template to 3CX</h3>
    </div>
    <p>A <strong>CRM template</strong> is how 3CX learns to talk to a system it does not already know. FreeITSM makes yours.</p>
    <ol>
        <li>On the FreeITSM 3CX page, click <strong>Download</strong>. You get <code>freeitsm_3cx_template.xml</code>, with the address and your token already in it.</li>
        <li>In the <strong>3CX Admin Console</strong>, go to <strong>Integrations &rsaquo; CRM</strong>.</li>
        <li>Choose to add your own template, and upload the file. 3CX shows it as <strong>FreeITSM</strong>, with the address and token filled in.</li>
        <li>Leave <strong>Log calls on FreeITSM tickets</strong> ticked, and save.</li>
        <li>Turn on <strong>call journaling</strong> for the calls you want reported, and the contact lookup, if your 3CX asks.</li>
    </ol>
    <p>Keep the file to yourself - it holds the token. After <strong>Replace</strong> on the token, download and upload it again.</p>
    <p class="help-muted">
        The token travels as a header on each request, never in the address - so it is not written into web server
        logs or anyone's browser history.
    </p>
</div>

<!-- 4 ───────────────────────────────────────────── -->
<div class="help-section" id="notes">
    <div class="help-section-header">
        <span class="help-section-num">4</span>
        <h3>Choose where calls go</h3>
    </div>
    <p><strong>Who is calling.</strong> A caller is the active person whose phone or mobile is the number - however either is written. <code>07700 900123</code>, <code>+44 7700 900123</code> and the last nine digits of it all count as the same number. If <strong>two or more</strong> people have it (a switchboard, a shared desk), FreeITSM does not guess: 3CX shows an unknown caller, and the call is only listed.</p>
    <ul>
        <li><strong>Which ticket a call goes on</strong> - the caller's most recently updated open ticket; only when they have exactly one (with several, a person decides); or none, so calls are only listed on the 3CX page. The ticket 3CX opens when the phone rings follows the same choice.</li>
        <li><strong>Open a new ticket</strong> - for a call in, answered or missed, from someone with no open ticket. A call out, to someone with no ticket, never opens one. Tick <strong>Also for numbers nobody in FreeITSM has</strong> to open one for strangers too: it is raised for "Caller" and their number, for an analyst to link to the right person.</li>
        <li><strong>Write notes as</strong> - a note is written by the analyst whose email is the same as the 3CX user who took the call. A receptionist or shared phone that is not an analyst can be covered by choosing an analyst here: their notes say who took the call. With <em>Nobody</em>, those calls are only listed.</li>
        <li><strong>Company</strong> (with more than one company) - where a ticket for an unknown number goes. A known caller's ticket goes in their own company.</li>
        <li><strong>Team</strong> and <strong>Origin</strong> - for the new tickets. Origin starts as <em>Phone</em>.</li>
    </ul>
</div>

<!-- 5 ───────────────────────────────────────────── -->
<div class="help-section" id="dial">
    <div class="help-section-header">
        <span class="help-section-num">5</span>
        <h3>Click-to-call</h3>
    </div>
    <p>
        Put a <strong>Dial address</strong> on the 3CX page and every phone number on a ticket, on
        <strong>Tickets &rsaquo; Users</strong> and on <strong>People</strong> gets a <strong>Call</strong> button.
        <code>{number}</code> is where the number goes:
    </p>
    <ul>
        <li><strong>3CX web client</strong>: <code>https://your-pbx.3cx.com/webclient/#/call?phone={number}</code> - use your own 3CX address.</li>
        <li><strong>3CX desktop app, or any softphone</strong>: <code>tel:{number}</code>.</li>
    </ul>
    <p>
        The call is placed by the analyst's own 3CX, on their own computer, where they are already signed in - so
        it comes from their extension, and FreeITSM never needs to know it. When it ends, 3CX reports it like any
        other call, and it is noted on the ticket.
    </p>
    <p>Leave the dial address empty and there are no Call buttons.</p>
</div>

<!-- 6 ───────────────────────────────────────────── -->
<div class="help-section" id="try">
    <div class="help-section-header">
        <span class="help-section-num">6</span>
        <h3>Try it</h3>
    </div>
    <ul>
        <li><strong>Try a call</strong> on the 3CX page. Type a number, choose the kind of call and who took it, and FreeITSM says what would happen: who the caller is, which ticket it would go on, and who the note would be written by. <strong>Nothing is written</strong> - try a real requester's number without putting anything on their ticket.</li>
        <li><strong>A real call.</strong> Ring the desk from a phone whose number is on a person in FreeITSM. Their name shows in 3CX while it rings; when you hang up, the call appears under <strong>Recent calls</strong> within a few seconds. Only this proves 3CX can reach FreeITSM.</li>
    </ul>
</div>

<!-- 7 ───────────────────────────────────────────── -->
<div class="help-section" id="trouble">
    <div class="help-section-header">
        <span class="help-section-num">7</span>
        <h3>If something is wrong</h3>
    </div>
    <ul>
        <li><strong>3CX shows no name.</strong> Try the number with <strong>Try a call</strong>. Nobody may have it, or two people may - and then no name is shown rather than the wrong one. Short internal numbers (extensions) never match anyone.</li>
        <li><strong>Calls never appear under Recent calls.</strong> 3CX cannot reach FreeITSM, or the token is wrong. Check FreeITSM's address is one 3CX can reach, and that call journaling is on in 3CX. After <strong>Replace</strong>, the old token stops working at once - upload the template again.</li>
        <li><strong>A call is listed but not noted.</strong> <em>What happened</em> says why: nobody has the number, several do, the caller has several open tickets and the rule is "only one", or the 3CX user is not an analyst and no one is chosen under <strong>Write notes as</strong>.</li>
        <li><strong>The note is written by the wrong analyst.</strong> The note goes to the analyst whose email is the 3CX user's email. Make them the same, in 3CX or on the analyst.</li>
        <li><strong>3CX refuses the file.</strong> Upload the file exactly as downloaded - opened and saved in another program, it can be changed. 3CX checks the template's name and format on upload.</li>
        <li><strong>No Call buttons.</strong> The dial address is empty, or has no <code>{number}</code> in it.</li>
    </ul>
</div>
