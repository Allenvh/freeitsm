<?php
/** Forms help — saving a form as a draft. */
$helpTopic = 'drafts';
require __DIR__ . '/_top.php';
?>

<?php helpSection('saving', 'Saving a draft',
    'Every form has a <strong>Save draft</strong> button beside Submit.'); ?>
    <p>It keeps whatever you have typed so far and leaves the form unsent. Use it when you are halfway through and something is missing &mdash; the cost code is with finance, the serial number is on a machine two floors up.</p>
    <div class="help-note">
        <strong>Nothing is checked when you save a draft.</strong> Required questions can be empty, a number box can say "tbc", and a table can have one half-filled row. That is the entire point: the checks happen when you Submit, which is a different act.
    </div>
    <p>Saving again replaces the previous draft rather than making a second one. There is one draft per form, per person.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('coming', 'Coming back to it',
    'Open the form again and your draft is already in it.'); ?>
    <p>A message tells you when you saved it and reminds you that nothing has been submitted. Carry on, and press Submit when you are ready.</p>
    <p>Once you do submit, the draft is thrown away &mdash; so if you fill the same form in again next week you start from a clean one rather than last week's answers.</p>
    <div class="help-note">
        <strong>Answers to hidden questions are kept too.</strong> If you tick a box that reveals extra questions, fill them in, then untick it, that typing is still in your draft tomorrow. Submitting does the opposite and records only what you were actually asked &mdash; which is right for a record and wrong for a draft.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('changed', 'When the form has changed',
    'If somebody makes a new version of the form after you saved a draft, that draft cannot be filled back in.'); ?>
    <p>You will be told so, and the form opens empty. It is not lost work being hidden from you &mdash; it genuinely cannot be put back safely.</p>
    <div class="help-note">
        <strong>Why.</strong> A new version is a fresh set of questions, even where the wording is identical. Putting old answers into new questions would mean guessing which is which, and a wrong guess would be invisible: the form would look filled in, with answers under the wrong headings.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('who', 'Who can see a draft',
    'Only the person who saved it.'); ?>
    <p>A draft is not a submission. It does not appear in the submissions list, in a collection, in an export, in a PDF, in the approvals queue or over the API, and it never raises a ticket or triggers an action. As far as the rest of FreeITSM is concerned it does not exist until it is submitted.</p>
    <p>Customers in the self-service portal get the same button on a catalogue form, and the same rules.</p>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
