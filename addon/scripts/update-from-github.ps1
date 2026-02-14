param(
	[string]$Repository = 'Raph563/Grocy',
	[string]$ReleaseTag = '',
	[string]$GrocyConfigPath = '',
	[switch]$NoBackup,
	[switch]$AllowPrerelease
)

$ErrorActionPreference = 'Stop'

function Resolve-RepositoryParts {
	param([string]$RepositoryValue)
	$text = [string]$RepositoryValue
	$text = $text.Trim()
	if ([string]::IsNullOrWhiteSpace($text))
	{
		throw 'Repository is required (expected owner/repo).'
	}

	$text = $text -replace '^https?://github\.com/', ''
	$text = $text -replace '\.git$', ''
	$text = $text.Trim('/')

	$parts = $text.Split('/', [System.StringSplitOptions]::RemoveEmptyEntries)
	if ($parts.Count -lt 2)
	{
		throw "Invalid repository: $RepositoryValue (expected owner/repo)"
	}

	$owner = $parts[0]
	$name = $parts[1]
	if ($owner -notmatch '^[A-Za-z0-9_.-]+$' -or $name -notmatch '^[A-Za-z0-9_.-]+$')
	{
		throw "Invalid repository: $RepositoryValue (expected owner/repo)"
	}

	return [ordered]@{
		Owner = $owner
		Name = $name
		Repository = "$owner/$name"
	}
}

function Resolve-GrocyConfigPath {
	param(
		[string]$ConfigPathInput,
		[string]$ScriptDir
	)

	if ([string]::IsNullOrWhiteSpace($ConfigPathInput))
	{
		$autoConfig = Resolve-Path (Join-Path $ScriptDir '..\..\config') -ErrorAction SilentlyContinue
		if ($autoConfig)
		{
			return $autoConfig.Path
		}
		throw 'GrocyConfigPath missing and ../config not found.'
	}

	$resolved = Resolve-Path $ConfigPathInput -ErrorAction SilentlyContinue
	if ($resolved)
	{
		return $resolved.Path
	}
	if (Test-Path $ConfigPathInput)
	{
		return (Get-Item $ConfigPathInput).FullName
	}
	throw "Grocy config path not found: $ConfigPathInput"
}

function Get-ReleasePayload {
	param(
		[string]$ApiBase,
		[string]$RequestedTag,
		[switch]$UsePrerelease
	)

	$headers = @{
		Accept = 'application/vnd.github+json'
		'X-GitHub-Api-Version' = '2022-11-28'
	}

	if (-not [string]::IsNullOrWhiteSpace($RequestedTag))
	{
		$tag = $RequestedTag.Trim()
		if ($tag -notmatch '^v')
		{
			$tag = "v$tag"
		}
		$uri = "$ApiBase/releases/tags/$([Uri]::EscapeDataString($tag))"
		return Invoke-RestMethod -Uri $uri -Headers $headers -Method Get
	}

	if ($UsePrerelease)
	{
		$rows = Invoke-RestMethod -Uri "$ApiBase/releases?per_page=20" -Headers $headers -Method Get
		if ($rows -isnot [System.Array])
		{
			$rows = @($rows)
		}
		$release = $rows | Where-Object { $_.draft -ne $true } | Select-Object -First 1
		if (-not $release)
		{
			throw 'No GitHub release found.'
		}
		return $release
	}

	try
	{
		return Invoke-RestMethod -Uri "$ApiBase/releases/latest" -Headers $headers -Method Get
	}
	catch
	{
		$statusCode = $null
		try { $statusCode = $_.Exception.Response.StatusCode.value__ } catch {}
		if ($statusCode -eq 404)
		{
			throw 'No GitHub release found.'
		}
		throw
	}
}

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$grocyConfigResolved = Resolve-GrocyConfigPath -ConfigPathInput $GrocyConfigPath -ScriptDir $scriptDir
$dataDir = Join-Path $grocyConfigResolved 'data'
$targetFile = Join-Path $dataDir 'custom_js.html'
$stateFile = Join-Path $dataDir 'grocy-addon-state.json'

if (-not (Test-Path $dataDir))
{
	throw "Data directory not found: $dataDir"
}

$repo = Resolve-RepositoryParts -RepositoryValue $Repository
$apiBase = "https://api.github.com/repos/$($repo.Owner)/$($repo.Name)"

$tempRoot = Join-Path ([System.IO.Path]::GetTempPath()) ("grocy-addon-update-{0}" -f ([Guid]::NewGuid().ToString('N')))
New-Item -ItemType Directory -Path $tempRoot | Out-Null

try
{
	$release = Get-ReleasePayload -ApiBase $apiBase -RequestedTag $ReleaseTag -UsePrerelease:$AllowPrerelease
	if (-not $release)
	{
		throw 'No GitHub release found.'
	}

	$assets = @($release.assets)
	$asset = $assets | Where-Object { $_.name -match '^grocy-addon-v.+\.zip$' } | Select-Object -First 1
	if (-not $asset)
	{
		$asset = $assets | Where-Object { $_.name -match '\.zip$' } | Select-Object -First 1
	}
	if (-not $asset)
	{
		throw "No ZIP asset found in release $($release.tag_name)."
	}

	$archiveFile = Join-Path $tempRoot ([string]$asset.name)
	Invoke-WebRequest -Uri ([string]$asset.browser_download_url) -OutFile $archiveFile -Headers @{ Accept = 'application/octet-stream' }

	$extractDir = Join-Path $tempRoot 'extract'
	Expand-Archive -Path $archiveFile -DestinationPath $extractDir -Force

	$addonFile = Join-Path $extractDir 'addon\dist\custom_js.html'
	if (-not (Test-Path $addonFile))
	{
		$fallback = Get-ChildItem -Path $extractDir -Recurse -Filter custom_js.html -File |
			Where-Object { $_.FullName -match 'addon[\\/]+dist[\\/]+custom_js\.html$' } |
			Select-Object -First 1
		if ($fallback)
		{
			$addonFile = $fallback.FullName
		}
	}

	if (-not (Test-Path $addonFile))
	{
		throw "custom_js.html not found in release archive ($($asset.name))."
	}

	$backupFile = $null
	if ((Test-Path $targetFile) -and (-not $NoBackup))
	{
		$ts = Get-Date -Format 'yyyyMMdd_HHmmss'
		$backupFile = Join-Path $dataDir ("custom_js.html.bak_addon_{0}" -f $ts)
		Copy-Item $targetFile $backupFile -Force
		Write-Host "Backup created: $backupFile"
	}

	Copy-Item $addonFile $targetFile -Force

	$state = [ordered]@{
		installed_at = (Get-Date).ToString('o')
		installed_by = 'update-from-github.ps1'
		repository = $repo.Repository
		release_tag = [string]$release.tag_name
		release_url = [string]$release.html_url
		asset_name = [string]$asset.name
		asset_url = [string]$asset.browser_download_url
		target_file = $targetFile
		backup_file = $backupFile
	}
	$state | ConvertTo-Json | Set-Content -Encoding UTF8 $stateFile

	Write-Host "Addon updated: $targetFile"
	Write-Host "Release: $($release.tag_name)"
	Write-Host "State: $stateFile"
}
finally
{
	Remove-Item $tempRoot -Recurse -Force -ErrorAction SilentlyContinue
}
