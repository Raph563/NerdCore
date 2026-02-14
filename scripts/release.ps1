param(
  [Parameter(Mandatory = $true)]
  [string]$Version,
  [ValidateSet('alpha', 'beta', 'stable')]
  [string]$Channel = 'alpha',
  [switch]$NoPush,
  [switch]$CreateGithubRelease,
  [switch]$Prerelease
)

$ErrorActionPreference = 'Stop'

if ($Version -notmatch '^[0-9]+\.[0-9]+\.[0-9]+$') {
  throw "Invalid version format: $Version (expected X.Y.Z)"
}

if ($Prerelease -and $Channel -eq 'stable') {
  $Channel = 'alpha'
}

function Get-NextPrereleaseTag {
  param(
    [string]$BaseVersion,
    [ValidateSet('alpha', 'beta')]
    [string]$PreChannel
  )

  $prefix = "v$BaseVersion-$PreChannel."
  $pattern = "^v$([regex]::Escape($BaseVersion))-$PreChannel\.(\d+)$"
  $max = 0
  $tags = git tag --list "$prefix*"
  foreach ($tag in $tags) {
    $text = [string]$tag
    if ($text -match $pattern) {
      $n = [int]$matches[1]
      if ($n -gt $max) {
        $max = $n
      }
    }
  }
  return "$prefix$($max + 1)"
}

$tag = if ($Channel -eq 'stable') {
  "v$Version"
} else {
  Get-NextPrereleaseTag -BaseVersion $Version -PreChannel $Channel
}

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
  if ($Channel -ne 'stable' -or $Prerelease) {
    $args += '--prerelease'
  }

  & gh @args
}

Write-Host "Release tag prepared: $tag (channel: $Channel)"
