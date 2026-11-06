<?php
/**
 * The Knowledge assistant — turning closed tickets into knowledge, honestly.
 *
 * The naive version of this feature ("button on a ticket, out comes an article")
 * fails on the first ticket you try it on. An analyst who writes *"reset password,
 * asked user to log in again"* has not written an article, and a knowledge base
 * full of that is worse than an empty one: it teaches people the portal is
 * useless and deflection goes backwards.
 *
 * So this file is built around three rules.
 *
 *  1. THE MODEL'S FIRST JOB IS TO REFUSE. Every call judges before it writes. A
 *     thin ticket comes back with "there is no article here, and here is what I
 *     would need to know" — never with a padded-out article.
 *  2. A REFUSAL IS NOT A DEAD END. When the ticket looks worth writing up but
 *     the detail is missing, the model asks two to four specific questions. The
 *     analyst who wrote one line will happily type three sentences into a box.
 *  3. VOLUME IS THE REAL SIGNAL. One ticket cannot tell you whether an article is
 *     worth having; a cluster of fourteen near-identical ones can. The gap finder
 *     (see writeupGapCandidateSql) is the half that decides WHAT to write; this
 *     half only decides whether there is enough material to write it FROM.
 *
 * Shared deliberately: the Tickets module owns the moment (a button on a solved
 * ticket) and the Knowledge module owns the judgement (the assistant, the
 * thresholds, the drafts). Both call in here rather than keeping two copies —
 * the same split kb_ai.php already uses for Knowledge chat vs web chat.
 */

require_once __DIR__ . '/../encryption.php';
require_once __DIR__ . '/../ai_settings.php';
require_once __DIR__ . '/kb_ai.php';

/**
 * Which AI config the assistant should use.
 *
 * Prefers its own namespace so the spend shows up as its own line on the
 * provider's bill, but falls back to the module's existing Knowledge AI key
 * rather than demanding a second one. Somebody who has already set up Knowledge
 * AI has told us how this module talks to a model; making them say it twice
 * before the assistant will do anything is friction for no benefit, and the
 * first thing they would ask is why.
 *
 * The returned 'ns' says which one answered, so the UI can be honest about
 * where the spend is going.
 */
function writeupAiConfig(PDO $conn): array
{
    $cfg = aiSettingsLoad($conn, 'knowledge_writeup');
    if (($cfg['api_key'] ?? '') !== '') {
        return $cfg + ['ns' => 'knowledge_writeup'];
    }
    $fallback = aiSettingsLoad($conn, 'knowledge_ai');
    if (($fallback['api_key'] ?? '') !== '') {
        return $fallback + ['ns' => 'knowledge_ai'];
    }
    return $cfg + ['ns' => null];
}

/**
 * Does this install have the gap tables yet?
 *
 * ⚠️ Load-bearing. An install that pulls this update but has not yet run
 * Database Verify must degrade to "the assistant has nothing to show you", NOT
 * to an "Unknown column" fatal on the Knowledge page. Same gate, same reason, as
 * snoozeSchemaReady() in the tickets inbox — that one would have served an empty
 * inbox, this one would take out the whole Knowledge module.
 *
 * Cached per request: called on every render and on every row of a batch.
 */
function writeupSchemaReady(PDO $conn): bool
{
    static $ready = null;
    if ($ready !== null) {
        return $ready;
    }
    try {
        $st = $conn->query("SHOW TABLES LIKE 'knowledge_gap_clusters'");
        $ready = $st && $st->fetchColumn() !== false;
    } catch (Throwable) {
        $ready = false;
    }
    return $ready;
}

/**
 * Tunables, with defaults that a fresh install can live with.
 *
 * These are settings rather than constants because "how thin is too thin" is a
 * genuine judgement that differs by desk: a small internal IT team wants the bar
 * low (any repeated question is worth an article), an MSP with a public portal
 * wants it high. Nobody has to touch them to get value out of the box.
 */
