# Guide Noob (Tres Detaille)

Ce guide explique pas a pas comment installer, mettre a jour, restaurer (rollback) et publier ton addon Grocy sans te prendre la tete.

Contexte local cible (ta machine):

- Repo: `C:\Users\Admin\Documents\Docker\grocy`
- Config Grocy: `C:\Users\Admin\Documents\Docker\grocy\config`
- Fichier addon actif dans Grocy: `config/data/custom_js.html`

## 1) Prerequis minimaux

- Windows + PowerShell.
- Un clone local du repo `Raph563/Grocy`.
- Ton instance Grocy qui lit bien `config/data/custom_js.html`.

Optionnel (pour release):

- Compte GitHub avec droits push.
- GitHub CLI `gh`.

## 2) Installation initiale (local)

Ouvre PowerShell:

```powershell
cd C:\Users\Admin\Documents\Docker\grocy\addon\scripts
.\install.ps1 -GrocyConfigPath "C:\Users\Admin\Documents\Docker\grocy\config"
```

Ce que fait le script:

1. cree un backup de `config/data/custom_js.html` (si present),
2. copie `addon/dist/custom_js.html` vers `config/data/custom_js.html`,
3. ecrit un etat d'installation (`grocy-addon-state.json`).

## 3) Nouvelle mise a jour depuis GitHub release

Script principal (nouveau):

```powershell
cd C:\Users\Admin\Documents\Docker\grocy\addon\scripts
.\update-from-github.ps1 -Repository "Raph563/Grocy" -GrocyConfigPath "C:\Users\Admin\Documents\Docker\grocy\config"
```

Notes utiles:

- Sans `-GrocyConfigPath`, le script tente automatiquement `../config`.
- Sans `-ReleaseTag`, il prend la derniere release stable.
- Avec `-ReleaseTag "v1.2.3"`, il force une version precise.
- Avec `-NoBackup`, il saute la sauvegarde (pas recommande).

Exemple version precise:

```powershell
.\update-from-github.ps1 -Repository "Raph563/Grocy" -ReleaseTag "v1.2.3" -GrocyConfigPath "C:\Users\Admin\Documents\Docker\grocy\config"
```

## 4) Utiliser le check update dans l'UI addon

Dans Grocy:

1. ouvre le dashboard addon,
2. clique l'icone parametres,
3. va dans la section `Mise a jour addon`,
4. clique `Verifier la version`.

Tu auras:

- version locale detectee,
- derniere release GitHub,
- bouton release,
- commandes PowerShell/Shell copiable pour faire la mise a jour locale.

## 5) Rollback rapide (si probleme)

Le backup est dans `config/data/`:

- nom type: `custom_js.html.bak_addon_YYYYMMDD_HHMMSS`

Pour restaurer manuellement:

```powershell
cd C:\Users\Admin\Documents\Docker\grocy\config\data
Copy-Item .\custom_js.html.bak_addon_20260214_120000 .\custom_js.html -Force
```

Alternative:

```powershell
cd C:\Users\Admin\Documents\Docker\grocy\addon\scripts
.\uninstall.ps1 -GrocyConfigPath "C:\Users\Admin\Documents\Docker\grocy\config"
```

## 6) Quand tu modifies localement Grocy (et veux commit)

Si tu as modifie le `custom_js.html` actif dans Grocy et que tu veux le rapatrier dans le repo:

```powershell
cd C:\Users\Admin\Documents\Docker\grocy\addon\scripts
.\export-from-local.ps1 -GrocyConfigPath "C:\Users\Admin\Documents\Docker\grocy\config"
```

## 7) Faire une release propre

1. mets a jour:
   - `addon/VERSION`
   - `addon/dist/custom_js.html` (constante `ADDON_RUNTIME_VERSION`)
   - `CHANGELOG.md`
2. commit.
3. lance:

```powershell
cd C:\Users\Admin\Documents\Docker\grocy
.\scripts\release.ps1 -Version 1.2.0
```

Le workflow GitHub publie automatiquement `grocy-addon-vX.Y.Z.zip`.

## 8) Depannage express

- `Data directory not found`: mauvais `-GrocyConfigPath`.
- `No GitHub release found`: pas de release publiee (ou repo/tag faux).
- `No ZIP asset found`: release presente mais asset manquant.
- L'UI ne change pas apres update:
  1. hard refresh navigateur (`Ctrl+F5`),
  2. verifie que `config/data/custom_js.html` a bien ete remplace.
