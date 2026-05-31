# Ligtile — Plateforme de collectes de sang CTS

Projet étudiant — HEIG-VD, semestre 4.

Plateforme multi-sites Laravel + Vue 3 destinée à faciliter l'organisation de collectes de sang en entreprise pour le CTS (Centre de Transfusion Sanguine).

---

## Stack technique

- **Back-end** : Laravel 13, PHP 8.4
- **Front-end** : Vue 3 (Composition API), Vite, Tailwind CSS 4, DaisyUI 5
- **Base de données** : SQLite (local) / MariaDB (production)
- **Auth** : Laravel Sanctum (sessions cookie)
- **Déploiement** : GitHub Actions → bare repo → post-receive hook (Infomaniak)

---

## Prérequis

- PHP 8.4+
- Composer
- Node.js 20+
- Git

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/Keedjud/Groupe-2---Ligtile.git
cd Groupe-2---Ligtile
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Le fichier `.env` est pré-configuré pour SQLite — aucun serveur de base de données à installer.

### 4. Créer la base de données et lancer les migrations

```bash
php artisan migrate
```

Laravel crée automatiquement le fichier `database/database.sqlite` s'il n'existe pas.

### 5. Lancer le serveur de développement

Dans deux terminaux séparés :

```bash
# Terminal 1 — serveur PHP
php artisan serve

# Terminal 2 — compilation des assets avec hot reload
npm run dev
```

Le site est accessible sur [http://localhost:8000](http://localhost:8000).

---

## Structure front-end

Le front-end est organisé en **trois SPAs indépendantes** :

| Entrée | URL | Description |
|--------|-----|-------------|
| `resources/js/public/app.js` | `/` | Site public vitrine |
| `resources/js/dashboard/app.js` | `/dashboard` | Interface de gestion CTS |
| `resources/js/cobrand/app.js` | `/{id}` | Sites cobrandés par collecte |

**Choix architecturaux :**
- Pas de Vue Router — navigation par `window.location.hash` + `hashchange`
- Pas de Pinia — état partagé via le pattern composable à niveau module
- Alias `@` → `resources/js/`

---

## Branches

| Branche | Rôle |
|---------|------|
| `main` | Production — déclenche le déploiement automatique |
| `develop` | Intégration — déclenche le build check |
| `feature/*` | Développement de fonctionnalités |
| `fix/*` | Corrections de bugs |
| `chore/*` | Tâches techniques (CI, config, etc.) |

**Flux de travail :**
1. Créer une branche depuis `develop` : `git checkout -b feature/ma-fonctionnalite`
2. Développer et commiter
3. Ouvrir une PR vers `develop`
4. Une fois validée, merger dans `develop`
5. Quand `develop` est stable, ouvrir une PR vers `main` pour déployer

---

## Déploiement

Le déploiement est entièrement automatisé via GitHub Actions.

Tout push sur `main` déclenche le workflow `.github/workflows/deploy.yml` qui :
1. Pousse le code vers le bare repo sur Infomaniak via SSH
2. Le hook `post-receive` prend le relais : `composer install`, `npm run build`, migrations, caches Laravel

**Ne jamais commiter le fichier `.env`** — il contient des secrets et est dans le `.gitignore`.

---

## Variables d'environnement

Copier `.env.example` en `.env` et renseigner les valeurs. Le fichier `.env.example` est configuré pour le développement local en SQLite.

Pour la production, les variables sensibles (`APP_KEY`, `DB_PASSWORD`, etc.) sont à définir directement sur le serveur dans le fichier `.env` — jamais dans Git.