function writeupSettings(PDO $conn): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }

    $defaults = [
        // How far back the assistant reads. Older than this and the answer has
        // probably changed anyway.
        'knowledge_gap_lookback_days'     => 90,
        // Similarity to the best-matching article, BELOW which a ticket counts as
        // unanswered by the knowledge base. 0.75 on text-embedding-3-small is
        // "clearly about something else" rather than "loosely related".
        'knowledge_gap_article_threshold' => 0.75,
        // Ticket-to-ticket similarity needed to join the same cluster. Higher
        // than the article threshold on purpose: two tickets must be *the same
        // question*, not merely the same topic, or clusters turn into "printers".
        'knowledge_gap_cluster_threshold' => 0.82,
        // How many tickets before the assistant will bother you about it. This is
        // the number that answers "is this worth an article at all".
        'knowledge_gap_min_cluster'       => 3,
        // Richness below which the assistant interviews instead of drafting.
        'knowledge_gap_min_richness'      => 35,
    ];

    $out = $defaults;
    try {
        $in  = implode(',', array_fill(0, count($defaults), '?'));
        $st  = $conn->prepare("SELECT setting_key, setting_value FROM system_settings WHERE setting_key IN ($in)");
        $st->execute(array_keys($defaults));
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $v = trim((string)$r['setting_value']);
            if ($v === '') {
                continue;
            }
            $out[$r['setting_key']] = is_float($defaults[$r['setting_key']]) ? (float)$v : (int)$v;
        }
    } catch (Throwable) {
        // Settings table unreadable — defaults are all perfectly usable.
    }

    $cache = $out;
    return $out;
}

/**
 * Strip HTML, entities, quoted reply chains and signatures down to readable text.
 *
 * @param ?string $html  an email body or note as stored (HTML or plain); null or '' gives ''
 * @param int $maxChars  the most characters kept before an ellipsis is added
 */
function writeupPlainText(?string $html, int $maxChars = 4000): string
{
    $s = (string)$html;
    if ($s === '') {
        return '';
    }

    // Cut the quoted chain before stripping tags — once it's plain text the
    // boundary markers are much harder to find. Each pattern is anchored to a
    // line start so a mention of "From:" mid-sentence doesn't truncate a reply.
    $cuts = [
        '/<blockquote[\s\S]*$/i',
        '/^\s*-{2,}\s*Original Message\s*-{2,}[\s\S]*$/im',
        '/^\s*On .{0,120}\bwrote:\s*$[\s\S]*$/im',
        '/^\s*From:\s.{0,200}$[\s\S]*$/im',
    ];
    foreach ($cuts as $re) {
        $s = preg_replace($re, '', $s) ?? $s;
    }

    $s = preg_replace('/<(script|style)\b[\s\S]*?<\/\1>/i', ' ', $s) ?? $s;
    $s = preg_replace('/<(br|\/p|\/div|\/li|\/tr)\s*\/?>/i', "\n", $s) ?? $s;
    $s = strip_tags($s);
    $s = html_entity_decode($s, ENT_QUOTES, 'UTF-8');
    $s = preg_replace('/[ \t\x{00A0}]+/u', ' ', $s) ?? $s;
    $s = preg_replace('/\n{3,}/', "\n\n", $s) ?? $s;
    $s = trim($s);

    if (mb_strlen($s) > $maxChars) {
        $s = mb_substr($s, 0, $maxChars) . '…';
    }
    return $s;
}

/**
 * How much of an article could actually be written from this one ticket? 0-100.
 *
 * ⚠️ READ THIS BEFORE TRUSTING THE NUMBER. This is a cheap heuristic for
 * ORDERING and for deciding whether it's worth spending a model call at all. It
 * is NOT the judgement of whether an article exists — only the model makes that
 * call, having read the words. A long ticket can still be pure back-and-forth
 * about scheduling, and a short one can contain the exact registry key.
 *
 * The weights encode what actually correlates with a writable ticket on a
 * service desk: somebody explained something at length (resolution + notes),
 * there was a diagnosis rather than a single exchange (turns), and it took real
 * time. Time is the most honest of the four — a two-minute ticket is a password
 * reset no matter how many words were typed.
 *
 * @param array $p  message_count, notes_len, resolution_len, minutes (ints) and has_problem (bool); missing keys count as 0
 */
