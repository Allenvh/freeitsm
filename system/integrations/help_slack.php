<?php
/**
 * The Slack half of system/integrations/help.php.
 *
 * Kept in its own file because Slack's setup shares nothing with a tracker's —
 * there is no base URL, no API token and no project. Inlining a second set of
 * sections would have made help.php a page of two unrelated halves separated by
 * an if.
 *
 * ⚠️ Written for somebody who has never used Slack. That is not a hypothetical:
 * the first person to set this up had never opened it, and the original steps
 * began at "go to api.slack.com/apps", which assumes an account, a workspace and
 * knowing what an app is. Step 2 exists because of that.
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
        People ask for help where they already are. In a lot of organisations that is Slack —
        someone types &ldquo;my laptop won&rsquo;t charge&rdquo; into a channel, somebody answers if they
        happen to see it, and there is no record that it ever happened.
    </p>
    <p>Once this is set up:</p>
    <ul>
        <li>A message in the Slack channel you choose <strong>becomes a ticket</strong>, with the sender as the requester.</li>
        <li>Your reply from the FreeITSM inbox <strong>appears in that Slack thread</strong>, so the person never leaves Slack.</li>
        <li>Anything else they say in the thread <strong>lands on the same ticket</strong>.</li>
        <li>Screenshots they share come across as attachments.</li>
    </ul>
    <div class="help-note">
        <strong>FreeITSM never sees your Slack traffic.</strong> You create the Slack app in your own
        workspace and it talks straight to this server. There is no FreeITSM-hosted service in the
        middle, and nothing about your messages passes through anyone else.
    </div>
    <p class="help-muted">
        About fifteen minutes, most of it in Slack. You need to be an administrator of the Slack
        workspace, or someone who can approve an app for it.
    </p>
</div>

<!-- 2 ───────────────────────────────────────────── -->
<div class="help-section" id="workspace">
    <div class="help-section-header">
        <span class="help-section-num">2</span>
        <h3>If you have never used Slack</h3>
    </div>
    <p>
        Skip this if your organisation already uses Slack — you want the workspace people are
        already in, not a new one.
    </p>
    <p>Otherwise, a workspace is free and takes a couple of minutes:</p>
    <ol>
        <li>Go to <strong>slack.com</strong> and choose <strong>Create a new workspace</strong>.</li>
        <li>Enter your email address and the code Slack sends you.</li>
        <li>Give the workspace a name — your company name is the usual choice.</li>
        <li>When it asks what you are working on, type anything; it just becomes your first channel.</li>
        <li>Skip inviting people for now. You can test this on your own.</li>
    </ol>
    <p>
        Three words worth knowing before the next step, because Slack&rsquo;s own screens use them:
    </p>
    <div class="help-defs">
        <div class="help-def">
            <div class="help-def-term">Workspace</div>
            <div class="help-def-desc">Your whole Slack — the company. You can belong to several.</div>
        </div>
        <div class="help-def">
            <div class="help-def-term">Channel</div>
            <div class="help-def-desc">A room inside it, like <em>#it-help</em>. This is what FreeITSM watches.</div>
        </div>
        <div class="help-def">
            <div class="help-def-term">App</div>
            <div class="help-def-desc">
                Something you add to a workspace so it can do things. You are about to create one
                that belongs to you — it is not something you install from a shop.
            </div>
        </div>
    </div>
</div>

<!-- 3 ───────────────────────────────────────────── -->
<div class="help-section" id="add">
    <div class="help-section-header">
        <span class="help-section-num">3</span>
        <h3>Add the workspace here first</h3>
    </div>
    <p>
        This step comes before anything in Slack, and the order matters. The app you are about to
        create needs the web address Slack should send messages to, and that address contains the
        row&rsquo;s own id — so the row has to exist before the address does.
    </p>
    <ol>
        <li>On the Slack settings page, click <strong>Add</strong>.</li>
        <li>Give it a name. It is only used in that list and as the name of the Slack app.</li>
        <li>Leave the two secret fields blank — you do not have them yet.</li>
        <li>Save.</li>
    </ol>
    <p>
        The row appears as <strong>Needs setup</strong>, which is honest: it is switched on but has no
        credentials, so nothing will arrive yet.
    </p>
</div>

<!-- 4 ───────────────────────────────────────────── -->
<div class="help-section" id="app">
    <div class="help-section-header">
        <span class="help-section-num">4</span>
        <h3>Create the Slack app</h3>
    </div>
    <p>
        Click the <strong>+</strong> button on the row. That opens everything Slack is about to ask
        you for, including a <em>manifest</em> — a block of settings that fills in the app&rsquo;s name,
        its permissions and its address in one paste, instead of you ticking twenty boxes.
    </p>
    <ol>
        <li>Go to <strong>api.slack.com/apps</strong> and sign in.</li>
        <li>Choose <strong>Create New App</strong>, then <strong>From a manifest</strong>.</li>
        <li>Pick your workspace.</li>
        <li>Paste the manifest from the FreeITSM screen and confirm.</li>
    </ol>
    <div class="help-note warn">
        <strong>Slack checks the address immediately.</strong> The moment you create the app it sends a
        test request to this server and refuses to save if nothing answers. So this install has to be
        reachable from the internet on <code>https://</code> — not <code>localhost</code>, not an
        address that only works inside your network. The setup screen says so before you start if it
        can tell your address will not work.
    </div>
</div>

<!-- 5 ───────────────────────────────────────────── -->
<div class="help-section" id="secrets">
    <div class="help-section-header">
        <span class="help-section-num">5</span>
        <h3>Copy the two secrets back</h3>
    </div>
    <p>Once the app exists, Slack has two values FreeITSM needs. They are on different screens.</p>
    <div class="help-defs">
        <div class="help-def">
            <div class="help-def-term">Bot user OAuth token</div>
            <div class="help-def-desc">
                Under <strong>OAuth &amp; Permissions</strong>. Click <strong>Install to Workspace</strong>
                first — the token only exists afterwards. It starts <code>xoxb-</code>. This is what lets
                FreeITSM post your replies.
            </div>
        </div>
        <div class="help-def">
            <div class="help-def-term">Signing secret</div>
            <div class="help-def-desc">
                Under <strong>Basic Information</strong>, in <em>App Credentials</em>. This is how
                FreeITSM proves a message really came from Slack. Until it is set, everything arriving
                is refused — which is deliberate.
            </div>
        </div>
    </div>
    <div class="help-note warn">
        <strong>If Slack shows a yellow &ldquo;Reinstall to Workspace&rdquo; banner, click it.</strong>
        Slack grants an app its permissions at the moment you install it, so if the permissions were
        set after that — which is what happens when the app is created from a manifest — the token you
        already hold is missing most of them. Nothing looks broken: tickets still arrive, they just
        come in as <em>Slack user @U0A1B2C3</em> instead of the person&rsquo;s name, because reading
        profiles was one of the permissions that never made it.
        <br><br>
        Reinstalling issues a <strong>new</strong> bot token, so copy it again afterwards. This caught
        the first person to set it up, which is why it has a box to itself.
    </div>
    <p>
        Back in FreeITSM, edit the row, paste both, and save. Then press the tick button to test it.
        A good result names your workspace.
    </p>
    <div class="help-note">
        <strong>Neither value is ever shown again.</strong> FreeITSM encrypts both and never sends them
        back to the browser, so the fields read <em>Unchanged</em> when you edit later — leave them blank
        to keep what is stored. Slack will not show you the token twice either, so if you lose it you
        reinstall the app to get a new one.
    </div>
</div>

<!-- 6 ───────────────────────────────────────────── -->
<div class="help-section" id="invite">
    <div class="help-section-header">
        <span class="help-section-num">6</span>
        <h3>Invite it to a channel</h3>
    </div>
    <p>
        An app cannot read a channel it is not in. This catches almost everybody, because nothing
        looks broken — messages simply never arrive.
    </p>
    <ol>
        <li>In Slack, open the channel you want the service desk to watch.</li>
        <li>Type <strong>/invite</strong> followed by the app&rsquo;s name and press enter.</li>
    </ol>
    <p>
        Then decide how much it should listen to. Leave <strong>Only watch this channel</strong> blank and
        every channel it is invited to raises tickets; put a channel id in it and the rest are ignored.
    </p>
    <div class="help-note warn">
        <strong>Do not point this at a busy general channel.</strong> Every message becomes a ticket, so
        a chatty channel produces a ticket per message. A dedicated channel — <em>#it-help</em> — is what
        you want. To find its id, right-click the channel in Slack, copy the link, and take the part
        starting with <code>C</code> at the end.
    </div>
</div>

<!-- 7 ───────────────────────────────────────────── -->
<div class="help-section" id="try">
    <div class="help-section-header">
        <span class="help-section-num">7</span>
        <h3>Try it</h3>
    </div>
    <ol>
        <li>Post a message in that Slack channel.</li>
        <li>It should appear in the FreeITSM inbox within a second or two, as a ticket from you.</li>
        <li>Reply to it from the inbox. Your reply appears in Slack, threaded under the original message.</li>
        <li>Reply again in the Slack thread. It lands on the same ticket.</li>
    </ol>
    <p>
        <strong>A thread is a ticket.</strong> If the same person starts a separate message in the
        channel, that is a separate ticket — which is what you want, because two unrelated questions
        should not share one ticket.
    </p>
</div>

<!-- 8 ───────────────────────────────────────────── -->
<div class="help-section" id="trouble">
    <div class="help-section-header">
        <span class="help-section-num">8</span>
        <h3>If something is wrong</h3>
    </div>
    <div class="help-table">
        <table>
            <tr><th>What you see</th><th>What it usually means</th></tr>
            <tr>
                <td>Slack refuses to create the app, complaining about the request URL</td>
                <td>Slack cannot reach this server. Check the address is <code>https://</code> and reachable from outside your network — the setup screen warns about this before you start.</td>
            </tr>
            <tr>
                <td>Messages in Slack never become tickets</td>
                <td>Nine times out of ten the app has not been invited to the channel. Try <code>/invite</code> again. Otherwise check the signing secret is saved — without it everything is refused.</td>
            </tr>
            <tr>
                <td>The test says the token was rejected</td>
                <td>The token was copied before installing the app, or from the wrong field. It must start <code>xoxb-</code>, from <em>OAuth &amp; Permissions</em>.</td>
            </tr>
            <tr>
                <td>Replies fail with &ldquo;not in channel&rdquo;</td>
                <td>The app was removed from the channel after setup. Invite it again.</td>
            </tr>
            <tr>
                <td>Tickets arrive as &ldquo;Slack user @U0A1B2C3&rdquo; instead of a name</td>
                <td>The app was installed before it had permission to read profiles. Go to <strong>OAuth &amp; Permissions</strong> and click <strong>Reinstall to Workspace</strong> — then copy the new bot token into FreeITSM, because reinstalling replaces it. Nothing is lost in the meantime: the ticket still records exactly who sent it.</td>
            </tr>
            <tr>
                <td>One person&rsquo;s messages all pile onto one ticket</td>
                <td>They are replying in the same thread. Starting a new message in the channel starts a new ticket.</td>
            </tr>
        </table>
    </div>
</div>
