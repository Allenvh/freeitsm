# Import a SharePoint issue list into FreeITSM

This is a command-line migration, not a new web import screen. It has three stages:
export SharePoint, prepare a resolved manifest using read-only API lookups, then
run a local administrator's historical import while the app is stopped.

The supplied mapping matches your issue-list schema and selects **Created dates in
2026**. Files, comments and historical dates are preserved; sign-off values remain
historical information and do not start an approval process.

## What is preserved

| Source | FreeITSM |
| --- | --- |
| ID / list identity | Server-side import ledger, source reference and duplicate protection |
| Title / Issue description | Ticket subject and opening description |
| Issue logged by | Requester, resolved to email through SharePoint UserInfo; Author is the fallback only when the field is absent |
| Assigned to | Exactly one active analyst matched by email; assigned analyst and owner |
| Status / Priority / Issue Type | Explicit lookup-name or ID mappings |
| Created / Modified | Native ticket creation/modification timestamps; opening message uses original creation date |
| Date reported / Issue source | Original values in internal historical context |
| Resolution / Sign-off status / approval code | Internal historical context, without new approvals |
| Comments and replies | Internal notes with original dates and author labels |
| Associated files / Images | Native staff-only attachments, checked against exported SHA-256 hashes |
| Other exported fields | Internal context and original source JSON in the import ledger |

Historical staff notes and files are not exposed automatically to requesters. Portal
thread/download rules and search scope keep them internal even if the site normally
shows third-party emails. Analysts can review and share appropriate material later.
If a commenter's email matches exactly one analyst, that analyst is attributed;
otherwise the importing admin is the native author and the note names the original
SharePoint person. No analyst accounts or permissions are created automatically.

Your schema has no original closure timestamp. Closed source statuses remain closed,
but `closed_datetime` stays unknown; Modified is not guessed to mean Closed. The
migration audit is recorded at the actual import time. SharePoint list-item
version/edit history is separate from comments and is not reconstructed by this tool.

## 1. Export from SharePoint

Use PowerShell 7 with a supported `PnP.PowerShell` module and your organisation's
approved Entra application client ID for interactive PnP sign-in. Read access to the
site/list, UserInfo, comments and associated files is needed. Do not share secrets
in chat or put them in the scripts. The helper does not provision applications.

Run from the repository, choosing a NEW folder outside the application/web root:

```powershell
./scripts/export_sharepoint_tickets.ps1 `
  -SiteUrl "https://YOUR-TENANT.sharepoint.com/sites/YOUR-SITE" `
  -ListIdentity "YOUR-LIST-NAME-OR-GUID" `
  -ClientId "YOUR-APP-CLIENT-ID" `
  -OutputDirectory "$env:USERPROFILE\FreeITSM-migration-2026" `
  -Year 2026
