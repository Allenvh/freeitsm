# Run locally with PowerShell 7 and PnP.PowerShell. Never place exported data in a web root.
[CmdletBinding()]
param(
    [Parameter(Mandatory)][string]$SiteUrl,
    [Parameter(Mandatory)][string]$ListIdentity,
    [Parameter(Mandatory)][string]$ClientId,
    [Parameter(Mandatory)][string]$OutputDirectory,
    [ValidateRange(2000, 2100)][int]$Year = (Get-Date).Year,
    [switch]$SkipFiles,
    [switch]$SkipComments
)
$ErrorActionPreference = 'Stop'
$applicationRoot = [IO.Path]::GetFullPath((Join-Path $PSScriptRoot '..')).TrimEnd([IO.Path]::DirectorySeparatorChar) + [IO.Path]::DirectorySeparatorChar
$outputPath = [IO.Path]::GetFullPath($OutputDirectory).TrimEnd([IO.Path]::DirectorySeparatorChar) + [IO.Path]::DirectorySeparatorChar
if ($outputPath.StartsWith($applicationRoot, [StringComparison]::OrdinalIgnoreCase)) { throw 'Keep exports outside the application/web root.' }
if (Test-Path -LiteralPath $OutputDirectory) { throw 'Choose a NEW output directory; existing exports are never overwritten.' }
if (-not (Get-Module -ListAvailable PnP.PowerShell)) { throw 'Install the supported PnP.PowerShell module in PowerShell 7 first.' }
Connect-PnPOnline -Url $SiteUrl -Interactive -ClientId $ClientId
$list = Get-PnPList -Identity $ListIdentity
$listId = $list.Id.ToString()
$start = '{0}-01-01T00:00:00Z' -f $Year
$end = '{0}-01-01T00:00:00Z' -f ($Year + 1)
$query = "<View Scope='RecursiveAll'><Query><Where><And><Geq><FieldRef Name='Created'/><Value Type='DateTime' IncludeTimeValue='TRUE' StorageTZ='TRUE'>$start</Value></Geq><Lt><FieldRef Name='Created'/><Value Type='DateTime' IncludeTimeValue='TRUE' StorageTZ='TRUE'>$end</Value></Lt></And></Where><OrderBy><FieldRef Name='ID'/></OrderBy></Query><RowLimit Paged='TRUE'>500</RowLimit></View>"
$items = @(Get-PnPListItem -List $list -Query $query -PageSize 500)
if ($items.Count -eq 0) { throw "No items have Created dates in $Year (UTC)." }
$root = (New-Item -ItemType Directory -Path $OutputDirectory).FullName
$people = @{}
$files = [System.Collections.Generic.List[object]]::new()
$rows = [System.Collections.Generic.List[object]]::new()
$commentsArchive = [System.Collections.Generic.List[object]]::new()

function Resolve-Person($value) {
    if ($null -eq $value) { return $null }
    $id = [int]$value.LookupId
    if (-not $people.ContainsKey($id)) {
        $user = Get-PnPUser -Identity $id
        if ([string]::IsNullOrWhiteSpace($user.Email)) { throw "SharePoint person ID $id has no email; resolve this account before exporting." }
        $people[$id] = [pscustomobject]@{ Email = $user.Email.ToLowerInvariant(); Name = $user.Title; Id = $id }
    }
    return $people[$id]
}

function Format-Date($value) {
    if ($null -eq $value) { return '' }
    # CSOM returns SharePoint storage timestamps as UTC, even when Kind is Unspecified.
    $date = [datetime]$value
    if ($date.Kind -eq [DateTimeKind]::Unspecified) { $date = [datetime]::SpecifyKind($date, [DateTimeKind]::Utc) }
    return $date.ToUniversalTime().ToString('o')
}

