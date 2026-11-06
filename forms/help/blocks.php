<?php
/** Forms help — notes, warnings and images. */
$helpTopic = 'blocks';
require __DIR__ . '/_top.php';
?>

<?php helpSection('notes', 'Notes and warnings',
    'A <strong>Note</strong> is something on the form to be read rather than answered.'); ?>
    <p>Add one from the same menu as a question. It takes a one-line message and, if you need it, a longer paragraph underneath:</p>
    <div class="help-code">All purchases over &pound;500 need director approval before this request is raised.</div>
    <p>It collects nothing, so it never appears on a submission, in an export or in a report. It is there to stop somebody getting the form wrong.</p>
    <div class="help-note">
        <strong>A note is not a section heading.</strong> A heading groups the questions beneath it, and hiding the heading hides the whole group. A note is just text; it does not own anything.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('styles', 'Choosing a style',
    'A note has a <strong>Style</strong>: Plain, Information, Warning, Important or Success.'); ?>
    <div class="help-table">
        <table>
            <thead><tr><th>Style</th><th>Use it for</th></tr></thead>
            <tbody>
                <tr><td>Information</td><td>Guidance. Where to find something, what a field means.</td></tr>
                <tr><td>Warning</td><td>Something that will delay them &mdash; "requests after 3pm are handled the next working day".</td></tr>
                <tr><td>Important</td><td>A consequence that cannot be undone, or a spending threshold.</td></tr>
                <tr><td>Success</td><td>Reassurance, sparingly.</td></tr>
                <tr><td>Plain</td><td>Standing text with no colour at all.</td></tr>
            </tbody>
        </table>
    </div>
    <div class="help-note">
        <strong>You pick a name, not a colour, and that is deliberate.</strong> The colours come from the theme, so a note reads correctly in light mode and in dark mode without you choosing twice &mdash; and a pale amber that looks right on a white screen is close to invisible on a black one. It also means your forms keep looking like the rest of FreeITSM, and a change of theme carries them all with it.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('when', 'Showing a warning only when it matters',
    'A note can be conditional, exactly like a question.'); ?>
    <p>Set a condition on it and the warning appears only when it applies &mdash; when somebody ticks "contains hazardous materials", or picks a quantity over a threshold.</p>
    <div class="help-note">
        <strong>This is worth doing.</strong> A warning that is always on screen stops being read within a week. One that appears at the moment it becomes true gets read every time.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('images', 'Images',
    'An <strong>Image</strong> puts a picture on the form &mdash; a logo at the top, a floor plan beside the question about which room, a diagram of the thing being requested.'); ?>
    <p>Choose the file in the builder. Its <strong>Size</strong> is a share of the column it sits in &mdash; full, three-quarters, half or a quarter &mdash; rather than a number of pixels, so it stays sensible on any screen. A picture smaller than its column is left at its own size rather than stretched.</p>
    <p>Like anything else on a form it can have a width, so it can sit beside a question, and it can be conditional, so a wiring diagram appears only once somebody says the fault is electrical.</p>
    <div class="help-note">
        <strong>What the label is for.</strong> Whatever you type as the image's label becomes its text description, which is what somebody using a screen reader hears. Describe what the picture shows.
    </div>
    <div class="help-note warn">
        <strong>SVG files are not accepted.</strong> An SVG is a document that can contain instructions, not just a picture, and these are shown to customers. PNG, JPG, GIF, WebP, BMP and phone photos (HEIC) are all fine.
    </div>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
