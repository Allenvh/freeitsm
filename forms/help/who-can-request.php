<?php
/** Forms help — who can request a form. */
$helpTopic = 'who-can-request';
require __DIR__ . '/_top.php';
?>

<?php helpSection('default', 'By default, everyone',
    'A form in the request catalogue can be requested by any customer who can reach the catalogue.'); ?>
    <p>That is how every catalogue form has always worked, and it is still what happens unless you say otherwise. Nothing changed shape when restrictions arrived.</p>
    <div class="help-note">
        <strong>This is separate from whether the form is in the catalogue at all.</strong> The catalogue toggle decides whether customers see it; a restriction decides <em>which</em> customers. A form that is not in the catalogue is not restricted &mdash; it is simply not offered.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('restrict', 'Restricting a form',
    'On the Forms list, each form has a <strong>Who can request this form</strong> button.'); ?>
    <p>Tick <em>Only let certain groups request this form</em>, choose one or more groups, and save. The form shows a <strong>Restricted</strong> label on the list so you can see it at a glance without opening anything.</p>
    <p>Somebody in <em>any one</em> of the groups you choose can request it. Untick the box and save to go back to everyone.</p>
    <div class="help-note">
        <strong>Check the customer count beside each group.</strong> A group containing only analysts has no customers in it, so restricting a catalogue form to it means nobody can request the form &mdash; which looks exactly the same from the outside as a restriction that is working.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('groups', 'Where the groups come from',
    'Groups are managed in <strong>Tickets &rarr; Users &rarr; Groups</strong>.'); ?>
    <p>These are the groups that can hold customers as well as members of staff, which is why they are the ones used here. Add somebody to a group there and they can request the forms restricted to it; remove them and they cannot.</p>
    <div class="help-note">
        <strong>Membership can have an end date.</strong> A group membership that has expired is not a membership, so somebody who was in the group for a project loses access to its forms when that expires, without anybody having to remember.
    </div>
<?php helpSectionEnd(); ?>

<?php helpSection('effect', 'What a restriction actually does',
    'It is a real restriction, not a hidden card.'); ?>
    <p>For a customer outside the groups, the form is not in their catalogue, will not open from a link somebody sends them, cannot be saved as a draft, will not show its images, and cannot be submitted. Each of those is checked separately, so a link shared between colleagues does not become a way in.</p>
    <p>They are told the form does not exist rather than that they are not allowed &mdash; deliberately, because "you cannot see this one" tells somebody which forms are worth asking about.</p>
    <div class="help-note">
        <strong>Analysts are unaffected.</strong> A restriction answers "which customers may request this", not "who may administer it". Anybody with access to the Forms module still sees and edits every form.
    </div>
    <div class="help-note">
        <strong>A new version keeps the restriction.</strong> Saving a form as a new version carries its audience with it, so editing a restricted form never quietly republishes it to everybody.
    </div>
<?php helpSectionEnd(); ?>

<?php require __DIR__ . '/_bottom.php'; ?>
