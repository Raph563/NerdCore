# Grocy Advanced UI Addon Pack

Ce dossier contient le pack partageable de l'addon Grocy base sur `custom_js.html`.

Le pack **n'inclut pas** tes donnees Grocy (stock, produits, recettes, etc.).

Guide ultra detaille debutant:

- `../docs/NOOB_GUIDE.md`

## Compatibilite guidee (premier lancement)

L'addon propose un assistant de compatibilite qui peut:

- detecter les elements deja presents,
- proposer des associations quand des noms ressemblent,
- creer uniquement ce qui manque.

Portee:

- `product_groups`
- `userentities`
- `userfields`

Tu peux relancer cela dans les parametres addon:

- section `Compatibilite addon`

## Contenu

- `dist/custom_js.html`: payload frontend a injecter dans Grocy.
- `scripts/install.ps1` / `scripts/install.sh`: installation avec backup.
- `scripts/update-from-github.ps1` / `scripts/update-from-github.sh`: mise a jour depuis GitHub release.
- `scripts/uninstall.ps1` / `scripts/uninstall.sh`: rollback.
- `scripts/export-from-local.ps1` / `scripts/export-from-local.sh`: export local vers `dist/custom_js.html`.
- `docker-sidecar/`: installateur sidecar Docker.

## Option A - Installation locale

### Windows (PowerShell)

```powershell
cd addon\scripts
.\install.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```

### Linux/macOS

```bash
cd addon/scripts
chmod +x install.sh
./install.sh /path/to/grocy/config
```

Si le chemin est omis, les scripts tentent `../config`.

## Mise a jour locale depuis GitHub release

### Windows (PowerShell)

```powershell
cd addon\scripts
.\update-from-github.ps1 -Repository "Raph563/Grocy" -GrocyConfigPath "C:\path\to\grocy\config"
```

### Linux/macOS

```bash
cd addon/scripts
chmod +x update-from-github.sh
./update-from-github.sh --repository "Raph563/Grocy" --config /path/to/grocy/config
```

Options utiles:

- version specifique: `-ReleaseTag "v1.2.3"` (PowerShell) ou `--tag v1.2.3` (shell)
- sans backup: `-NoBackup` (PowerShell) ou `--no-backup` / `NO_BACKUP=1` (shell)

## Option B - Sidecar Docker

Depuis `addon/docker-sidecar`:

```bash
docker compose -f docker-compose.example.yml run --rm grocy-addon
```

Le sidecar:

1. sauvegarde `custom_js.html`,
2. copie `dist/custom_js.html` vers `config/data/custom_js.html`,
3. ecrit `config/data/grocy-addon-state.json`.

## Desinstallation / rollback

### Windows

```powershell
cd addon\scripts
.\uninstall.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```

### Linux/macOS

```bash
cd addon/scripts
chmod +x uninstall.sh
./uninstall.sh /path/to/grocy/config
```

## Workflow GitHub recommande

1. modifier l'addon,
2. exporter depuis local si besoin (`export-from-local`),
3. mettre a jour `addon/VERSION` + `CHANGELOG.md`,
4. tag/release.

## Notes importantes

- Ce pack remplace le fichier cible `config/data/custom_js.html`.
- Les backups sont nommes `custom_js.html.bak_addon_YYYYMMDD_HHMMSS`.
- Un etat est ecrit dans `config/data/grocy-addon-state.json`.
