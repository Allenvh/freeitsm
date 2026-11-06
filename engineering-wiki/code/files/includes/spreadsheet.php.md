# includes/spreadsheet.php

> PHP - 441 lines

Spreadsheets in and out - CSV and Excel (.xlsx), in plain PHP.

First used by System -> Cost Centres (GH #160); written to be reused by any
list that wants an import/export.

🔑 WHY .xlsx AND NOT JUST CSV: LEADING ZEROS. A cost centre "0010" written to
a CSV comes back as 10 the moment somebody double-clicks the file, because
Excel decides it is a number - and saving it again writes 10. An .xlsx can say
"this cell is text", so every cell this file WRITES is text, and Excel shows
0010. CSV stays for systems that only speak CSV.

🔑 WHY NO ZipArchive: an .xlsx is a zip of XML files, but the Docker image
has no zip extension (docker-php-ext-install only adds pdo/pdo_mysql) and
plenty of shared hosts lack it too. Zip is simple enough to read and write
here with zlib, which every PHP has. The writer stores (no compression); the
reader handles stored and deflated entries, which is all Excel, LibreOffice
and Google Sheets produce.

The reader is guarded like includes/search/extract.php - an uploaded zip is
untrusted: a cap on entries, on each entry's unzipped size and on the total.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [spreadsheetRead()](/code/functions/spreadsheetRead.md) | 40 | Read an uploaded CSV or .xlsx into rows of strings (the first sheet of a workbook). Every value is a string exactly as the person sees it, trimmed. Blank rows are dropped. |
| [spreadsheetCleanText()](/code/functions/spreadsheetCleanText.md) | 71 | Strip control characters (bar tab) - a pasted line break must not reach the database. |
| [spreadsheetReadCsv()](/code/functions/spreadsheetReadCsv.md) | 86 | CSV in any of the shapes Excel saves: UTF-8 with or without a BOM, or the Windows code page; comma, semicolon (Excel in most of Europe) or tab. |
| [spreadsheetWriteCsv()](/code/functions/spreadsheetWriteCsv.md) | 132 | CSV bytes: UTF-8 with a BOM (so Excel reads accents correctly), CRLF. |
| [spreadsheetWriteXlsx()](/code/functions/spreadsheetWriteXlsx.md) | 159 | A one-sheet .xlsx. Every cell is an inline TEXT cell, never a number - that is the point (see the file header). The header row is bold and frozen. |
| [spreadsheetZipStore()](/code/functions/spreadsheetZipStore.md) | 232 | A zip with every entry STORED (no compression) - valid, and all Excel needs. |
| [spreadsheetReadXlsx()](/code/functions/spreadsheetReadXlsx.md) | 259 | The first worksheet of an .xlsx as rows of display strings. |
| [spreadsheetColIndex()](/code/functions/spreadsheetColIndex.md) | 345 | "BC12" -> 54 (zero-based column). |
| [spreadsheetInlineText()](/code/functions/spreadsheetInlineText.md) | 358 | The text of an <si> or <is>: plain <t>, or rich-text runs <r><t>. Phonetic runs are skipped. |
| [spreadsheetXml()](/code/functions/spreadsheetXml.md) | 373 | Parses one XML part of an .xlsx file safely (no network, no entity expansion); null when empty or not valid XML. |
| [spreadsheetZipOpen()](/code/functions/spreadsheetZipOpen.md) | 390 | Read a zip's central directory. Returns [bytes, [name => entry]]. |
| [spreadsheetZipGet()](/code/functions/spreadsheetZipGet.md) | 418 | One entry's bytes, or null when it is not there. |

## How it hangs together

**Included by:** [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md), [api/projects/export.php](/code/files/api/projects/export.php.md), [api/system/cost_centres.php](/code/files/api/system/cost_centres.php.md)

