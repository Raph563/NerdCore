# Grocy Advanced UI Addon Pack

Ce dossier contient un pack partageable de l'addon Grocy (non-vanilla) base sur `custom_js.html`.

Le pack **n'inclut pas** tes donnees Grocy (produits, unites, stock, recettes, etc.).

## Compatibilite guidee (premier lancement)

L'addon propose au premier lancement un assistant de compatibilite pour creer les attributs personnalises
produits recommandes (ex: marque, sous-marque, origine, SIQO, parent), **sans toucher a `userentity-Medicaments`**.

Tu peux aussi relancer cette operation plus tard via les parametres de l'addon (section "Compatibilite addon").

## Contenu

- `dist/custom_js.html` : addon frontend a injecter dans Grocy.
- `scripts/install.ps1` / `scripts/install.sh` : installation avec backup auto.
- `scripts/uninstall.ps1` / `scripts/uninstall.sh` : retour arriere (restore backup).
- `scripts/export-from-local.ps1` / `scripts/export-from-local.sh` : met a jour `dist/custom_js.html` depuis un Grocy local.
- `docker-sidecar/` : installateur Docker sidecar.

## Option A - Installation locale (script)

### Windows (PowerShell)

```powershell
cd addon\scripts
.\install.ps1 -GrocyConfigPath "C:\path\to\grocy\config"
```

Si `-GrocyConfigPath` est omis, le script tente `../config`.

### Linux/macOS

```bash
cd addon/scripts
chmod +x install.sh
./install.sh /path/to/grocy/config
```

Si le chemin est omis, le script tente `../config`.

## Option B - Sidecar Docker

Depuis `addon/docker-sidecar`:

```bash
docker compose -f docker-compose.example.yml run --rm grocy-addon
```

Ce sidecar:

1. sauvegarde `custom_js.html` (backup timestamp),
2. copie `dist/custom_js.html` vers `config/data/custom_js.html`,
3. ecrit un etat d'installation dans `config/data/grocy-addon-state.json`.

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

1. Versionner uniquement ce dossier `addon/`.
2. Publier des tags/releases.
3. A chaque evolution locale du `config/data/custom_js.html`, executer:

Windows:

```powershell
cd addon\scripts
.\export-from-local.ps1 -GrocyConfigPath "..\..\config"
```

Linux/macOS:

```bash
cd addon/scripts
./export-from-local.sh ../../config
```

## Notes importantes

- Ce pack remplace le fichier `custom_js.html` cible.
- Les backups sont nommes `custom_js.html.bak_addon_YYYYMMDD_HHMMSS`.
- Si tu veux 0 backup, utilise `-NoBackup` (PowerShell) ou `NO_BACKUP=1` (shell).
