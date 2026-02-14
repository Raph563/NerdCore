# Beginner Guide (EN)

This guide uses generic paths only (no personal machine path).

## 1) Prepare the repo

```powershell
cd C:\path\to\repo
git status
```

## 2) Create an alpha release (default)

```powershell
.\scripts\release.ps1 -Version 1.2.1
```

This creates a tag like `v1.2.1-alpha.N`.

## 3) Create a beta release

```powershell
.\scripts\release.ps1 -Version 1.2.1 -Channel beta
```

## 4) Create a stable release

```powershell
.\scripts\release.ps1 -Version 1.2.1 -Channel stable
```

## 5) Verify on GitHub

1. Open `Actions` in the repository.
2. Open workflow `Release`.
3. Confirm `publish` job is green.
4. Open `Releases` and confirm:
   - release tag,
   - ZIP asset.

## 6) Common errors

- `Working tree is not clean`: commit or stash changes.
- `Tag already exists`: pick a new version or channel.
- GitHub Actions failure: check repo permissions/actions/billing.

## Links

- EN release guide: `RELEASING.en.md`
- EN overview: `README.en.md`
- FR version: `NOOB_GUIDE.fr.md`
