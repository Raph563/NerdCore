# StatNerd Core - Addon Pack (EN)

This pack contains the core line (stats + charts).

Product helper features were split to:
- https://github.com/Raph563/Grocy_Product_Helper

## Contents

- `dist/custom_js.html`: core dashboard payload.
- `scripts/install.*`: local install.
- `scripts/uninstall.*`: rollback.
- `scripts/update-from-github.*`: update from GitHub releases.
- `docker-sidecar/`: Docker sidecar option.

## Local install

```powershell
cd addon\scripts
.\install.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```
