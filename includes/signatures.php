<?php
/**
 * Analyst email signatures (discussion #80, request 3).
 *
 * WHY THE MERGE CODES ARE NOT THE ONES YOU ALREADY KNOW
 * ----------------------------------------------------
 * ⚠️ `[analyst_name]` is NOT the person typing. buildTicketMergeData() resolves it
 * from the ticket:
 *
 *      COALESCE(o.full_name, a.full_name)     -- o = owner, a = assigned analyst
 *
 * which is the right answer for an automatic email about a ticket, and the wrong
 * answer for a signature. Sam answering a ticket owned by Jo would sign it *Jo* —
 * every time, to the customer, without either of them noticing.
 *
 * So signatures get their own vocabulary, tied to whoever is signed in: `[my_name]`,
 * `[my_email]`, `[my_job_title]` and so on. The `my_` prefix is not decoration; it is
 * the whole distinction, and it reads correctly next to the My Account screen these
 * are edited on. The ticket codes keep meaning exactly what they always meant.
 *
 * ESCAPING
 * --------
 * Same rule as canned responses: the BODY is trusted (the analyst wrote it, and it is
 * HTML on purpose so a signature can have a link or bold text), the VALUES substituted
 * into it are not. An analyst's own job title is hardly hostile, but it is typed into
 * a box and ends up in somebody else's inbox, so it is escaped like anything else.
 */

/**
 * The values a signature can merge, for the analyst who is signing.
 *
 * Empty strings rather than nulls: a missing phone number should leave a blank in the
 * signature, not the literal word "null" in an email to a customer.
 */
function signatureMergeData(PDO $conn, int $analystId): array
{
    $stmt = $conn->prepare("SELECT full_name, email, username, job_title, department, phone, mobile
                              FROM analysts WHERE id = ?");
    $stmt->execute([$analystId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        return [];
    }

    return [
        'my_name'       => (string)($row['full_name']  ?? ''),
        'my_email'      => (string)($row['email']      ?? ''),
        'my_username'   => (string)($row['username']   ?? ''),
        'my_job_title'  => (string)($row['job_title']  ?? ''),
        'my_department' => (string)($row['department'] ?? ''),
        'my_phone'      => (string)($row['phone']      ?? ''),
        'my_mobile'     => (string)($row['mobile']     ?? ''),
    ];
}

/** The merge codes offered in the editor, in the order they are listed. */
function signatureMergeCodes(): array
{
    return [
        'my_name'       => 'Your name',
        'my_job_title'  => 'Your job title',
        'my_department' => 'Your department',
        'my_email'      => 'Your email address',
        'my_phone'      => 'Your phone number',
        'my_mobile'     => 'Your mobile number',
    ];
}

/**
 * Resolve a signature body for one analyst.
 *
 * A code with no value behind it is removed rather than left showing. That is the
 * opposite of what renderReplyTemplate() does, and deliberately so: an unresolved
 * `[requester_first_name]` sits in an editor where an analyst reads it before
 * sending, whereas an analyst who has not filled in a mobile number would otherwise
 * have `[my_mobile]` appear at the bottom of every email they ever send.
 *
 * @param string $body  the signature's stored HTML, with [my_*] merge codes
 */
function renderSignature(PDO $conn, string $body, int $analystId): string
{
    $merge = signatureMergeData($conn, $analystId);
    foreach ($merge as $code => $value) {
        $safe = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
        $body = str_replace("[$code]", $safe, $body);
    }
    // Anything left is a code this analyst has no value for.
    return preg_replace('/\[my_[a-z_]+\]/', '', $body);
}

/** Every signature belonging to one analyst, default first. */
function signaturesForAnalyst(PDO $conn, int $analystId): array
{
    try {
        $stmt = $conn->prepare("SELECT id, name, body, is_default, display_order
                                  FROM analyst_signatures
                                 WHERE analyst_id = ?
                              ORDER BY is_default DESC, display_order ASC, id ASC");
        $stmt->execute([$analystId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception) {
        // A part-upgraded install has no signatures, which means replies are composed
        // exactly as they were before this feature — never an error in the reply box.
        return [];
    }
}

/**
 * The one that gets inserted without being asked for, or null.
 *
 * ⚠️ Falls back to the FIRST signature when none is flagged. Otherwise an analyst who
 * deletes the signature that happened to be the default silently gets none inserted
 * from then on, and would have to work out why on their own.
 */
function defaultSignatureForAnalyst(PDO $conn, int $analystId): ?array
{
    $all = signaturesForAnalyst($conn, $analystId);
    if (!$all) {
        return null;
    }
    foreach ($all as $sig) {
        if ((int)$sig['is_default'] === 1) {
            return $sig;
        }
    }
    return $all[0];
}

/**
 * Make exactly one signature the default for an analyst.
 *
 * Two statements, not one: clearing every flag and then setting one is what keeps
 * "exactly one" true even if a previous write left two set. The analyst_id clause on
 * both is what stops this touching anybody else's signatures.
 *
 * @param int $signatureId  the signature (analyst_signatures.id) to make the default; one of another analyst's matches nothing
 */
function setDefaultSignature(PDO $conn, int $analystId, int $signatureId): void
{
    $conn->prepare("UPDATE analyst_signatures SET is_default = 0 WHERE analyst_id = ?")
         ->execute([$analystId]);
    $conn->prepare("UPDATE analyst_signatures SET is_default = 1 WHERE id = ? AND analyst_id = ?")
         ->execute([$signatureId, $analystId]);
}
