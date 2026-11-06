<?php
/** Knowledge help — documents and the self-service Help Centre (GH #177). */
$helpTopic = 'documents-help-centre';
require __DIR__ . '/_top.php';
?>

<?php helpSection('rule', 'The rule, in one sentence',
    'A document attached to a knowledge article can be downloaded from the self-service portal\'s <strong>Help Centre</strong> by anyone who can read that article there &mdash; unless you have set that document to <strong>Hidden</strong>.'); ?>
    <p>So the question "can this customer download this file?" always comes down to two others:</p>
    <ol>
        <li><strong>Can they read the article in the Help Centre?</strong> That is decided by the article itself, exactly as before: it must be published, not in the recycle bin, set to an audience that includes signed-in customers, and belong to their company (or be shared with every company).</li>
        <li><strong>Is the document shown?</strong> Each document on an article is either <em>Shown in Help Centre</em> or <em>Hidden from Help Centre</em>. You can see which on the article's Documents section.</li>
    </ol>
    <p>Both have to be yes. A document shown on an article that only analysts can read reaches nobody outside your team.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('where', 'Only knowledge articles',
    'The same Documents section appears on lots of screens. This only changes what happens on knowledge articles.'); ?>
    <table>
        <thead><tr><th>Documents attached to&hellip;</th><th>Can customers download them in the portal?</th></tr></thead>
        <tbody>
            <tr><td><strong>A knowledge article</strong></td><td>Yes, if they can read the article in the Help Centre and the document is not hidden</td></tr>
            <tr><td><strong>A shared ticket note</strong> (one the customer can see)</td><td>Yes &mdash; the person who raised the ticket, as before</td></tr>
            <tr><td><strong>An internal ticket note</strong></td><td>No, never</td></tr>
            <tr><td>An asset, contract, supplier, task, process or anything else</td><td>No, never</td></tr>
        </tbody>
    </table>
    <div class="help-note">
        <strong>Email attachments are separate again.</strong> Files that arrive on a customer's email, or that you send in a reply, are not documents in this sense, and nothing here changes how they work.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('hide', 'Keeping one document back',
    'Attach two guides and an internal checklist to the same article, and you can let customers have the guides while the checklist stays with your team.'); ?>
    <ol>
        <li>Open the article and click <strong>Edit</strong>.</li>
        <li>In the <strong>Documents</strong> section, each document has a <strong>Hide</strong> or <strong>Show</strong> button beside it.</li>
        <li>Click <strong>Hide</strong> on the one to keep back. Its label changes to <em>Hidden from Help Centre</em> straight away &mdash; there is nothing else to save.</li>
    </ol>
    <p>A hidden document is still on the article for your team: analysts can open and download it exactly as before. It simply does not appear in the Help Centre, and the portal refuses it if anybody asks for it directly.</p>
    <p>Each change is recorded in the article's <strong>History</strong>, with who made it and when.</p>
    <div class="help-note">
        <strong>New documents start Shown.</strong> Attaching a document to an article is normally done so that the people reading the article can have it. If it is only for your team, hide it as you attach it.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('pins', 'A document on more than one record',
    'FreeITSM stores a document once, and lets you attach that one copy to several things &mdash; the same warranty PDF on eleven laptops, say.'); ?>
    <p>Think of the document as a file with several pins in it, one for each record it is attached to. <strong>Shown and Hidden belong to the pin, not to the file.</strong> So if the same PDF is attached to two articles, you can show it on one and hide it on the other, and hiding it on an article never changes anything about the contract or asset it is also attached to.</p>
    <p>The <em>Also on</em> labels under a document tell you where else it is attached.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('before', 'Documents attached before 3.7.0',
    'Every document that was already on an article when you updated starts out Hidden.'); ?>
    <p>Until 3.7.0 the Help Centre never showed documents, so nobody attaching one had any reason to think customers might see it. Publishing them all on the day of an update would have been a decision nobody made. So nothing changed when you updated: an older document reaches customers only once somebody clicks <strong>Show</strong> on it.</p>
    <p>If you have articles for customers with documents you <em>do</em> want them to have, open each one, click <strong>Edit</strong>, and click <strong>Show</strong> on those documents.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('audience', 'Changing who can read an article',
    'When you open an article up to customers, its shown documents go with it.'); ?>
    <p>If you change an article from <em>Analysts only</em> to an audience that includes customers, and it has documents marked Shown, the editor says so under the audience before you save &mdash; that is the moment to hide anything that should stay internal.</p>
    <p>It works the other way too. Unpublish an article, move it to the recycle bin, or set it back to <em>Analysts only</em>, and its documents leave the Help Centre with it, without you having to hide each one.</p>
    <div class="help-note">
        <strong>A download cannot be taken back.</strong> Hiding a document stops anybody downloading it from then on, but a copy somebody has already downloaded is theirs. Check before you show, rather than after.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('safe', 'What stops the wrong people downloading',
    'The checks are made by the server, on every download, not by what the page happens to show.'); ?>
    <ul>
        <li><strong>Every download is checked again.</strong> A customer who copies a download link and sends it to somebody else gets nothing for them, unless that person could read the article too.</li>
        <li><strong>Guessing does not work.</strong> Asking for a document by number answers "not found" whether it does not exist, is hidden, is on another company's article, or is attached to something that never reaches the portal &mdash; so the answer gives nothing away.</li>
        <li><strong>Companies stay apart.</strong> An article that belongs to one company is only readable by that company's customers, and so are its documents. An article shared with every company shares its shown documents with every company.</li>
        <li><strong>Folder permissions still apply.</strong> If an article sits in a restricted folder, the same restrictions decide who can download its documents.</li>
    </ul>
    <div class="help-note">
        <strong>Links to another system are only links.</strong> A document that is a link to your own document system (SharePoint, a file share) opens that link for the customer. Whether they can then read it is up to that system, not FreeITSM &mdash; if it needs your company login, they will be asked for one.
    </div>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