function writeupRichness(array $p): int
{
    $score = 0;

    // The resolution explanation — the single best predictor.
    $resLen = (int)($p['resolution_len'] ?? 0);
    $score += (int)min(35, $resLen / 20);

    // Internal notes: where analysts write what they actually found.
    $noteLen = (int)($p['notes_len'] ?? 0);
    $score += (int)min(20, $noteLen / 15);

    // Conversation turns beyond the opening question + first answer.
    $turns = max(0, (int)($p['message_count'] ?? 0) - 2);
    $score += (int)min(20, $turns * 5);

    // Time logged. 60+ minutes of someone's life is rarely nothing.
    $mins = (int)($p['minutes'] ?? 0);
    $score += (int)min(15, $mins / 4);

    // Somebody already thought this was a pattern worth a Problem record.
    if (!empty($p['has_problem'])) {
        $score += 10;
    }

    return (int)max(0, min(100, $score));
}

/**
 * Everything the assistant knows about one ticket: the readable text, the parts
 * it was built from, and the richness score.
 *
 * Returns ['ref','subject','text','richness','parts','closed_datetime'] or null
 * if the ticket doesn't exist. Callers are responsible for the access check
 * BEFORE calling — this function does no scoping of its own, deliberately, so
 * that omitting the check is a visible omission at the call site rather than a
 * silent one buried in here.
 */
function writeupTicketBundle(PDO $conn, int $ticketId): ?array
{
    $st = $conn->prepare(
        "SELECT t.id, t.subject, t.closed_datetime, t.tenant_id,
                COALESCE(NULLIF(t.ticket_number, ''), CONCAT('#', t.id)) AS ticket_ref
           FROM tickets t
          WHERE t.id = ?"
    );
    $st->execute([$ticketId]);
    $t = $st->fetch(PDO::FETCH_ASSOC);
    if (!$t) {
        return null;
    }

    // Messages, oldest first — the story reads forwards even though the inbox
    // shows it newest-first.
    $st = $conn->prepare(
        "SELECT direction, body_content, received_datetime
           FROM emails
          WHERE ticket_id = ?
       ORDER BY received_datetime ASC, id ASC
          LIMIT 40"
    );
    $st->execute([$ticketId]);
    $messages = $st->fetchAll(PDO::FETCH_ASSOC);

    $st = $conn->prepare(
        "SELECT note_text, is_internal
           FROM ticket_notes
          WHERE ticket_id = ?
       ORDER BY created_datetime ASC, id ASC
          LIMIT 40"
    );
    $st->execute([$ticketId]);
    $notes = $st->fetchAll(PDO::FETCH_ASSOC);

    $minutes = 0;
    // Not while Time tracking is switched off for the install (GH #217): logged
    // minutes nobody can see should not shape a write-up.
    if (moduleIsEnabled($conn, 'time-tracking')) try {
        $st = $conn->prepare(
            "SELECT COALESCE(SUM(time_spent_minutes), 0)
               FROM ticket_time_entries
              WHERE ticket_id = ? AND is_active = 1"
        );
        $st->execute([$ticketId]);
        $minutes = (int)$st->fetchColumn();
    } catch (Throwable) { $minutes = 0; }

    $hasProblem = false;
    try {
        $st = $conn->prepare("SELECT 1 FROM problem_tickets WHERE ticket_id = ? LIMIT 1");
        $st->execute([$ticketId]);
        $hasProblem = (bool)$st->fetchColumn();
    } catch (Throwable) { $hasProblem = false; }

    // Build the narrative.
    $lines   = [];
    $notesLen = 0;
    $resolutionLen = 0;

    $lines[] = 'SUBJECT: ' . (string)$t['subject'];
    $lines[] = '';

    $lastOutbound = '';
    foreach ($messages as $m) {
        $body = writeupPlainText($m['body_content'] ?? '', 2500);
        if ($body === '') {
            continue;
        }
        $who = (($m['direction'] ?? '') === 'Inbound') ? 'USER' : 'ANALYST';
        $lines[] = $who . ': ' . $body;
        if ($who === 'ANALYST') {
            $lastOutbound = $body;
        }
    }

    if ($notes) {
        $lines[] = '';
        foreach ($notes as $n) {
            $body = writeupPlainText($n['note_text'] ?? '', 2500);
            if ($body === '') {
                continue;
            }
            $notesLen += mb_strlen($body);
            $lines[] = 'INTERNAL NOTE: ' . $body;
        }
    }

    // The resolution, for scoring purposes, is the last thing the analyst said
    // plus whatever they wrote in notes. Notes are frequently where the real
    // answer lives — the customer gets "all sorted now", the note gets the fix.
    $resolutionLen = mb_strlen($lastOutbound) + $notesLen;

    if ($minutes > 0) {
        $lines[] = '';
        $lines[] = 'TIME LOGGED: ' . $minutes . ' minutes';
    }

    $parts = [
        'message_count'  => count($messages),
        'note_count'     => count($notes),
        'notes_len'      => $notesLen,
        'resolution_len' => $resolutionLen,
        'minutes'        => $minutes,
        'has_problem'    => $hasProblem,
    ];

    $text = implode("\n", $lines);
    if (mb_strlen($text) > 14000) {
        $text = mb_substr($text, 0, 14000) . "\n…(truncated)";
    }

    return [
        'ticket_id'       => (int)$t['id'],
        'ref'             => (string)$t['ticket_ref'],
        'subject'         => (string)$t['subject'],
        'closed_datetime' => $t['closed_datetime'],
        'tenant_id'       => $t['tenant_id'] === null ? null : (int)$t['tenant_id'],
        'text'            => $text,
        'parts'           => $parts,
        'richness'        => writeupRichness($parts),
    ];
}

