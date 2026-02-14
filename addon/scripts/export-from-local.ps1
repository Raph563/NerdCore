param(
	[string]$GrocyConfigPath = ''
)

$ErrorActionPreference = 'Stop'

$scriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$addonRoot = (Resolve-Path (Join-Path $scriptDir '..')).Path
$destFile = Join-Path $addonRoot 'dist\custom_js.html'

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

$sourceFile = Join-Path (Join-Path $GrocyConfigPath 'data') 'custom_js.html'
if (-not (Test-Path $sourceFile))
{
	throw "Source introuvable: $sourceFile"
}

Copy-Item $sourceFile $destFile -Force
Write-Host "Export OK: $sourceFile -> $destFile"
