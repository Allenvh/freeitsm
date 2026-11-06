# includes/search/extract.php

> PHP - 234 lines

Pull readable text out of an attachment — the dependency-free tier (discussion #53, tier 1 of the wiki's §8.2).

WHAT THIS DELIBERATELY DOES NOT DO
----------------------------------
No PDFs, no legacy .doc/.xls, no OCR. Those need a real parser, and a real
parser has no business running inside the web request:

  - The input is HOSTILE. An attachment arrives from anyone who can email the
    service desk, through api/tickets/check_mailbox_email.php, which has no
    authentication at all. Malformed documents are a well-trodden route to
    remote code execution, and this process holds the database credentials.
  - FreeITSM has no Composer, so a PHP PDF library would be vendored by hand
    and ours to patch for its lifetime. A bundled library version was itself
    a finding in the August 2026 security review.

Those formats are tier 2: one external extractor, in a container, reached over
HTTP — see includes/search/tika.php. Until an install configures one they are
recorded as `unsupported`, which is
the honest answer rather than silence.

WHAT IT DOES HANDLE, AND WHY IT NEEDS NOTHING
---------------------------------------------
  .txt .csv .log .md .json .xml   already text; read it
  .docx .xlsx .pptx               a ZIP of XML. ZipArchive + strip tags.
                                  includes/rfp_docx_parser.php has done this
                                  since the RFP builder; no library involved.

⚠️ The RFP parser is NOT reused as-is. It was written for documents chosen and
uploaded by a signed-in member of staff; these arrive from strangers. Same
file format, completely different threat model — hence the zip-bomb guards
below, which that parser has no reason to carry.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [attTextExtension()](/code/functions/attTextExtension.md) | 93 | The extension, lowercased, or '' if there isn't one. |
| [attTextSupports()](/code/functions/attTextSupports.md) | 103 | Could this tier read the file at all? Used to answer before opening anything. |
| [attTextNormalise()](/code/functions/attTextNormalise.md) | 117 | Reduce extracted XML/text to the words worth indexing. |
| [attTextExtractFile()](/code/functions/attTextExtractFile.md) | 137 | Read the text of one file. |
| [attTextExtractOoxml()](/code/functions/attTextExtractOoxml.md) | 193 | Text out of a docx/xlsx/pptx. Returns null if the archive cannot be opened. |

## How it hangs together

**Included by:** [api/system/search_status.php](/code/files/api/system/search_status.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md)

