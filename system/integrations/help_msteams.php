<?php
/**
 * The Microsoft Teams (channel alerts) half of system/integrations/help.php.
 *
 * Its own file like help_zabbix.php and help_threecx.php: nothing here is a
 * tracker. FreeITSM posts to an address Teams gives you; nothing comes back.
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
        Service desk alerts appear in Microsoft Teams channels as cards, each with an <strong>Open in FreeITSM</strong>
        button that goes straight to the ticket. For each channel you tick what it should hear about:
    </p>
    <ul>
        <li><strong>Urgent tickets</strong> - a new ticket at or above a priority you choose, or one raised to it.</li>
        <li><strong>Tickets for its teams</strong> - say your L2 channel: every ticket that arrives with the L2 team, or is handed to it.</li>
        <li><strong>SLA warnings and breaches</strong>, <strong>status page incidents</strong>, <strong>catalogue requests waiting for approval</strong>, or simply every new ticket.</li>
    </ul>
    <p>
        <strong>This is not the Teams chat bot.</strong> For people to chat to the service desk in Teams, with each
        conversation becoming a ticket, set up the bot under <strong>Tickets &rsaquo; Settings &rsaquo; Messaging</strong>.
        The two work side by side.
    </p>
</div>

<!-- 2 ───────────────────────────────────────────── -->
<div class="help-section" id="address">
    <div class="help-section-header">
        <span class="help-section-num">2</span>
        <h3>Get the channel's address</h3>
    </div>
    <p>Teams gives each channel an address that anything allowed to post to it can send a message to. To make one:</p>
    <ol>
        <li>In Teams, go to the channel, and click <strong>&hellip;</strong> next to its name.</li>
        <li>Choose <strong>Workflows</strong>, then <strong>Post to a channel when a webhook request is received</strong>.</li>
        <li>Give it a name (for example <em>FreeITSM</em>), check the team and channel, and click <strong>Add workflow</strong>.</li>
        <li>Teams shows the address - a long one starting <code>https://</code>. Copy it.</li>
    </ol>
    <p>
        Treat it like a password: anyone who has it can post in that channel. FreeITSM keeps it encrypted and never
        shows it again.
    </p>
    <p class="help-muted">
        The older <em>Incoming Webhook</em> connector is gone: Microsoft switched Office 365 connectors off in May 2026.
        A Workflows address is the one that works now.
    </p>
</div>

<!-- 3 ───────────────────────────────────────────── -->
<div class="help-section" id="channel">
    <div class="help-section-header">
        <span class="help-section-num">3</span>
        <h3>Add the channel here</h3>
    </div>
    <ol>
        <li>On the FreeITSM Microsoft Teams page, click <strong>Add</strong>.</li>
        <li>Give the channel a name you will recognise, and paste the address.</li>
        <li>Tick its alerts (next step) and click <strong>Save</strong>.</li>
        <li>Click <strong>Test</strong>. A card saying <em>FreeITSM is connected to this channel</em> should appear in Teams within a few seconds.</li>
    </ol>
    <p>Add as many channels as you like - one for major incidents, one per support line, one for approvals.</p>
</div>

<!-- 4 ───────────────────────────────────────────── -->
<div class="help-section" id="alerts">
    <div class="help-section-header">
        <span class="help-section-num">4</span>
        <h3>Choose the alerts</h3>
    </div>
    <ul>
        <li><strong>Urgent tickets</strong> - choose a priority, then tick <em>new tickets at or above it</em>, <em>tickets raised to it</em>, or both. "At or above" follows the order of your priorities in Tickets &rsaquo; Settings, so choosing <em>High</em> includes anything more urgent than High. A ticket moved between two urgent priorities is not announced twice.</li>
        <li><strong>Tickets for these teams</strong> - tick the FreeITSM teams this channel looks after, then whether it hears about <em>new tickets that arrive with one of them</em> (from a routing rule or the New ticket form), <em>tickets handed to one of them later</em>, or both. This is how an L1 / L2 / L3 set-up gets one channel per line.</li>
        <li><strong>Also</strong> - SLA warnings, SLA breaches, status page incidents, catalogue requests waiting for approval, and every new ticket.</li>
    </ul>
    <p>
        A confidential ticket's card shows its number, never its subject or requester - the same rule every webhook follows.
        On a multi-company install, a channel hears about tickets from every company.
    </p>
</div>

<!-- 5 ───────────────────────────────────────────── -->
<div class="help-section" id="workflows">
    <div class="help-section-header">
        <span class="help-section-num">5</span>
        <h3>Where the alerts live</h3>
    </div>
    <p>
        Every alert you tick is an ordinary <strong>workflow</strong>, named <em>Teams: &hellip; &rarr; your channel</em>.
        Open <strong>Workflows</strong> to see when each one last ran, and <strong>Workflows &rsaquo; Webhook deliveries</strong> to see
        every card sent and Teams' answer. A card that could not be delivered is tried again automatically.
    </p>
    <p>
        Make changes on the Teams page, not in Workflows: saving the channel puts its workflows back the way the page
        says. Untick an alert, or delete the channel, and its workflow is removed - its history stays.
    </p>
    <p>
        Want a card for something this page does not offer? Build a workflow of your own with the
        <strong>Send webhook</strong> action and the <strong>Microsoft Teams (Workflows card)</strong> format, pasting
        the same address. <code>{{link}}</code> is the ticket's address, ready for the card's button.
    </p>
</div>

<!-- 6 ───────────────────────────────────────────── -->
<div class="help-section" id="trouble">
    <div class="help-section-header">
        <span class="help-section-num">6</span>
        <h3>If something is wrong</h3>
    </div>
    <ul>
        <li><strong>Test says Teams did not accept it.</strong> The address is wrong or incomplete - copy it again from the channel's workflow in Teams and paste it in.</li>
        <li><strong>The test arrives, but alerts do not.</strong> Check the workflow on the Workflows page: did it run? If it ran, <strong>Workflows &rsaquo; Webhook deliveries</strong> shows what Teams answered. If it did not run, nothing matching has happened yet - for example no ticket at that priority.</li>
        <li><strong>Team alerts never come.</strong> At least one team must be ticked for the channel, and the ticket really has to arrive with (or be handed to) one of them.</li>
        <li><strong>Somebody turned the workflow off in Teams.</strong> The address stops working. Make a new one and paste it into the channel here.</li>
        <li><strong>An edit made in Workflows disappeared.</strong> Saving the channel on this page rebuilds its workflows. Make the change here instead - or copy the workflow and change the copy.</li>
    </ul>
</div>
