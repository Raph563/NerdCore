param(
  [Parameter(Mandatory = $true)]
  [string]$Version,
  [switch]$NoPush,
  [switch]$CreateGithubRelease,
  [switch]$Prerelease
)

$ErrorActionPreference = 'Stop'

if ($Version -notmatch '^[0-9]+\.[0-9]+\.[0-9]+([.-][0-9A-Za-z.-]+)?$') {
  throw "Invalid version format: $Version"
}

$tag = "v$Version"
$dirty = git status --porcelain
if (-not [string]::IsNullOrWhiteSpace($dirty)) {
  throw 'Working tree is not clean. Commit or stash changes before releasing.'
}

$tagExists = git tag --list $tag
if (-not [string]::IsNullOrWhiteSpace($tagExists)) {
  throw "Tag already exists: $tag"
}

git tag -a $tag -m "Release $tag"

if (-not $NoPush) {
  $branch = git rev-parse --abbrev-ref HEAD
  git push origin $branch
  git push origin $tag
}

if ($CreateGithubRelease) {
  $gh = Get-Command gh -ErrorAction SilentlyContinue
  if (-not $gh) {
    throw 'GitHub CLI (gh) is required for -CreateGithubRelease.'
  }

  $args = @('release', 'create', $tag, '--generate-notes')
  if ($Prerelease) {
    $args += '--prerelease'
  }

  & gh @args
}

Write-Host "Release tag prepared: $tag"
