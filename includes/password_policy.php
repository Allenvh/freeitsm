<?php
/**
 * The one rule for a new password, for analysts and portal users alike.
 *
 * 🔴 There was no shared rule. Eight places set a password, each with its own
 * length check - 8 characters in some, 6 in others, none at all when an
 * administrator created an analyst - and none refused the password FreeITSM
 * itself publishes. So on first sign-in the "you must change your password"
 * screen could be satisfied by typing freeitsm three times, which made the forced
 * change of the default credentials cosmetic. Reported privately, October 2026.
 *
 * Deliberately modest: a length floor and a short list of passwords that are
 * public knowledge for THIS product, plus the handful every guessing list starts
 * with. No composition rules - they push people towards Password1! and do little
 * against guessing.
 */

const PASSWORD_MIN_LENGTH = 8;

/**
 * Passwords that are written down somewhere a stranger can read: the shipped
 * default (README, setup), the demo data's analysts (database/demo-data/core.json),
 * and the commonest guesses. Compared case-insensitively.
 */
const PASSWORD_REFUSED = [
    'freeitsm', 'demo1234',
    'password', 'password1', '12345678', '123456789', '1234567890', 'qwertyui', 'qwerty123',
    'iloveyou', 'admin123', 'administrator', 'changeme', 'letmein1', 'welcome1',
];

/**
 * Why this password may not be used, or null if it may.
 *
 * @param string $new  the password being set
 * @param ?string $currentHash  the account's current password hash, to refuse "the same again"; null when there is none (a new account)
 * @return ?string  a sentence to show the person, or null
 */
function passwordPolicyError(string $new, ?string $currentHash = null): ?string
{
    if (mb_strlen($new) < PASSWORD_MIN_LENGTH) {
        return 'The password must be at least ' . PASSWORD_MIN_LENGTH . ' characters.';
    }
    if (in_array(mb_strtolower($new), PASSWORD_REFUSED, true)) {
        return 'That password is too easy to guess - it is published or very common. Choose another.';
    }
    if ($currentHash !== null && $currentHash !== '' && password_verify($new, $currentHash)) {
        return 'The new password must be different from the current one.';
    }
    return null;
}
