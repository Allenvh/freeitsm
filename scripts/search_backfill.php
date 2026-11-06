<?php
/**
 * Rebuild the search corpus from existing ticket content.
 *
 *   php scripts/search_backfill.php            index everything
 *   php scripts/search_backfill.php --limit=50 index the first 50 tickets (a sample)
 *   php scripts/search_backfill.php --prune    also drop rows for trashed tickets
 *   php scripts/search_backfill.php --stats    report what is in the corpus and stop
 *
 * TEXT ONLY — subjects, message bodies and notes. It never opens an attachment.
 *
 * CLI only. Safe to re-run: every write is an upsert, so a second run updates in
 * place rather than duplicating.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("This script is command-line only.\n");
}

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/search/backfill.php';

$args  = array_slice($argv, 1);
$opt   = function (string $name, $default = null) use ($args) {
    foreach ($args as $a) {
        if ($a === "--$name") return true;
        if (str_starts_with($a, "--$name=")) return substr($a, strlen($name) + 3);
    }
    return $default;
};

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function corpusStats(PDO $conn): void {
    $total = (int)$conn->query("SELECT COUNT(*) FROM search_documents")->fetchColumn();
    printf("corpus: %s rows\n", number_format($total));
    if (!$total) return;
    foreach ($conn->query("SELECT source_type, COUNT(*) n, SUM(CHAR_LENGTH(body)) chars
                             FROM search_documents GROUP BY source_type ORDER BY n DESC") as $r) {
        printf("   %-14s %8s rows  %12s chars of text\n",
               $r[0], number_format((int)$r[1]), number_format((int)$r[2]));
    }
    printf("   %-14s %8s tickets covered\n", '', number_format(
        (int)$conn->query("SELECT COUNT(DISTINCT ticket_id) FROM search_documents WHERE ticket_id IS NOT NULL")->fetchColumn()));
    printf("   last indexed   %s\n", (string)$conn->query("SELECT MAX(indexed_datetime) FROM search_documents")->fetchColumn());
}

if ($opt('stats')) { corpusStats($conn); exit(0); }

$limit = (int)$opt('limit', 0);
echo "Indexing ticket subjects, message bodies, notes and knowledge articles"
   . ($limit ? " (first $limit tickets)" : "")
   . ". Attachments are NOT touched.\n\n";

try {
    $res = searchBackfillRun($conn, ['limit' => $limit], function ($stage, $done, $total) {
        if ($total > 0) printf("\r  %s: %d / %d (%d%%)   ", $stage, $done, $total, (int)round($done * 100 / $total));
    });
} catch (Throwable $e) {
    fwrite(STDERR, "\nFAILED: " . $e->getMessage() . "\n");
    exit(1);
}

printf("\n\nindexed %s ticket subjects, %s messages, %s notes, %s articles in %ss (%s empty rows skipped)\n",
    number_format($res['tickets']), number_format($res['emails']),
    number_format($res['notes']), number_format($res['articles'] ?? 0),
    $res['seconds'], number_format($res['skipped']));

if ($opt('prune')) {
    printf("pruned %s rows belonging to trashed tickets\n", number_format(searchBackfillPrune($conn)));
}

echo "\n";
corpusStats($conn);