```

Freeze source-list edits for the final export/cutover; this is not continuous sync.
The query is **Created >= 2026-01-01 UTC and Created < 2027-01-01 UTC**. Date
reported is retained separately. Older open tickets are not included merely because
they were updated this year: export their creation years too if they must migrate.
The script defaults to the execution year; specify `-Year` for repeatability.

It uses the supplied internal field names, including `Issueloggedby`, `Assignedto`,
`Issue_x0020_Type`, `Sign_x002d_off_x0020_status`, `DateReported`, `IssueSource`,
`Resolution`, `Images` and `Attachments`. Person display names are never guessed to
be emails. An unresolved requester/assignee email stops the export for review.

Output:

- `tickets.csv`, with resolved requester/assignee emails and ISO source dates;
- `mapping.json`, tailored to the source list;
- downloaded `files/`, plus `files.json` names, relative paths and SHA-256 hashes;
- `comments.json`, retaining comment data including paginated replies;
- `export-summary.json`, written LAST to mark successful completion.

Existing folders are refused. A lookup/download failure stops the export; retain
and investigate a partial folder, but do not treat it as a completed export. Explicit
`-SkipFiles` / `-SkipComments` options exist for archive-only uses; historical
preparation refuses bundles marked as having skipped either.

SharePoint-specific permissions/API behavior still need testing with your tenant.
The export helper has offline fixture tests, not a live connection to your site.

## 2. Review the mappings

The generated mapping starts from
`examples/sharepoint-import/issue-tracker-mapping.json`. It preserves all nine source
statuses, four priorities and five issue types by matching destination names:

- Status: Blocked, In progress, Completed, Duplicate, By design, Won't fix, New,
  On Hold, TEST-NoAction needed.
- Priority: Critical, High, Normal, Low.
- Issue Type: Alert, Incident, Request, Project, Change.

Create the corresponding lookup entries in Tickets settings, or deliberately remap
each label to an existing destination name/positive ID in `values`. Names must match
exactly one active entry, case-insensitively. Missing/ambiguous entries stop preflight.
Original status/type values remain in internal source context even when remapped.
Project and Change remain ticket types here; this does not create other-module records.

Set the correct `company_id` default (initially `Default`). Optionally add a
`department_id` default by name or ID. Assignees need an existing active analyst
with the resolved email. Handle former employees/unmatched emails explicitly before
import; the tool does not promote requester accounts or silently guess assignments.

`columns` maps CSV headers, `values` translates lookup labels, `defaults` fills blank
lookup fields, and `custom_fields` maps required FreeITSM custom-field keys to headers.
`metadata` retains extra source fields; `html_metadata` converts selected rich text
for preview. Raw source fields are retained by the historical importer regardless.

CSV must be UTF-8, optionally with BOM. `.xlsx`, `.iqy`, the schema XML and display
names alone are not data exports. For semicolon CSV, pass `--delimiter ';'`.
A custom CSV needs timezone-qualified ISO Created/Modified values, source-comment
JSON and a complete companion archive; the export helper produces those for you.

## 3. Preview and resolve destination IDs (no writes)

The Python preparer requires Python 3.9+ and uses only the standard library:

```powershell
$Export = "$env:USERPROFILE\FreeITSM-migration-2026"
python scripts/import_sharepoint_tickets.py preview --csv "$Export\tickets.csv" --mapping "$Export\mapping.json"
```

Preview is offline and requires no key. Its JSON contains ticket data: keep it private.
All selected rows are validated before import, including duplicate IDs and missing
requester emails. The mapping's year filter applies before row conversion.

Create a dedicated key under System → API, restricted to the correct company and
with reference-data read permissions. The acting analyst must have the appropriate
access too. The preparer is GET-only: it cannot create tickets, send emails or fire
workflows through the API. Read the key securely into the environment:

```powershell
$ApiKey = Read-Host "FreeITSM API key" -AsSecureString
$env:FREEITSM_API_KEY = [System.Net.NetworkCredential]::new("", $ApiKey).Password
$Url = "http://localhost:8080"
python scripts/import_sharepoint_tickets.py lookups --url $Url
python scripts/import_sharepoint_tickets.py check --url $Url --csv "$Export\tickets.csv" --mapping "$Export\mapping.json"
```

`check` shows resolved destination IDs and analyst-email matches. It cannot prove
all business rules (for example required custom fields) will accept a later create.
Test a small reviewed batch in staging first.

Remote API connections require HTTPS with valid certificates; HTTP is restricted
to localhost. Use `SSL_CERT_FILE` for a private CA, not disabled TLS checks. Redirects
are refused so the bearer token cannot be forwarded elsewhere.

Prepare the private manifest beside the CSV and files:

```powershell
python scripts/import_sharepoint_tickets.py prepare-history --url $Url --csv "$Export\tickets.csv" --mapping "$Export\mapping.json" --output "$Export\plan.json"
Remove-Item Env:\FREEITSM_API_KEY
```

This verifies file paths, sizes/hashes, timestamps and export completeness, and resolves
lookup names to IDs. Use `--limit 5` to prepare a small first batch. Output files are
never overwritten. Do not edit the prepared manifest: PHP verifies its content
fingerprint. Correct the export/mapping and generate a new plan file instead.

Staff context is separated from the requester-facing opening description during
preparation. Keep the whole export bundle: `plan.json` needs its relative `files/`.

## 4. Run the native historical import

Back up FreeITSM's database, encryption key and uploads. Merge/build this version,
then stop the normal app and background jobs. Keep the existing Compose project
name, environment file and persistent volumes; never use `down -v`.

Run from PowerShell in the same repository directory:

```powershell
$EnvFile = ".env"  # use your existing .env.example instead if that is your setup
python -m podman_compose --env-file $EnvFile build app
python -m podman_compose --env-file $EnvFile stop app