/**
 * The shorter text used for EMBEDDING a ticket (as opposed to drafting from it).
 *
 * Deliberately different from the full bundle: for "is this the same question as
 * that one", what matters is the problem as reported, not the thousand words of
 * troubleshooting that followed. Feeding the whole thread in makes every long
 * ticket look similar to every other long ticket, and clusters collapse into
 * "difficult ones".
 *
 * @param ?string $subject  the ticket's subject if the caller has it; null reads it from tickets
 */
function writeupEmbeddingText(PDO $conn, int $ticketId, ?string $subject = null): string
{
    if ($subject === null) {
        $st = $conn->prepare("SELECT subject FROM tickets WHERE id = ?");
        $st->execute([$ticketId]);
        $subject = (string)($st->fetchColumn() ?: '');
    }

    $st = $conn->prepare(
        "SELECT body_content FROM emails
          WHERE ticket_id = ? AND direction = 'Inbound'
       ORDER BY received_datetime ASC, id ASC LIMIT 1"
    );
    $st->execute([$ticketId]);
    $first = writeupPlainText((string)($st->fetchColumn() ?: ''), 1200);

    return trim($subject . "\n\n" . $first);
}

/* ------------------------------------------------------------------ *
 * Similarity — two engines, one interface
 *
 * Embeddings are better and cost money. Subject matching is cruder and costs
 * nothing. The assistant supports both on purpose: FINDING the gaps should work
 * on every install, including one that will never pay an embeddings bill, and
 * only DRAFTING should require a key. An install with no OpenAI key still gets
 * told "you have answered this 14 times and have no article" — which is the
 * insight — it just matches on wording rather than meaning.
 * ------------------------------------------------------------------ */

/** Words too common on a service desk to carry any signal. */
function writeupStopWords(): array
{
    static $w = null;
    if ($w === null) {
        $w = array_flip([
            're','fw','fwd','the','a','an','and','or','but','is','are','was','were','be','been',
            'to','of','in','on','at','for','with','from','by','my','our','your','i','we','you',
            'it','this','that','there','has','have','had','do','does','did','not','no','can',
            'cannot','cant','wont','will','would','should','could','please','help','issue',
            'issues','problem','problems','request','ticket','urgent','asap','support','hi',
            'hello','thanks','thank','regards','new','need','needs','when','how','what','why',
        ]);
    }
    return $w;
}

/**
 * Crude suffix stemmer, so that different tenses of the same complaint match.
 *
 * ⚠️ NOT cosmetic — the harness in tests/knowledge-gaps caught this. Four tickets
 * saying "VPN keeps disconnecting", "VPN disconnects constantly", "vpn connection
 * drops" and "VPN disconnecting again" failed to cluster at all, purely because
 * "disconnects" and "disconnecting" are different strings. That is the single
 * most common way a real service desk phrases the same question twice, so
 * without this the free engine misses exactly the gaps it exists to find.
 *
 * Deliberately not a full Porter implementation: this runs over subject lines of
 * a dozen words, and the failure mode of over-stemming (two unrelated words
 * colliding) is much worse here than under-stemming.
 *
 * @param string $w  one lower-case word from a subject line
 */
