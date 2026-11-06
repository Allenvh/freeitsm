<?php
/** Forms help — questions answered with a table. */
$helpTopic = 'tables';
require __DIR__ . '/_top.php';
?>

<?php helpSection('what', 'What a table question is',
    'One question whose answer is a list of rows, with the person filling the form in adding as many as they need.'); ?>
    <p>Some questions do not have one answer. "What are you ordering?" has three answers on Tuesday and eleven on Friday, and each of them has a description, a unit and a quantity. A table question asks all of that once:</p>
    <div class="help-table">
        <table>
            <thead><tr><th>Item</th><th>Type</th><th>Quantity</th></tr></thead>
            <tbody>
                <tr><td>Keyboard</td><td>Wireless</td><td>4</td></tr>
                <tr><td>Monitor stand</td><td>Dual</td><td>2</td></tr>
            </tbody>
        </table>
    </div>
    <p>You set the columns up once. The rows are not yours to decide &mdash; whoever fills the form in adds them.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('columns', 'Setting up the columns',
    'Add a <strong>Table</strong> from the Add menu, give it a heading, then add a column for each thing you want per row.'); ?>
    <p>Each column has a heading, a kind, and whether it must be filled in. A column can be one of six kinds:</p>
    <div class="help-table">
        <table>
            <thead><tr><th>Kind</th><th>What the person gets</th></tr></thead>
            <tbody>
                <tr><td>Text</td><td>A box to type in.</td></tr>
                <tr><td>Number</td><td>A number box.</td></tr>
                <tr><td>Dropdown</td><td>A list you provide, separated by commas.</td></tr>
                <tr><td>Choose one</td><td>The same, as buttons, when there are only two or three.</td></tr>
                <tr><td>Tick box</td><td>Yes or no.</td></tr>
                <tr><td>Date</td><td>A date picker.</td></tr>
            </tbody>
        </table>
    </div>
    <div class="help-note">
        <strong>Why only six.</strong> A file upload or a signature pad in a column two inches wide is unusable, and a table where each row can ask different follow-up questions very quickly becomes something nobody can read. A table is for the repetitive, regular part of a form. Anything richer belongs as ordinary questions.
    </div>
    <p>Use the arrows to reorder columns, and there is a limit of twelve.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('filling', 'Filling one in',
    'The table appears with its headings and one empty row, and an <strong>Add row</strong> button underneath.'); ?>
    <p>Type across the boxes, press Add row, type the next one. Each row has a <strong>&times;</strong> to remove it. It works the same on the analyst side and in the self-service portal.</p>
    <div class="help-note">
        <strong>An untouched row is not an answer.</strong> The table starts with a blank row so there is somewhere to type; if nobody types in it, it is not recorded. So a table that has to be filled in is not satisfied by that starter row being there.
    </div>
    <p>On a narrow screen the table scrolls sideways rather than squashing its columns, so every heading stays readable.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('changing', 'Changing a table later',
    'You can rename a column, move it, or retire it &mdash; and answers already given stay correct.'); ?>
    <p>Retiring a column stops it being asked. It does not delete anything: the answers people already gave it are kept, and they still appear on those submissions under the heading they were asked with, marked as removed. That is why a retired column stays visible, greyed out, in the builder.</p>
    <div class="help-note">
        <strong>Renaming and reordering are safe.</strong> Each column keeps a hidden identity of its own, so moving "Quantity" to the front, or renaming it "How many", does not re-label a single answer anybody has already given.
    </div>
    <div class="help-note warn">
        <strong>A retired column's place is never reused.</strong> If you retire a column and add a new one, the new one is genuinely new. This is what stops last year's answers reappearing under this year's heading.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('reading', 'Reading the answers back',
    "A table's answers appear as a table wherever a submission is read in full."); ?>
    <div class="help-table">
        <table>
            <thead><tr><th>Where</th><th>What you see</th></tr></thead>
            <tbody>
                <tr><td>The submissions list</td><td>A count &mdash; "3 rows". That list is one line per submission, and thirty cells squeezed into one column would make every row unreadable.</td></tr>
                <tr><td>Opening a submission</td><td>The full table.</td></tr>
                <tr><td>The PDF</td><td>A real table, not a paragraph.</td></tr>
                <tr><td>The CSV export</td><td>One column per <em>table</em> column, with that column's values listed down the rows.</td></tr>
                <tr><td>A collection</td><td>The full table, same as a single form.</td></tr>
            </tbody>
        </table>
    </div>
    <div class="help-note">
        <strong>Why the CSV is that way round.</strong> A spreadsheet needs the same headings for every row in the file, and one submission might have three rows where the next has eleven. A column per table column always fits; a column per row cannot.
    </div>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
