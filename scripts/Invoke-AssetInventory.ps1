<#
.SYNOPSIS
    Collects hardware, software, and system inventory from the local machine and
    posts it to FreeITSM.

.DESCRIPTION
    Gathers hostname, manufacturer, model, CPU, memory, OS, BIOS, disk, network,
    GPU, TPM, BitLocker, and installed software information then sends the data as
    a JSON payload to the FreeITSM asset inventory API.

    Run as Administrator for full results (BitLocker, TPM, and some disk details
    require elevation).

.PARAMETER ApiUrl
    The base URL of your FreeITSM instance (e.g. https://itsm.yourcompany.com).

.PARAMETER ApiKey
    API key for authentication.

.PARAMETER OutputFile
    Optional path to save the JSON output to a file instead of (or in addition to)
    posting to the API.

.PARAMETER CertificateThumbprint
    The SHA-1 fingerprint of the certificate the server is expected to present.
    The inventory is only sent if the server presents exactly that certificate.

    Use this when FreeITSM is served over an internal name with a self-signed
    certificate: it works without installing anything, and unlike
    -SkipCertificateCheck it still refuses an impostor server.

    Read it off the server with:
        Get-ChildItem Cert:\LocalMachine\My | Format-List Subject, Thumbprint
    or from a browser's certificate details.

.PARAMETER SkipCertificateCheck
    Accept the server's HTTPS certificate without validating it at all.

    Lab use only. It disables the protection against an impostor server, and
    since the API key travels in a request header, anyone able to intercept the
    connection can read it. Prefer -CertificateThumbprint, or install the
    issuing CA into the machine's Trusted Root store.

.EXAMPLE
    .\Invoke-AssetInventory.ps1 -ApiUrl "https://itsm.yourcompany.com" -ApiKey "abc123"

.EXAMPLE
    .\Invoke-AssetInventory.ps1 -OutputFile "C:\Temp\asset.json"

.EXAMPLE
    # Internal server with a self-signed certificate - pinned, safe for production
    .\Invoke-AssetInventory.ps1 -ApiUrl "https://freeitsm.internal" -ApiKey "abc123" -CertificateThumbprint "A1B2C3D4E5F60718293A4B5C6D7E8F9012345678"

.EXAMPLE
    # Same, but accepting any certificate - lab use only
    .\Invoke-AssetInventory.ps1 -ApiUrl "https://freeitsm.internal" -ApiKey "abc123" -SkipCertificateCheck
#>

[CmdletBinding()]
param(
    [string]$ApiUrl,
    [string]$ApiKey,
    [string]$OutputFile,
    [string]$CertificateThumbprint,
    [switch]$SkipCertificateCheck
)

# Require at least one output destination
if (-not $ApiUrl -and -not $OutputFile) {
    Write-Host ""
    Write-Host "FreeITSM Asset Inventory Collector" -ForegroundColor Cyan
    Write-Host "-----------------------------------" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "Usage:" -ForegroundColor Yellow
    Write-Host "  .\Invoke-AssetInventory.ps1 -ApiUrl `"https://itsm.yourcompany.com`" -ApiKey `"your-key`""
    Write-Host "  .\Invoke-AssetInventory.ps1 -OutputFile `"C:\Temp\asset.json`""
    Write-Host "  .\Invoke-AssetInventory.ps1 -ApiUrl `"https://itsm.yourcompany.com`" -ApiKey `"your-key`" -OutputFile `"C:\Temp\asset.json`""
    Write-Host ""
    Write-Host ""
    Write-Host "If your FreeITSM uses a self-signed certificate, add either:" -ForegroundColor Yellow
    Write-Host "  -CertificateThumbprint `"A1B2...`"   accept only that certificate (recommended)"
    Write-Host "  -SkipCertificateCheck              accept any certificate (lab use only)"
    Write-Host ""
    Write-Host "Run as Administrator for full results (BitLocker, TPM)." -ForegroundColor Gray
    exit 1
}

# Validate the certificate arguments before collecting anything - inventory takes
# the best part of a minute, and a typo in a thumbprint shouldn't cost that.
# Accept it in any of the forms people copy it in: with spaces (certmgr), with
# colons (openssl), or lower case.
$pinnedThumbprint = $null
if ($CertificateThumbprint) {
    $pinnedThumbprint = ($CertificateThumbprint -replace '[^0-9A-Fa-f]', '').ToUpperInvariant()
    if ($pinnedThumbprint.Length -ne 40) {
        Write-Host "Error: -CertificateThumbprint should be a 40-character SHA-1 fingerprint." -ForegroundColor Red
        Write-Host "  Got $($pinnedThumbprint.Length) usable characters from '$CertificateThumbprint'." -ForegroundColor Red
        exit 1
    }
}

if ($pinnedThumbprint -and $SkipCertificateCheck) {
    Write-Host "Note: -CertificateThumbprint given, so -SkipCertificateCheck is ignored." -ForegroundColor Yellow
    $SkipCertificateCheck = $false
}

$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

Write-Host "Collecting inventory data..." -ForegroundColor Cyan
if (-not $isAdmin) {
    Write-Host "  Note: Not running as Administrator. BitLocker and TPM data may be limited." -ForegroundColor Yellow
}

# ─── Core system info ───────────────────────────────────────────────────────────

$cs   = Get-CimInstance Win32_ComputerSystem
$os   = Get-CimInstance Win32_OperatingSystem
$bios = Get-CimInstance Win32_BIOS
$cpu  = Get-CimInstance Win32_Processor | Select-Object -First 1

# Feature release from registry (e.g. "23H2", "24H2")
$ntKey          = 'HKLM:\SOFTWARE\Microsoft\Windows NT\CurrentVersion'
$featureRelease = (Get-ItemProperty -Path $ntKey -Name DisplayVersion -ErrorAction SilentlyContinue).DisplayVersion
$ubr            = (Get-ItemProperty -Path $ntKey -Name UBR -ErrorAction SilentlyContinue).UBR
$buildNumber    = if ($ubr) { "$($os.BuildNumber).$ubr" } else { "$($os.BuildNumber)" }

Write-Host "  System info collected" -ForegroundColor Green

# ─── What each section came to ──────────────────────────────────────────────────
# FreeITSM keeps, per section, whether this report collected it, found it empty or
# could not collect it, so an asset can say how fresh each list is (report_version 2).
# A section named here keeps its last good list on the server instead of being replaced.
#
# TRAP: Windows answering "nothing" for volumes, drives, network adapters or Device
# Manager is treated as a FAILED collection, not an empty one. Every PC that can reach
# FreeITSM has at least one of each, so an empty answer means WMI did not answer - and
# reporting it as empty would wipe the machine's list (the 226-row Device Manager wipe).
# Installed software is the one list that is trusted when it comes back empty.
$collectionFailures = [ordered]@{}

function Get-Section {
    param([string]$Name, [string]$Noun, [scriptblock]$Collect, [switch]$EmptyIsFailure)
    try {
        $items = @(& $Collect)
        if ($EmptyIsFailure -and $items.Count -eq 0) {
            $script:collectionFailures[$Name] = "Windows returned no $Noun - treated as a failed collection, the last list is kept."
        }
        return ,$items
    } catch {
        $script:collectionFailures[$Name] = "Could not read $($Noun): $($_.Exception.Message)"
        Write-Host "  $Noun skipped: $($_.Exception.Message)" -ForegroundColor Yellow
        return ,@()
    }
}

# ─── Disks ───────────────────────────────────────────────────────────────────────

$logicalDisks = Get-Section -Name 'disks' -Noun 'volumes' -EmptyIsFailure -Collect {
    Get-CimInstance Win32_LogicalDisk -Filter "DriveType=3" -ErrorAction Stop | ForEach-Object {
        @{
            drive        = $_.DeviceID
            label        = $_.VolumeName
            file_system  = $_.FileSystem
            size_bytes   = $_.Size
            free_bytes   = $_.FreeSpace
            used_percent = if ($_.Size -and $_.Size -gt 0) {
                [math]::Round((($_.Size - $_.FreeSpace) / $_.Size) * 100, 1)
            } else { 0 }
        }
    }
}

$physicalDisks = Get-Section -Name 'physical_disks' -Noun 'physical drives' -EmptyIsFailure -Collect {
    Get-CimInstance Win32_DiskDrive -ErrorAction Stop | ForEach-Object {
        @{
            model      = $_.Model
            serial     = if ($_.SerialNumber) { $_.SerialNumber.Trim() } else { $null }
            size_bytes = $_.Size
            media_type = $_.MediaType
            interface  = $_.InterfaceType
        }
    }
}

Write-Host "  Disk info collected ($($logicalDisks.Count) logical, $($physicalDisks.Count) physical)" -ForegroundColor Green

# ─── Network adapters ────────────────────────────────────────────────────────────

$networkAdapters = Get-Section -Name 'network_adapters' -Noun 'network adapters' -EmptyIsFailure -Collect {
    Get-CimInstance Win32_NetworkAdapterConfiguration -Filter "IPEnabled=True" -ErrorAction Stop | ForEach-Object {
        @{
            name         = $_.Description
            mac_address  = $_.MACAddress
            ip_addresses = @($_.IPAddress)
            subnet_masks = @($_.IPSubnet)
            gateway      = @($_.DefaultIPGateway | Where-Object { $_ })
            dhcp_enabled = $_.DHCPEnabled
            dns_servers  = @($_.DNSServerSearchOrder | Where-Object { $_ })
        }
    }
}

Write-Host "  Network info collected ($($networkAdapters.Count) adapters)" -ForegroundColor Green

# ─── GPU ─────────────────────────────────────────────────────────────────────────

$gpus = @(Get-CimInstance Win32_VideoController | ForEach-Object {
    @{
        name            = $_.Name
        driver_version  = $_.DriverVersion
        vram_bytes      = $_.AdapterRAM
        resolution      = "$($_.CurrentHorizontalResolution)x$($_.CurrentVerticalResolution)"
    }
})

Write-Host "  GPU info collected ($($gpus.Count) adapters)" -ForegroundColor Green

# ─── TPM ─────────────────────────────────────────────────────────────────────────

$tpm = $null
try {
    $tpmData = Get-CimInstance -Namespace "root\cimv2\Security\MicrosoftTpm" -ClassName Win32_Tpm -ErrorAction Stop
    if ($tpmData) {
        $tpm = @{
            version        = $tpmData.SpecVersion
            manufacturer   = $tpmData.ManufacturerIdTxt
            is_enabled     = $tpmData.IsEnabled_InitialValue
            is_activated   = $tpmData.IsActivated_InitialValue
        }
        Write-Host "  TPM info collected (v$($tpmData.SpecVersion))" -ForegroundColor Green
    }
} catch {
    Write-Host "  TPM info skipped (requires elevation or not present)" -ForegroundColor Gray
}

# ─── BitLocker ───────────────────────────────────────────────────────────────────

$bitlocker = @()
if ($isAdmin) {
    try {
        $bitlocker = @(Get-BitLockerVolume -ErrorAction Stop | ForEach-Object {
            @{
                drive             = $_.MountPoint
                protection_status = $_.ProtectionStatus.ToString()
                encryption_method = $_.EncryptionMethod.ToString()
                volume_status     = $_.VolumeStatus.ToString()
                lock_status       = $_.LockStatus.ToString()
            }
        })
        Write-Host "  BitLocker info collected ($($bitlocker.Count) volumes)" -ForegroundColor Green
    } catch {
        Write-Host "  BitLocker info skipped (not available)" -ForegroundColor Gray
    }
} else {
    Write-Host "  BitLocker info skipped (requires elevation)" -ForegroundColor Gray
}

# ─── Device Manager ─────────────────────────────────────────────────────────────

$devices = @()
try {
    # Get present PnP devices (matches what Device Manager shows)
    $pnpDevices = Get-PnpDevice -PresentOnly -Status OK, Error, Degraded -ErrorAction Stop |
        Where-Object { $_.Class -and $_.FriendlyName }

    # Build a lookup of driver info from Win32_PnPSignedDriver
    $driverLookup = @{}
    Get-CimInstance Win32_PnPSignedDriver -ErrorAction SilentlyContinue | ForEach-Object {
        if ($_.DeviceID) {
            $driverLookup[$_.DeviceID] = $_
        }
    }

    foreach ($dev in $pnpDevices) {
        $driver = $driverLookup[$dev.InstanceId]
        $devices += @{
            device_class   = $dev.Class
            device_name    = $dev.FriendlyName
            status         = $dev.Status
            manufacturer   = if ($driver) { $driver.DriverProviderName } else { $null }
            driver_version = if ($driver) { $driver.DriverVersion } else { $null }
            driver_date    = if ($driver -and $driver.DriverDate) {
                $driver.DriverDate.ToString("yyyy-MM-dd")
            } else { $null }
        }
    }
    Write-Host "  Device Manager info collected ($($devices.Count) devices)" -ForegroundColor Green
    if ($devices.Count -eq 0) {
        $collectionFailures['devices'] = "Windows returned no devices - treated as a failed collection, the last list is kept."
    }
} catch {
    $collectionFailures['devices'] = "Could not read Device Manager: $($_.Exception.Message)"
    Write-Host "  Device Manager info skipped: $_" -ForegroundColor Yellow
}

# ─── Installed software ─────────────────────────────────────────────────────────

$regPaths = @(
    "HKLM:\SOFTWARE\Microsoft\Windows\CurrentVersion\Uninstall\*"
    "HKLM:\SOFTWARE\WOW6432Node\Microsoft\Windows\CurrentVersion\Uninstall\*"
)

$software = Get-Section -Name 'software' -Noun 'installed software' -Collect {
    $regPaths | ForEach-Object {
        Get-ItemProperty -Path $_ -ErrorAction SilentlyContinue
    } | Where-Object {
        $_.DisplayName -and $_.DisplayName.Trim() -ne ''
    } | Sort-Object DisplayName -Unique | ForEach-Object {
        # SystemComponent=1 or ParentKeyName set = hidden from Add/Remove Programs
        $isComponent = ($_.SystemComponent -eq 1) -or ($_.ParentKeyName -and $_.ParentKeyName -ne '')

        @{
            display_name      = $_.DisplayName
            publisher         = $_.Publisher
            display_version   = $_.DisplayVersion
            install_date      = $_.InstallDate
            install_location  = $_.InstallLocation
            uninstall_string  = $_.UninstallString
            estimated_size    = if ($_.EstimatedSize) { "$($_.EstimatedSize)" } else { $null }
            system_component  = $isComponent
        }
    }
}

$appCount = ($software | Where-Object { -not $_.system_component }).Count
$componentCount = ($software | Where-Object { $_.system_component }).Count
Write-Host "  Software inventory collected ($appCount applications, $componentCount system components)" -ForegroundColor Green

# ─── Logged-in user ──────────────────────────────────────────────────────────────

$loggedInUser = $null
try {
    $explorerProc = Get-CimInstance Win32_Process -Filter "Name='explorer.exe'" -ErrorAction Stop | Select-Object -First 1
    if ($explorerProc) {
        $owner = Invoke-CimMethod -InputObject $explorerProc -MethodName GetOwner -ErrorAction Stop
        $loggedInUser = if ($owner.Domain) { "$($owner.Domain)\$($owner.User)" } else { $owner.User }
    }
} catch {
    $loggedInUser = $env:USERNAME
}

# ─── Last boot time ─────────────────────────────────────────────────────────────

$lastBoot = $os.LastBootUpTime.ToUniversalTime().ToString("yyyy-MM-dd HH:mm:ss")
$uptimeDays = [math]::Round(((Get-Date) - $os.LastBootUpTime).TotalDays, 1)

Write-Host "  Last boot: $lastBoot UTC (uptime: $uptimeDays days)" -ForegroundColor Green

# ─── Build the payload ───────────────────────────────────────────────────────────

$payload = [ordered]@{
    # 2 = every section is sent, an empty list is a real "none", and the sections that
    # could not be collected are named in collection_failures. FreeITSM before 3.15.0
    # ignores both and treats an empty list as "did not find out", so this is safe to
    # roll out before or after the server is upgraded.
    report_version      = 2
    collection_failures = $collectionFailures

    # Core asset fields (match assets table schema)
    hostname         = $env:COMPUTERNAME
    manufacturer     = $cs.Manufacturer
    model            = $cs.Model
    memory           = [long]$cs.TotalPhysicalMemory
    service_tag      = $bios.SerialNumber
    operating_system = $os.Caption -replace "Microsoft ", ""
    feature_release  = $featureRelease
    build_number     = $buildNumber
    cpu_name         = $cpu.Name
    speed            = [long]($cpu.MaxClockSpeed * 1000000)
    bios_version     = $bios.SMBIOSBIOSVersion
    domain           = $cs.Domain

    # Extended info
    logged_in_user   = $loggedInUser
    last_boot_utc    = $lastBoot
    uptime_days      = $uptimeDays

    # Disks
    disks = [ordered]@{
        logical  = $logicalDisks
        physical = $physicalDisks
    }

    # Network
    network_adapters = $networkAdapters

    # GPU
    gpus = $gpus

    # Security
    tpm              = $tpm
    bitlocker        = $bitlocker

    # Device Manager
    devices          = $devices

    # Software inventory
    software         = $software
}

$json = $payload | ConvertTo-Json -Depth 5 -Compress:$false

# ─── Output ──────────────────────────────────────────────────────────────────────

if ($OutputFile) {
    $json | Out-File -FilePath $OutputFile -Encoding UTF8 -Force
    Write-Host ""
    Write-Host "JSON saved to: $OutputFile" -ForegroundColor Green
}

if ($ApiUrl) {
    $url = "$($ApiUrl.TrimEnd('/'))/api/external/system-info/submit/"
    $headers = @{ 'Content-Type' = 'application/json'; 'Authorization' = '' }
    if ($ApiKey) { $headers['Authorization'] = $ApiKey }

    # ─── TLS negotiation ─────────────────────────────────────────────────────────
    # Windows PowerShell 5.1 (.NET Framework) leaves SecurityProtocol at
    # "SystemDefault", which on older builds still offers TLS 1.0 and gets refused
    # by a hardened server, so ask for 1.2 explicitly there.
    #
    # PowerShell 7 (.NET Core) is deliberately left alone: it negotiates the best
    # protocol the OS supports, including TLS 1.3, and pinning it here would be a
    # downgrade. Note .NET Framework's Tls13 enum value is not reliably supported
    # by SCHANNEL, so it is not set even where the enum exists.
    if ($PSVersionTable.PSVersion.Major -lt 6) {
        [Net.ServicePointManager]::SecurityProtocol = [Net.SecurityProtocolType]::Tls12
    }

    # ─── Certificate validation ──────────────────────────────────────────────────
    # Three modes, in descending order of safety:
    #
    #   default                  - normal validation against the machine's trust store
    #   -CertificateThumbprint   - accept only the one certificate with that fingerprint
    #   -SkipCertificateCheck    - accept anything (lab use only)
    #
    # PowerShell 7 has a per-request -SkipCertificateCheck switch. 5.1 does not, so
    # there the only lever is the process-wide validation callback - saved here and
    # restored in the finally block so we don't leave the session trusting anything.
    $restoreCallback   = $false
    $originalCallback  = $null
    $requestArgs       = @{}
    $isCoreEdition     = $PSVersionTable.PSVersion.Major -ge 6

    if ($pinnedThumbprint) {
        Write-Host ""
        Write-Host "Pinned to certificate $pinnedThumbprint" -ForegroundColor Cyan

        if ($isCoreEdition) {
            # .NET Core ignores ServicePointManager, and Invoke-RestMethod exposes no
            # per-request validation callback, so verify the certificate ourselves in
            # a preflight handshake and only then skip the built-in check.
            $uri = [Uri]$url
            try {
                $tcp = New-Object System.Net.Sockets.TcpClient($uri.Host, $uri.Port)
                $ssl = New-Object System.Net.Security.SslStream($tcp.GetStream(), $false, { $true })
                $ssl.AuthenticateAsClient($uri.Host)
                $presented = (New-Object System.Security.Cryptography.X509Certificates.X509Certificate2($ssl.RemoteCertificate)).Thumbprint.ToUpperInvariant()
                $ssl.Dispose(); $tcp.Close()
            } catch {
                Write-Host "Error: could not read the server's certificate: $_" -ForegroundColor Red
                exit 1
            }

            if ($presented -ne $pinnedThumbprint) {
                Write-Host "Error: certificate mismatch - refusing to send." -ForegroundColor Red
                Write-Host "  expected: $pinnedThumbprint" -ForegroundColor Red
                Write-Host "  server:   $presented" -ForegroundColor Red
                exit 1
            }
            $requestArgs['SkipCertificateCheck'] = $true
        } else {
            # .NET Framework: the callback runs for the real connection, so the
            # comparison happens on the certificate actually being negotiated.
            $originalCallback = [Net.ServicePointManager]::ServerCertificateValidationCallback
            [Net.ServicePointManager]::ServerCertificateValidationCallback = {
                param($sender, $certificate, $chain, $sslPolicyErrors)
                $presented = [System.Security.Cryptography.X509Certificates.X509Certificate2]$certificate
                $presented.Thumbprint.ToUpperInvariant() -eq $pinnedThumbprint
            }.GetNewClosure()
            $restoreCallback = $true
        }
    }
    elseif ($SkipCertificateCheck) {
        Write-Host ""
        Write-Host "WARNING: certificate validation is disabled for this run." -ForegroundColor Yellow
        Write-Host "  Anyone able to intercept this connection can read the API key." -ForegroundColor Yellow
        Write-Host "  Use -CertificateThumbprint instead outside a lab." -ForegroundColor Yellow

        if ($isCoreEdition) {
            $requestArgs['SkipCertificateCheck'] = $true
        } else {
            $originalCallback = [Net.ServicePointManager]::ServerCertificateValidationCallback
            [Net.ServicePointManager]::ServerCertificateValidationCallback = { $true }
            $restoreCallback = $true
        }
    }

    Write-Host ""
    Write-Host "Posting to $url ..." -ForegroundColor Cyan

    $postFailed = $false

    try {
        try {
            $response = Invoke-RestMethod -Uri $url -Method POST -Headers $headers -Body ([System.Text.Encoding]::UTF8.GetBytes($json)) -ContentType 'application/json; charset=utf-8' @requestArgs
            Write-Host "Success!" -ForegroundColor Green
            Write-Host ($response | ConvertTo-Json -Compress) -ForegroundColor Gray
        } catch {
            Write-Host "Error posting to API: $_" -ForegroundColor Red

            # A rejected certificate is the most common failure against an internal
            # FreeITSM, and the raw .NET message doesn't say what to do about it.
            $trustError = ($_.Exception.Message -match 'trust relationship|SSL/TLS|SSL connection could not be established') -or
                          ($_.Exception.InnerException -and $_.Exception.InnerException.Message -match 'certificate')

            if ($trustError -and -not $SkipCertificateCheck -and -not $pinnedThumbprint) {
                Write-Host ""
                Write-Host "This machine does not trust the HTTPS certificate presented by $ApiUrl." -ForegroundColor Yellow
                Write-Host "That is normal for a FreeITSM served over an internal name with a" -ForegroundColor Yellow
                Write-Host "self-signed certificate, or one from a private CA." -ForegroundColor Yellow
                Write-Host ""
                Write-Host "Pick one of these, best first:" -ForegroundColor Yellow
                Write-Host ""
                Write-Host "  1. Install the issuing CA certificate into this machine's Trusted Root" -ForegroundColor Yellow
                Write-Host "     store - by Group Policy if you have a domain. Nothing to change here." -ForegroundColor Yellow
                Write-Host ""
                Write-Host "  2. Pin the server's certificate, if there is no internal CA:" -ForegroundColor Yellow
                Write-Host "     .\Invoke-AssetInventory.ps1 -ApiUrl `"$ApiUrl`" -ApiKey `"your-key`" -CertificateThumbprint `"<thumbprint>`"" -ForegroundColor Gray
                Write-Host ""
                Write-Host "  3. Skip the check entirely - lab use only, exposes the API key:" -ForegroundColor Yellow
                Write-Host "     .\Invoke-AssetInventory.ps1 -ApiUrl `"$ApiUrl`" -ApiKey `"your-key`" -SkipCertificateCheck" -ForegroundColor Gray
            }
            elseif ($trustError -and $pinnedThumbprint) {
                Write-Host ""
                Write-Host "The server did not present the pinned certificate $pinnedThumbprint." -ForegroundColor Yellow
                Write-Host "Check the thumbprint, or whether the server's certificate has been renewed." -ForegroundColor Yellow
            }

            if ($_.Exception.Response) {
                $reader = New-Object System.IO.StreamReader($_.Exception.Response.GetResponseStream())
                Write-Host "Response: $($reader.ReadToEnd())" -ForegroundColor Red
            }
            $postFailed = $true
        }

        # Post device manager data separately - every time, so a failed or empty
        # collection is recorded as such rather than looking like no report at all.
        if (-not $postFailed) {
            $dmUrl = "$($ApiUrl.TrimEnd('/'))/api/external/device-manager/submit/"
            $dmFailures = [ordered]@{}
            if ($collectionFailures.Contains('devices')) { $dmFailures['devices'] = $collectionFailures['devices'] }
            $dmPayload = [ordered]@{
                report_version      = 2
                collection_failures = $dmFailures
                hostname            = $env:COMPUTERNAME
                devices             = $devices
            }
            $dmJson = $dmPayload | ConvertTo-Json -Depth 5 -Compress:$false

            Write-Host ""
            Write-Host "Posting device manager data to $dmUrl ..." -ForegroundColor Cyan
            try {
                $dmResponse = Invoke-RestMethod -Uri $dmUrl -Method POST -Headers $headers -Body ([System.Text.Encoding]::UTF8.GetBytes($dmJson)) -ContentType 'application/json; charset=utf-8' @requestArgs
                Write-Host "  Devices synced: $($dmResponse.devices_synced)" -ForegroundColor Green
            } catch {
                Write-Host "  Warning: Device manager POST failed: $_" -ForegroundColor Yellow
            }
        }
    } finally {
        if ($restoreCallback) {
            [Net.ServicePointManager]::ServerCertificateValidationCallback = $originalCallback
        }
    }

    if ($postFailed) { exit 1 }
}

Write-Host ""
Write-Host "Done. Collected: $($software.Count) apps, $($logicalDisks.Count) drives, $($networkAdapters.Count) NICs, $($gpus.Count) GPUs, $($devices.Count) devices" -ForegroundColor Cyan
if ($collectionFailures.Count -gt 0) {
    Write-Host "Not collected this time (FreeITSM keeps the last good list):" -ForegroundColor Yellow
    foreach ($name in $collectionFailures.Keys) { Write-Host "  $($name): $($collectionFailures[$name])" -ForegroundColor Yellow }
}