function Expand-CommentReplies($comment) {
    $children = [System.Collections.Generic.List[object]]::new()
    $replyPage = $comment.replies
    if ($null -ne $replyPage) {
        $next = $replyPage.__deferred.uri
        if (-not $next) {
            $entries = @($replyPage)
            if ($null -ne $replyPage.results) { $entries = @($replyPage.results) }
            if ($null -ne $replyPage.value) { $entries = @($replyPage.value) }
            foreach ($entry in $entries) { if ($null -ne $entry) { $children.Add((Expand-CommentReplies $entry)) } }
            $next = $replyPage.'@odata.nextLink'
            if (-not $next) { $next = $replyPage.'odata.nextLink' }
            if (-not $next) { $next = $replyPage.__next }
        }
        while ($next) {
            $page = Invoke-PnPSPRestMethod -Method Get -Url $next
            $entries = @($page.value)
            if ($null -ne $page.d) { $entries = @($page.d.results) }
            foreach ($entry in $entries) { if ($null -ne $entry) { $children.Add((Expand-CommentReplies $entry)) } }
            $next = $page.'@odata.nextLink'
            if (-not $next) { $next = $page.'odata.nextLink' }
            if (-not $next -and $null -ne $page.d) { $next = $page.d.__next }
        }
    }
    $comment | Add-Member -NotePropertyName replies_all -NotePropertyValue $children.ToArray() -Force
    return $comment
}

function Save-SourceFile([int]$ItemId, [string]$Url, [string]$OriginalName, [string]$SourceField) {
    if (-not $Url.StartsWith('/')) { throw "Item $ItemId has a non-server-relative file URL; review it before downloading." }
    $siteUri = [uri]$SiteUrl
    $fileUri = [uri]::new($siteUri, $Url)
    $webPath = $siteUri.AbsolutePath.TrimEnd('/') + '/'
    if ($fileUri.Authority -ne $siteUri.Authority -or -not $fileUri.AbsolutePath.StartsWith($webPath, [StringComparison]::OrdinalIgnoreCase) -or [uri]::UnescapeDataString($Url) -match '(^|/)\.\.?(/|$)') {
        throw "Item $ItemId references a file outside the selected SharePoint web; review it before downloading."
    }
    $folder = Join-Path $root ("files/" + $ItemId)
    New-Item -ItemType Directory -Path $folder -Force | Out-Null
    $safeName = [IO.Path]::GetFileName($OriginalName) -replace '[^\p{L}\p{N}._-]', '_'
    if (-not $safeName) { $safeName = 'file' }
    $physicalName = ('{0:D5}-' -f $files.Count) + $safeName
    Get-PnPFile -Url $Url -Path $folder -FileName $physicalName -AsFile -Force | Out-Null
    $path = Join-Path $folder $physicalName
    $files.Add([pscustomobject]@{ source_id = [string]$ItemId; source_field = $SourceField; source_url = $Url; name = $OriginalName; path = "files/$ItemId/$physicalName"; sha256 = (Get-FileHash -LiteralPath $path -Algorithm SHA256).Hash.ToLowerInvariant(); size = (Get-Item -LiteralPath $path).Length })
}