# Replace actor 1 with your active System administrator's analyst ID.
# Replace freeitsm with your configured DB_NAME. First command previews only:
python -m podman_compose --env-file $EnvFile run --rm --no-deps --entrypoint php -v "${Export}:/migration:ro" app scripts/import_sharepoint_history.php --manifest /migration/plan.json --actor 1 --expected-database freeitsm

# After reviewing the preview and backups:
python -m podman_compose --env-file $EnvFile run --rm --no-deps --entrypoint php -v "${Export}:/migration:ro" app scripts/import_sharepoint_history.php --manifest /migration/plan.json --actor 1 --expected-database freeitsm --apply
```

The PHP command refuses HTTP execution, non-admin actors and a mismatched database
name. Preview reads/validates all selected records and files without creating its
ledger or tickets. The configured attachment type/size policy still applies. A
refused file is never silently dropped to declare an import successful.

Each source item commits its ticket, comments, attachment rows and server-side
`sharepoint_ticket_imports` entry together. Failed items roll back those changes and
newly stored files; earlier successful items remain. An abrupt kill can leave
unreferenced random files, but cannot commit an unrecorded ticket. Rerun the same
manifest to skip identical imported source IDs; changed imported records are refused
for reconciliation. This is a migration, not a continuous update/sync engine.

Keep the stable source identifier across years and reruns, and include the ledger
table in database backups. Do not run this importer over tickets migrated through
an unrelated tool without first reconciling their source IDs.

Creation-time workflows/emails/manager notifications are suppressed, not replayed
for historical tickets. Normal UI/API ticket creation keeps its existing behavior.
Rebuild search separately without replaying events:

```powershell
python -m podman_compose --env-file $EnvFile run --rm --no-deps --entrypoint php app scripts/search_backfill.php
```

Before restarting, review SLA/escalation jobs: old open tickets with original creation
dates may now be overdue. Suppressing import-time notifications does not disable
future scheduled SLA jobs. For an initial UI review, temporarily set
`FREEITSM_SCHEDULER=0` in the environment file and keep any external cron jobs paused.
Then start the app:

```powershell
python -m podman_compose --env-file $EnvFile up -d --force-recreate app
```

Check native dates, status/priority/type, requester, owner, internal comments,
attachment downloads, portal/search isolation and a normal new-ticket creation.
When ready to resume background processing, restore `FREEITSM_SCHEDULER=1` and
recreate the app again with the same command.

## Tests and limits

```text
python -m unittest discover -s tests -p sharepoint_import_test.py -v
pwsh -NoProfile -File tests/sharepoint-export/run.ps1
FREEITSM_TEST_DATABASE=<disposable-db> php tests/sharepoint_history.php
```

Python tests use a loopback fake API and generated credentials. PowerShell tests
use an offline PnP stand-in, including reply pagination and file hashes. PHP tests
write only to an explicitly named disposable database and cover dates, file/comment
storage, rollback, duplicate protection, portal/search privacy and normal-create
workflow controls. No tests access your SharePoint tenant or modify your installation.