function writeupStem(string $w): string
{
    $len = mb_strlen($w);
    if ($len <= 3) {
        return $w;
    }

    // Adverbs first, so "repeatedly" reaches the same stem as "repeated".
    if ($len > 4 && substr($w, -2) === 'ly') {
        $w   = substr($w, 0, -2);
        $len = mb_strlen($w);
    }

    if ($len > 4 && substr($w, -3) === 'ies') {
        $w = substr($w, 0, -3) . 'y';
    } elseif ($len > 4 && substr($w, -4) === 'sses') {
        $w = substr($w, 0, -2);
    } elseif ($len > 5 && substr($w, -3) === 'ing') {
        $w = substr($w, 0, -3);
    } elseif ($len > 4 && substr($w, -2) === 'ed') {
        $w = substr($w, 0, -2);
    } elseif ($len > 3 && substr($w, -1) === 's' && substr($w, -2) !== 'ss') {
        $w = substr($w, 0, -1);
    }

    // "jamming" → "jamm" → "jam", "stopped" → "stopp" → "stop". Never collapse
    // l/s/z: "call", "pass" and "buzz" are words, not doubled stems.
    $len = mb_strlen($w);
    if ($len > 3 && $w[$len - 1] === $w[$len - 2] && !str_contains('lsz', $w[$len - 1])) {
        $w = substr($w, 0, -1);
    }

    return $w;
}

/**
 * Normalise a subject into comparable tokens: strip Re:/Fwd:, ticket references,
 * numbers, punctuation and stop words, then stem what is left.
 *
 * Numbers go deliberately. "Laptop LT0431 won't boot" and "Laptop LT0899 won't
 * boot" are the same question, and leaving the asset tags in makes them look
 * like different ones — exactly the mistake that would hide the biggest
 * recurring gaps behind a wall of unique-looking subjects.
 *
 * @param string $subject  a ticket subject or article title
 */
function writeupSubjectTokens(string $subject): array
{
    $s = mb_strtolower($subject);
    $s = preg_replace('/^\s*((re|fw|fwd|aw)\s*:\s*)+/iu', '', $s) ?? $s;
    $s = preg_replace('/\[[^\]]*\]/u', ' ', $s) ?? $s;      // [SDREF-123-456]
    $s = preg_replace('/[^\p{L}\s]+/u', ' ', $s) ?? $s;      // drops digits too
    $stop = writeupStopWords();

    $out = [];
    foreach (preg_split('/\s+/u', trim($s)) as $tok) {
        if ($tok === '' || mb_strlen($tok) < 3 || isset($stop[$tok])) {
            continue;
        }
        $stem = writeupStem($tok);
        if ($stem !== '') {
            $out[$stem] = true;
        }
    }
    return array_keys($out);
}

/**
 * Overlap coefficient of two token sets — |A ∩ B| / min(|A|, |B|) — with a floor
 * of two shared tokens.
 *
 * ⚠️ NOT Jaccard, and the harness is why. Jaccard divides by the UNION, so it
 * punishes a subject for the words the other one happens not to have: "VPN keeps
 * disconnecting every few minutes" and "VPN disconnecting again" share
 * everything that matters and still only score 0.29, because one of them
 * mentions minutes. Real subject lines vary wildly in length and filler, so the
 * union is the wrong denominator for the question being asked, which is "is the
 * shorter of these two entirely contained in the longer?".
 *
 * The two-token floor is what makes that safe. The overlap coefficient alone
 * scores any pair sharing a single word out of a two-word subject at 0.5, so
 * "Broken window in the server room" and "Request access to the CAD licence
 * server" would cluster on the word "server". Requiring two shared tokens costs
 * nothing real — no genuine recurring question is identified by one word — and
 * removes the whole class of false cluster.
 *
 * @param array $a  a token list from writeupSubjectTokens()
 * @param array $b  another token list from writeupSubjectTokens()
 */
function writeupTokenSimilarity(array $a, array $b): float
{
    if (!$a || !$b) {
        return 0.0;
    }
    $sa = array_flip($a);
    $inter = 0;
    foreach ($b as $t) {
        if (isset($sa[$t])) {
            $inter++;
        }
    }
    if ($inter < 2) {
        return 0.0;
    }
    $min = min(count($a), count($b));
    return $min > 0 ? $inter / $min : 0.0;
}