foreach ($item in $items) {
    $v = $item.FieldValues
    $author = Resolve-Person $v['Author']
    $requester = Resolve-Person $v['Issueloggedby']
    if ($null -eq $requester) { $requester = $author }
    if ($null -eq $requester) { throw "Item $($item.Id) has no resolvable requester." }
    $assignee = Resolve-Person $v['Assignedto']
    $history = ''
    if (-not $SkipComments) {
        # Keep the server's JSON intact too, so attribution and reply structure are not lost.
        $url = "/_api/web/lists(guid'$listId')/items($($item.Id))/comments?`$expand=replies"
        $pages = [System.Collections.Generic.List[object]]::new()
        while ($url) {
            $page = Invoke-PnPSPRestMethod -Method Get -Url $url
            $pages.Add($page)
            $entries = @($page.value)
            if ($null -ne $page.d) { $entries = @($page.d.results) }
            foreach ($comment in $entries) {
                if ($null -ne $comment) { $history += ((Expand-CommentReplies $comment) | ConvertTo-Json -Depth 40 -Compress) + "`n" }
            }
            $url = $page.'@odata.nextLink'
            if (-not $url) { $url = $page.'odata.nextLink' }
            if (-not $url -and $null -ne $page.d) { $url = $page.d.__next }
        }
        $commentsArchive.Add([pscustomobject]@{ source_id = [string]$item.Id; pages = $pages.ToArray() })
    }
    if (-not $SkipFiles) {
        $attachments = Get-PnPProperty -ClientObject $item -Property AttachmentFiles
        foreach ($attachment in $attachments) { Save-SourceFile $item.Id $attachment.ServerRelativeUrl $attachment.FileName 'Attachments' }
        if ($v['Images']) {
            $image = $v['Images'] | ConvertFrom-Json
            if (-not $image.serverRelativeUrl) { throw "Item $($item.Id) has image metadata without a downloadable server-relative path; review it before completing the export." }
            Save-SourceFile $item.Id $image.serverRelativeUrl $image.fileName 'Images'
        }
    }
    $issueSource = $v['IssueSource']
    if ($null -ne $issueSource) { $issueSource = $issueSource.Url }
    $rows.Add([pscustomobject][ordered]@{
        'ID' = [string]$item.Id; 'Title' = $v['Title']; 'Issue description' = $v['Description']
        'Priority' = $v['Priority']; 'Issue Type' = $v['Issue_x0020_Type']; 'Status' = $v['Status']
        'Created' = Format-Date $v['Created']; 'Created By' = $author.Name; 'Created By email' = $author.Email
        'Assigned to' = $assignee.Name; 'Assigned to email' = $assignee.Email
        'Sign-off status' = $v['Sign_x002d_off_x0020_status']; 'Issue logged by' = $requester.Name; 'Requester email' = $requester.Email
        'Resolution' = $v['Resolution']; 'Associated files' = [string]$v['Attachments']; 'Date reported' = Format-Date $v['DateReported']
        'Modified' = Format-Date $v['Modified']; 'Issue source' = $issueSource; 'Images' = [string]$v['Images']
        'Approval status (source)' = [string]$v['_ApprovalStatus']; 'Archived (source)' = [string]$v['Archived']
        'Source comments' = $history; 'SharePoint item URL' = "$($SiteUrl.TrimEnd('/'))/_layouts/15/listform.aspx?PageType=4&ListId=$listId&ID=$($item.Id)"
    })
}
$rows | Export-Csv -LiteralPath (Join-Path $root 'tickets.csv') -NoTypeInformation -Encoding utf8
ConvertTo-Json -InputObject $files.ToArray() -Depth 20 | Set-Content -LiteralPath (Join-Path $root 'files.json') -Encoding utf8
ConvertTo-Json -InputObject $commentsArchive.ToArray() -Depth 50 | Set-Content -LiteralPath (Join-Path $root 'comments.json') -Encoding utf8
$mappingPath = Join-Path $PSScriptRoot '../examples/sharepoint-import/issue-tracker-mapping.json'
$mapping = Get-Content -LiteralPath $mappingPath -Raw | ConvertFrom-Json
$mapping.source = "$(([uri]$SiteUrl).GetLeftPart([System.UriPartial]::Authority).ToLowerInvariant())#list=$listId"
$mapping.year = $Year
$mapping | ConvertTo-Json -Depth 20 | Set-Content -LiteralPath (Join-Path $root 'mapping.json') -Encoding utf8
[pscustomobject]@{ source = $mapping.source; year = $Year; year_basis = 'Created (UTC)'; exported_at = [datetime]::UtcNow.ToString('o'); items = $rows.Count; files = $files.Count; files_skipped = [bool]$SkipFiles; comments_skipped = [bool]$SkipComments;
    csv_sha256 = (Get-FileHash -LiteralPath (Join-Path $root 'tickets.csv') -Algorithm SHA256).Hash.ToLowerInvariant();
    files_manifest_sha256 = (Get-FileHash -LiteralPath (Join-Path $root 'files.json') -Algorithm SHA256).Hash.ToLowerInvariant();
    comments_sha256 = (Get-FileHash -LiteralPath (Join-Path $root 'comments.json') -Algorithm SHA256).Hash.ToLowerInvariant() } |
    ConvertTo-Json | Set-Content -LiteralPath (Join-Path $root 'export-summary.json') -Encoding utf8
Write-Host "Export complete: $($rows.Count) items; $($files.Count) files. Review export-summary.json and mapping.json before importing."
