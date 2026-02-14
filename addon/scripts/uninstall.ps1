param(
	[string]$GrocyConfigPath = ''
)

$ErrorActionPreference = 'Stop'

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$addonRoot = (Resolve-Path (Join-Path $scriptDir '..')).Path

if ([string]::IsNullOrWhiteSpace($GrocyConfigPath))
{
	$autoConfig = Resolve-Path (Join-Path $addonRoot '..\config') -ErrorAction SilentlyContinue
	if ($autoConfig)
	{
		$GrocyConfigPath = $autoConfig.Path
	}
	else
	{
		throw 'GrocyConfigPath manquant et ../config introuvable.'
	}
}

$dataDir = Join-Path $GrocyConfigPath 'data'
$targetFile = Join-Path $dataDir 'custom_js.html'
$stateFile = Join-Path $dataDir 'grocy-addon-state.json'

if (-not (Test-Path $dataDir))
{
	throw "Dossier data introuvable: $dataDir"
}

$restoreFile = $null
if (Test-Path $stateFile)
{
	try
	{
		$state = Get-Content $stateFile -Raw | ConvertFrom-Json
		if ($state.backup_file -and (Test-Path $state.backup_file))
		{
			$restoreFile = $state.backup_file
		}
	}
	catch
	{
		Write-Warning "Etat addon illisible: $stateFile"
	}
}

if (-not $restoreFile)
{
	$latestBackup = Get-ChildItem $dataDir -Filter 'custom_js.html.bak_addon_*' -ErrorAction SilentlyContinue |
		Sort-Object LastWriteTime -Descending |
		Select-Object -First 1
	if ($latestBackup)
	{
		$restoreFile = $latestBackup.FullName
	}
}

if (-not $restoreFile)
{
	throw 'Aucun backup addon trouve pour restauration.'
}

Copy-Item $restoreFile $targetFile -Force

if (Test-Path $stateFile)
{
	Remove-Item $stateFile -Force
}

Write-Host "Restaure depuis: $restoreFile"
Write-Host "Fichier actif: $targetFile"