/**
 * Single-linkage clustering: grow a cluster outwards from a seed, pulling in
 * anything close to ANY member rather than only to the seed.
 *
 * $items must be pre-sorted by how good a SEED each one is (richest first), so a
 * cluster forms around its most writable ticket and inherits that subject as the
 * label. Each item needs an 'id' and whatever $simFn reads.
 *
 * ⚠️ THE TRANSITIVE STEP IS THE WHOLE POINT, and the harness is why. Comparing
 * only against the seed makes the result depend on which ticket happened to be
 * first. Four real VPN tickets scored: "keeps disconnecting every few minutes"
 * ↔ "disconnecting again" 0.67, "disconnects constantly" ↔ "disconnecting
 * again" 0.67 — but the first against the second only 0.40, because one
 * mentions minutes and the other mentions home. Seeded on the wrong one, a
 * seed-only pass splits a genuine recurring question into fragments too small
 * to report, and the assistant stays silent about the thing it most needed to
 * say. Chaining outwards, they are one cluster of three, which is the truth.
 *
 * Single linkage can over-chain in principle (A~B~C where A and C are
 * unrelated). Here the pairing bar is high and, in wording mode, two tokens must
 * match before any score is returned at all — and the harness carries a negative
 * control of six unrelated one-off tickets that must never end up in a cluster.
 *
 * O(n²) and unapologetic: n is gap CANDIDATES inside a 90-day window — hundreds,
 * not millions — and a real clustering library is not worth a dependency here.
 *
 * @param array $items  candidates sorted best seed first; each needs an 'id' plus whatever $simFn reads
 * @param callable $simFn  function(item, item): float - how alike two items are, 0-1
 * @param float $threshold  the lowest similarity that joins an item to a cluster
 */
function writeupCluster(array $items, callable $simFn, float $threshold): array
{
    $clusters = [];
    $taken    = [];

    foreach ($items as $i => $seed) {
        if (isset($taken[$i])) {
            continue;
        }
        $taken[$i] = true;

        // Breadth-first: each newly admitted member gets its own turn to pull in
        // neighbours the seed could not reach on its own.
        $members = [['item' => $seed, 'similarity' => 1.0]];
        $queue   = [$seed];

        while ($queue) {
            $current = array_shift($queue);
            foreach ($items as $j => $cand) {
                if (isset($taken[$j])) {
                    continue;
                }
                $sim = $simFn($current, $cand);
                if ($sim >= $threshold) {
                    $taken[$j] = true;
                    // Report its similarity to the SEED — that is the cluster's
                    // subject line, so it is what a reader is comparing against.
                    $members[] = ['item' => $cand, 'similarity' => $simFn($seed, $cand)];
                    $queue[]   = $cand;
                }
            }
        }

        $clusters[] = ['seed' => $seed, 'members' => $members];
    }

    return $clusters;
}

/**
 * Tidy a ticket subject into something that reads as a knowledge-base heading.
 * Cosmetic only — the model writes the real title when it drafts.
 *
 * @param string $subject  the ticket subject to tidy into a heading
 */
function writeupCleanLabel(string $subject): string
{
    $s = trim($subject);
    $s = preg_replace('/^\s*((re|fw|fwd|aw)\s*:\s*)+/iu', '', $s) ?? $s;
    $s = preg_replace('/\[[^\]]*\]/u', '', $s) ?? $s;
    $s = preg_replace('/\s+/u', ' ', $s) ?? $s;
    $s = trim($s, " \t-–—:");
    if ($s === '') {
        $s = 'Untitled request';
    }
    if (mb_strlen($s) > 200) {
        $s = mb_substr($s, 0, 200) . '…';
    }
    return $s;
}

/* ------------------------------------------------------------------ *
 * Prompts
 * ------------------------------------------------------------------ */

/**
 * The system prompt. One call does both jobs — judge, then write — because the
 * judgement is worthless if a second call can overrule it, and because a single
 * streamed response is a far better experience than a spinner followed by a
 * verdict followed by another spinner.
 *
 * The response protocol is a single first line, `VERDICT: ARTICLE` or
 * `VERDICT: NOT_ENOUGH`, so the front end can branch while still streaming the
 * rest token by token.
 *
 * @param string $mode  'single' (one ticket), 'cluster' (a recurring question) or 'answers' (the analyst has replied)
 */
