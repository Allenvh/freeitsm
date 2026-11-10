# Offline stand-in: exercises export transformations, not SharePoint authentication.
function Connect-PnPOnline { param($Url, [switch]$Interactive, $ClientId) }
function Get-PnPList { param($Identity) return [pscustomobject]@{ Id = [guid]'00000000-0000-0000-0000-000000000123' } }
function Get-PnPUser {
    param($Identity)
    $emails = @{ 1 = 'creator@example.test'; 2 = 'requester@example.test'; 3 = 'analyst@example.test' }
    return [pscustomobject]@{ Email = $emails[[int]$Identity]; Title = "Person $Identity" }
}
function Get-PnPListItem {
    param($List, $Query, $PageSize)
    if ($Query -notmatch '2026-01-01T00:00:00Z' -or $Query -notmatch '2027-01-01T00:00:00Z') { throw 'UTC year bounds missing' }
    return [pscustomobject]@{ Id = 101; FieldValues = @{
        Title = 'Example'; Description = 'Description'; Priority = 'High'; Status = 'Completed'; Issue_x0020_Type = 'Incident'
        Author = [pscustomobject]@{ LookupId = 1 }; Issueloggedby = [pscustomobject]@{ LookupId = 2 }; Assignedto = [pscustomobject]@{ LookupId = 3 }
        Created = [datetime]'2026-02-01T10:00:00Z'; Modified = [datetime]'2026-02-02T12:00:00Z'; DateReported = [datetime]'2026-02-01T00:00:00Z'
        Resolution = '<p>Fixed &amp; checked</p>'; Sign_x002d_off_x0020_status = 'Signed off'; _ApprovalStatus = 3; Archived = $true; Attachments = $true
        IssueSource = [pscustomobject]@{ Url = 'https://example.test/original' }
        Images = '{"fileName":"photo.png","serverRelativeUrl":"/sites/test/images/photo.png"}'
    } }
}
function Get-PnPProperty {
    param($ClientObject, $Property)
    return [pscustomobject]@{ FileName = 'report.txt'; ServerRelativeUrl = '/sites/test/attachments/report.txt' }
}
function Get-PnPFile {
    param($Url, $Path, $FileName, [switch]$AsFile, [switch]$Force)
    [IO.File]::WriteAllBytes((Join-Path $Path $FileName), [byte[]](1, 2, 3, 4))
}
function Invoke-PnPSPRestMethod {
    param($Method, $Url)
    if ($Url -eq '/mock-replies-next') { return [pscustomobject]@{ value = @([pscustomobject]@{ id = 9; text = 'Second reply'; createdDate = '2026-02-01T11:30:00Z'; replies = @() }) } }
    return [pscustomobject]@{ value = @([pscustomobject]@{ id = 7; text = 'Original comment'; createdDate = '2026-02-01T10:30:00Z'; author = [pscustomobject]@{ email = 'requester@example.test' }; replies = [pscustomobject]@{ value = @([pscustomobject]@{ id = 8; text = 'First reply'; createdDate = '2026-02-01T11:00:00Z'; replies = @() }); '@odata.nextLink' = '/mock-replies-next' } }) }
}
Export-ModuleMember -Function *
