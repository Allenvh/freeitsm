<?php
/** Forms help — laying a form out. */
$helpTopic = 'layout';
require __DIR__ . '/_top.php';
?>

<?php helpSection('widths', 'Field widths',
    'Every question has a <strong>Width</strong>, set next to it in the builder. It decides how much of a line the question takes up.'); ?>
    <div class="help-table">
        <table>
            <thead><tr><th>Width</th><th>What it means</th></tr></thead>
            <tbody>
                <tr><td>Full width</td><td>The question has the line to itself. This is what every question does unless you change it.</td></tr>
                <tr><td>Three-quarters</td><td>Pairs with a quarter.</td></tr>
                <tr><td>Two-thirds</td><td>Pairs with a third.</td></tr>
                <tr><td>Half</td><td>Pairs with another half.</td></tr>
                <tr><td>Third</td><td>Three of them make a line.</td></tr>
                <tr><td>Quarter</td><td>Four of them make a line.</td></tr>
            </tbody>
        </table>
    </div>
    <div class="help-note">
        <strong>Existing forms are untouched.</strong> A question with no width set takes the full line, which is what every question on every form did before widths existed. Nothing changed shape when this arrived.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('pairs', 'Putting two questions on one line',
    'Give both of them a width that adds up to a line, and they sit side by side.'); ?>
    <p>Set one question to <strong>Two-thirds</strong> and the next to <strong>Third</strong>, and they share a line — the wider box for the longer answer.</p>
    <div class="help-note">
        <strong>The pairs that are not halves are usually the ones you want.</strong> "Department" and "Date" both take half a line if you let them, but a department name is long and a date is eight characters. Two-thirds and a third looks like a form somebody designed; half and half looks like a form somebody gave up on.
    </div>
    <p>Questions fill a line in the order they appear, and anything that does not fit starts a new one. You do not draw rows — you set widths, and the lines follow. Rows are independent, so one line can be two halves and the next three thirds.</p>
<?php helpSectionEnd(); ?>

<?php helpSection('labels', 'Labels above or beside',
    'Each question also has a <strong>Label</strong> setting: <em>Above</em> or <em>Beside</em>.'); ?>
    <p><em>Above</em> is the usual arrangement and the default. <em>Beside</em> puts the question text on the same line as the answer box, which is how a printed form normally reads:</p>
    <div class="help-code">First name [____________]&nbsp;&nbsp;&nbsp;Surname [____________]</div>
    <p>That row is two half-width questions, both set to <em>Beside</em>. There is no table involved and nothing to draw.</p>
    <div class="help-note">
        <strong>A tick box is left alone.</strong> A single yes/no question already has its text beside the box, so the setting is not offered for one.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('phones', 'What happens on a phone',
    'Everything goes back to one question per line, and every label goes back above its box.'); ?>
    <p>This is not a setting and cannot be switched off. Two boxes side by side on a phone screen leaves about an inch for each, and a label long enough to be worth reading wraps to three lines before its box is even narrow.</p>
    <p>So a form can be laid out properly for a desk and still be usable by somebody standing in a server room, without you designing it twice.</p>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