function writeupSystemPrompt(string $mode = 'single'): string
{
    $shared = <<<'TXT'
You are a technical writer embedded in an IT service desk. You turn resolved
support tickets into knowledge base articles — but only when there is genuinely
an article there.

YOUR FIRST JOB IS TO REFUSE.

Most tickets are not articles. "Reset the user's password and asked them to log
in again" describes an action, not knowledge: it says nothing about why the
account locked, how you diagnosed it, or what anyone should do next time. An
article written from that ticket would be padding around a single sentence, and
publishing it makes the knowledge base worse, not better — readers learn it
cannot be trusted and stop searching it.

Refuse when the ticket does not tell you AT LEAST TWO of:
  - what actually caused the problem (not just what was done about it)
  - how the cause was identified — the diagnostic step, the error, the log line
  - the specific fix, in enough detail that someone else could repeat it
  - how to confirm it worked, or how to avoid it happening again

Be strict. A confident guess is worse than a refusal, because nobody checks a
published article as carefully as they check an empty page. NEVER invent a cause,
a command, a path, a setting name or a version number that is not in the ticket.
If the ticket does not say, you do not know.

WHEN YOU REFUSE, ASK. A refusal is not the end — the analyst is sitting right
there and can fill the gap in thirty seconds. After the verdict line, write one
short paragraph saying plainly what is missing, then ask TWO TO FOUR specific
questions whose answers would let you write the article. Ask about this ticket,
not in general: name the error, the system, the symptom you can see in the
thread. Put each question on its own line beginning with "- ".

PRIVACY. Articles get published, often to a customer-facing portal. Never include
a person's name, email address, phone number, job title, physical location,
individual machine name, IP address, ticket reference, or company name. Write
about "the user" and "the affected machine". Keep product names, error messages,
version numbers and technical paths — those are the whole point.

STYLE. Write for a colleague in a hurry who has the problem right now. Lead with
the symptom as they would search for it. Short sentences, plain words, no
marketing tone, no "in today's fast-paced IT environment". British English.

FORMAT when you are writing an article. Output simple HTML using only these
tags: <h2> <h3> <p> <ul> <ol> <li> <strong> <em> <code> <pre>. No <html>, <head>,
<body>, <style>, <script>, no attributes of any kind, no markdown fences. Begin
with a single <h1> containing the article title, then the body. Structure it as:
symptom, cause, resolution steps, and how to check it worked. Omit any section
the ticket cannot support rather than padding it.
TXT;

    if ($mode === 'cluster') {
        $extra = <<<'TXT'


YOU ARE WRITING FROM A CLUSTER. You will be given the most detailed example of a
question this service desk has answered many times, plus the subject lines of the
others. The volume is already established — you do NOT need to judge whether the
topic is worth an article, only whether the material in front of you is enough to
write an accurate one. The other subject lines are context for how people phrase
the question; do not invent detail from them, and do not list them.
TXT;
        return $shared . $extra . "\n\n" . writeupProtocolBlock();
    }

    if ($mode === 'answers') {
        $extra = <<<'TXT'


THE ANALYST HAS ANSWERED YOUR QUESTIONS. Their answers are authoritative — they
were there and you were not. Treat them as fact even where they contradict your
reading of the thread. If the answers now give you enough, write the article. If
they are still too thin, say so honestly rather than padding; do not ask the same
question twice.
TXT;
        return $shared . $extra . "\n\n" . writeupProtocolBlock();
    }

    return $shared . "\n\n" . writeupProtocolBlock();
}

/** The response protocol, shared by all three modes so it can never drift apart. */
function writeupProtocolBlock(): string
{
    return <<<'TXT'
RESPONSE PROTOCOL — this is not optional.

Your VERY FIRST LINE must be exactly one of:
VERDICT: ARTICLE
VERDICT: NOT_ENOUGH

Nothing may precede it — no preamble, no explanation, no blank line.

If ARTICLE: the rest of your response is the article HTML and nothing else. No
commentary before or after it.

If NOT_ENOUGH: the rest is one short paragraph of plain text explaining what is
missing, then your questions, each on its own line starting with "- ". No HTML.
TXT;
}

/**
 * Build the user message for a single-ticket write-up.
 *
 * @param array $bundle  the ticket bundle from writeupTicketBundle() - only 'text' is used
 * @param string $answers  the analyst's answers to the model's questions; '' for a first attempt
 */
