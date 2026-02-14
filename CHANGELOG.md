# Changelog

All notable changes to this repository are documented in this file.

The format follows Keep a Changelog and semantic versioning.

## [1.1.0] - 2026-02-14

### Added
- Root-level project documentation (`README.md`).
- Maintainer release guide (`RELEASING.md`).
- Automated GitHub Releases workflow (`.github/workflows/release.yml`).
- Cross-platform release helper scripts (`scripts/release.ps1`, `scripts/release.sh`).
- Addon version file (`addon/VERSION`).
- Config runtime policy documentation (`config/README.md`).

### Changed
- Repository scope clarified around distributable addon artifacts.
- `.gitignore` hardened for local runtime/sensitive Grocy data.

### Security
- Removed tracked runtime/local data from version control index:
  - database and caches
  - personal media and user files
  - logs and local cert keys
  - local runtime config file

## [1.0.0] - 2026-02-14

### Added
- Custom Grocy addon payload (`addon/dist/custom_js.html`).
- Guided addon compatibility setup and analytics features in frontend addon.
- Install/uninstall/export scripts for PowerShell and POSIX shell.
- Docker sidecar installer (`addon/docker-sidecar/*`).
- Addon documentation (`addon/README.md`).
