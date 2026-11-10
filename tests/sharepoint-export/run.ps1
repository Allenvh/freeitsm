# Offline only. Requires PowerShell 7; makes no network requests.
$ErrorActionPreference = 'Stop'
$originalModulePath = $env:PSModulePath
$temp = Join-Path ([IO.Path]::GetTempPath()) ('sharepoint-export-' + [guid]::NewGuid())
try {
    $env:PSModulePath = $PSScriptRoot + [IO.Path]::PathSeparator + $env:PSModulePath
    $exporter = Join-Path $PSScriptRoot '../../scripts/export_sharepoint_tickets.ps1'
    & $exporter -SiteUrl 'https://example.sharepoint.com/sites/test' -ListIdentity 'Issues' -ClientId 'offline-fixture' -OutputDirectory $temp -Year 2026
    $rows = @(Import-Csv -LiteralPath (Join-Path $temp 'tickets.csv'))
    if ($rows.Count -ne 1 -or $rows[0].'Requester email' -ne 'requester@example.test' -or $rows[0].'Created By email' -ne 'creator@example.test') { throw 'Requester/creator lookup lost attribution' }
    if ($rows[0].'Assigned to email' -ne 'analyst@example.test') { throw 'Assignee lookup lost email' }
    if ($rows[0].'Sign-off status' -ne 'Signed off' -or $rows[0].Resolution -notmatch 'Fixed' -or $rows[0].'Source comments' -notmatch 'Original comment') { throw 'Historical fields missing' }
    $comment = $rows[0].'Source comments' | ConvertFrom-Json
    if ($comment.replies_all.Count -ne 2 -or $comment.replies_all[1].text -ne 'Second reply') { throw 'Paginated comment replies missing' }
    $manifest = @(Get-Content -LiteralPath (Join-Path $temp 'files.json') -Raw | ConvertFrom-Json)
    if ($manifest.Count -ne 2) { throw 'Attachment/image export missing' }
    foreach ($file in $manifest) {
        $path = Join-Path $temp $file.path
        if ((Get-FileHash -LiteralPath $path -Algorithm SHA256).Hash.ToLowerInvariant() -ne $file.sha256) { throw 'File hash mismatch' }
    }
    $mapping = Get-Content -LiteralPath (Join-Path $temp 'mapping.json') -Raw | ConvertFrom-Json
    if ($mapping.year -ne 2026 -or $mapping.source -notmatch '#list=00000000-0000-0000-0000-000000000123') { throw 'Stable namespace/year missing' }
    try {
        & $exporter -SiteUrl 'https://example.sharepoint.com/sites/test' -ListIdentity 'Issues' -ClientId 'offline-fixture' -OutputDirectory $temp -Year 2026
        throw 'Existing output was overwritten'
    } catch {
        if ($_.Exception.Message -notmatch 'NEW output directory') { throw }
    }
    Write-Host 'PASS requester/creator/assignee emails, source dates/statuses, resolution/comments, attachment/image hashes, namespace and overwrite refusal'
} finally {
    $env:PSModulePath = $originalModulePath
    if (Test-Path -LiteralPath $temp) { Remove-Item -LiteralPath $temp -Recurse -Force }
}