function writeupUserMessage(array $bundle, string $answers = ''): string
{
    $msg  = "RESOLVED TICKET\n\n" . $bundle['text'];
    if (trim($answers) !== '') {
        $msg .= "\n\n---\n\nANALYST'S ANSWERS TO YOUR QUESTIONS:\n" . trim($answers);
    }
    return $msg;
}

/**
 * Build the user message for a cluster write-up: the richest ticket as the
 * source, the rest as evidence of how the question gets asked.
 *
 * @param array $bundle  the bundle of the cluster's richest ticket, from writeupTicketBundle()
 * @param array $otherSubjects  the subjects of the other tickets in the cluster; the first 15 non-blank are sent
 * @param int $count  how many tickets are in the cluster
 * @param string $answers  the analyst's answers to the model's questions; '' for a first attempt
 */
function writeupClusterUserMessage(array $bundle, array $otherSubjects, int $count, string $answers = ''): string
{
    $msg  = "THIS SERVICE DESK HAS ANSWERED THIS QUESTION {$count} TIMES AND HAS NO ARTICLE FOR IT.\n\n";
    $msg .= "MOST DETAILED EXAMPLE:\n\n" . $bundle['text'];

    $others = array_slice(array_filter(array_map('trim', $otherSubjects)), 0, 15);
    if ($others) {
        $msg .= "\n\n---\n\nHOW THE OTHERS WERE PHRASED (context only — do not invent detail from these):\n";
        foreach ($others as $s) {
            $msg .= '- ' . $s . "\n";
        }
    }
    if (trim($answers) !== '') {
        $msg .= "\n---\n\nANALYST'S ANSWERS TO YOUR QUESTIONS:\n" . trim($answers);
    }
    return $msg;
}

/**
 * Split a model response into its verdict and body.
 *
 * Tolerant on purpose: a model that drops the colon, adds a markdown fence or
 * emits a stray blank line first should not turn a good article into an error.
 * The one thing we never do is GUESS a verdict — an unrecognised response is
 * treated as NOT_ENOUGH, because the failure mode of wrongly publishing is worse
 * than the failure mode of wrongly asking.
 *
 * @param string $raw  the model's whole response text
 */
function writeupParseResponse(string $raw): array
{
    $s = ltrim($raw);
    $s = preg_replace('/^```[a-z]*\s*/i', '', $s) ?? $s;
    $s = preg_replace('/```\s*$/', '', $s) ?? $s;
    $s = ltrim($s);

    if (preg_match('/^VERDICT\s*:?\s*ARTICLE\b[ \t]*\r?\n?/i', $s, $m)) {
        return ['verdict' => 'article', 'body' => trim(substr($s, strlen($m[0])))];
    }
    if (preg_match('/^VERDICT\s*:?\s*NOT[_ ]?ENOUGH\b[ \t]*\r?\n?/i', $s, $m)) {
        return ['verdict' => 'not_enough', 'body' => trim(substr($s, strlen($m[0])))];
    }
    return ['verdict' => 'not_enough', 'body' => trim($s)];
}

/**
 * Pull the "- " question lines out of a NOT_ENOUGH body.
 *
 * @param string $body  the body of a NOT_ENOUGH response, from writeupParseResponse()
 */
function writeupExtractQuestions(string $body): array
{
    $out = [];
    foreach (preg_split('/\r?\n/', $body) as $line) {
        $line = trim($line);
        if ($line !== '' && (str_starts_with($line, '- ') || str_starts_with($line, '• '))) {
            $q = trim(mb_substr($line, 2));
            if ($q !== '') {
                $out[] = $q;
            }
        }
    }
    return $out;
}

/**
 * The prose part of a NOT_ENOUGH body, with the question lines removed.
 *
 * @param string $body  the body of a NOT_ENOUGH response, from writeupParseResponse()
 */
function writeupExtractExplanation(string $body): string
{
    $keep = [];
    foreach (preg_split('/\r?\n/', $body) as $line) {
        $t = trim($line);
        if ($t === '' || str_starts_with($t, '- ') || str_starts_with($t, '• ')) {
            continue;
        }
        $keep[] = $t;
    }
    return trim(implode(' ', $keep));
}
