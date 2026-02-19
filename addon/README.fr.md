# StatNerd Core - Pack Addon (FR)

Ce pack contient la ligne core (stats + graphiques).

Les fonctions produit ont ete separees dans:
- https://github.com/Raph563/Grocy_Product_Helper

## Contenu

- `dist/custom_js.html`: payload core dashboard.
- `scripts/install.*`: installation locale.
- `scripts/uninstall.*`: rollback.
- `scripts/update-from-github.*`: update depuis releases GitHub.
- `docker-sidecar/`: option sidecar Docker.

## Installation locale

```powershell
cd addon\scripts
.\install.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```
