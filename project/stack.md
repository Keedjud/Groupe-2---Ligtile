# Stack technique

## Vue d'ensemble

| Couche | Technologie | Statut dans le brief |
|--------|-------------|----------------------|
| Back-end | Laravel 13, PHP 8.4 | Imposé |
| Authentification | Sanctum | Choix (inclus dans Laravel) |
| Front-end | Vue 3 (Composition API) | Imposé |
| Styling | Tailwind CSS 4 + DaisyUI 5 | Libre choix |
| Base de données | SQLite (local) / MariaDB (production) | Imposé (MySQL) |
| Versioning | GitHub | Imposé (Git) |
| CI/CD | GitHub Actions | Choix |
| Hébergement | Infomaniak (SSH) | Imposé |

---

## Détail et justification des choix

### Laravel 13 — Back-end & API REST

**Pourquoi :** Imposé par le brief pédagogique. Laravel est le framework PHP le plus mature pour construire des API REST, avec un ORM (Eloquent), un système de migrations, et des seeders — tous explicitement demandés dans le brief.

**Décision retenue :** Architecture API REST découplée retenue volontairement pour appliquer les acquis du cours sans introduire un nouveau framework (Inertia.js écarté). Points de vigilance à garder en tête : gestion du CORS et authentification côté client avec Sanctum.

---

### Sanctum — Authentification dashboard

**Pourquoi :** Sanctum est le package d'authentification officiel de Laravel pour les SPA. Il gère l'authentification par **cookie de session** (recommandé) ou par token Bearer. C'est le choix naturel pour un dashboard Vue + Laravel, sans les complexités d'OAuth (Passport).

**Mode à utiliser — cookie, pas token Bearer :** Le dashboard étant sur le même domaine que l'API (`hug-collecte.ch/dashboard`), il n'y a aucun problème de cross-origin. Sanctum utilise des cookies de session httpOnly — non accessibles en JavaScript, résistants aux attaques XSS. Aucune configuration `SESSION_DOMAIN` ou `SANCTUM_STATEFUL_DOMAINS` spéciale requise. Le mode token Bearer (stocké en `localStorage`) reste plus simple à mettre en place mais constitue une mauvaise pratique de sécurité.

**Décision retenue :** Sanctum en mode cookie. La protection repose sur le middleware `auth` Laravel appliqué au groupe de routes `/dashboard`.

---

### Vue 3 — Front-end

**Pourquoi :** Imposé par le brief. Vue 3 avec la Composition API est la version recommandée. Elle est parfaitement adaptée aux besoins du projet : composants réactifs pour le quizz, le scrollytelling, et le compteur en temps réel des inscrits.

#### Architecture multi-entry — 3 apps Vue indépendantes

Le projet est structuré en **3 applications Vue distinctes**, chacune montée sur sa propre Blade view servie par Laravel. Il n'y a pas de rechargement entre les vues d'un même espace, mais un rechargement complet est normal entre les espaces (les utilisateurs sont différents).

```
resources/js/
├── public/app.js       → app Vue du site public
├── dashboard/app.js    → app Vue du dashboard
└── cobrand/app.js      → app Vue des sites cobrandés

resources/views/
├── public.blade.php    → point d'entrée site public
├── dashboard.blade.php → point d'entrée dashboard
└── cobrand.blade.php   → point d'entrée sites cobrandés
```

Laravel sert uniquement ces 3 vues. Tout le reste (navigation, affichage des sections) est géré par Vue à l'intérieur de chaque app.

#### Navigation par hash — sans Vue Router

La navigation entre sections à l'intérieur de chaque app se fait via le **hash de l'URL** (`#section`), sans Vue Router. Le navigateur ne recharge pas la page lors d'un changement de hash — Vue écoute l'événement `hashchange` et affiche le bon composant.

```js
// Exemple dans chaque app
const currentView = ref(window.location.hash || '#home')

window.addEventListener('hashchange', () => {
  currentView.value = window.location.hash
})
```

```html
<!-- Navigation -->
<a href="#trophees">Trophées</a>
<a href="#label">Label</a>

<!-- Affichage conditionnel -->
<HomeView v-if="currentView === '#home'" />
<TropheesView v-if="currentView === '#trophees'" />
<LabelView v-if="currentView === '#label'" />
```

URLs résultantes par espace :

| Espace | Exemples d'URLs |
|--------|----------------|
| Site public | `hug-collecte.ch/#trophees`, `hug-collecte.ch/#label` |
| Dashboard | `hug-collecte.ch/dashboard#collectes`, `hug-collecte.ch/dashboard#collecte/42` |
| Site cobrandé | `hug-collecte.ch/abc123#prevention`, `hug-collecte.ch/abc123#quiz` |

#### Gestion d'état partagé — composable module-level

Pour partager de l'état entre composants (ex : progression du quizz entre la partie 1 et la partie 2), on utilise le **pattern composable avec ref au niveau du module** — sans Pinia ni Vuex.

```js
// composables/useQuizStore.js
import { ref } from 'vue'

const answers = ref([])      // ← déclaré HORS de la fonction = singleton partagé
const currentStep = ref(1)

export function useQuizStore() {
  function addAnswer(answer) {
    answers.value.push(answer)
  }
  function nextStep() {
    currentStep.value++
  }
  return { answers, currentStep, addAnswer, nextStep }
}
```

Tous les composants qui appellent `useQuizStore()` partagent la même instance de `answers` et `currentStep`.

#### Inventaire des pages et niveau de réactivité

| Espace | Page | Réactivité | Justification |
|--------|------|-----------|---------------|
| **Site public** | Landing / Accueil | Faible | Contenu statique, navigation par hash |
| **Site public** | Page Trophées | Faible | Liste statique chargée depuis l'API au montage |
| **Site public** | Page Label | Faible | Liste statique chargée depuis l'API au montage |
| **Site public** | Page Information / Don du sang | Faible | Contenu éditorial statique, pas d'interaction |
| **Site public** | Formulaire de contact | Moyenne | Validation en temps réel, feedback d'envoi, gestion des erreurs |
| **Dashboard** | Page login | Moyenne | Formulaire avec validation, gestion d'erreur, redirection |
| **Dashboard** | Liste des collectes | Haute | CRUD, filtres, tri, rafraîchissement de la liste |
| **Dashboard** | Création / Édition collecte | Haute | Formulaire complexe : color picker, upload logo, aperçu live du co-branding |
| **Dashboard** | Détail d'une collecte | Haute | Statistiques, compteur d'inscrits Onedoc (polling API fictif) |
| **Site cobrandé** | Page d'accueil collecte | Haute | Compteur en temps réel des inscrits (polling API Onedoc), thème dynamique |
| **Site cobrandé** | Page Prévention (Scrollytelling) | Très haute | Animations déclenchées au scroll, transitions entre sections |
| **Site cobrandé** | Quizz (partie 1 — éliminatoire) | Très haute | Navigation entre questions, logique conditionnelle, redirection externe |
| **Site cobrandé** | Quizz (partie 2 — informatif) | Très haute | Pop-ups contextuelles, skip possible, état partagé via composable |
| **Site cobrandé** | Page de redirection Onedoc | Faible | Page de transition simple vers lien externe |

**Synthèse :** Le dashboard et les sites cobrandés concentrent l'essentiel de la complexité réactive. Les pages du site public sont quasi-statiques.

---

### Tailwind CSS + DaisyUI — Styling

**Pourquoi :** Libre choix dans le brief. Tailwind est un utilitaire CSS bas niveau, DaisyUI ajoute une couche de composants sémantiques (boutons, cartes, modals). L'association est bien rodée avec Vue 3 et permet un développement rapide.

**Point d'attention pour le co-branding :** Les sites cobrandés doivent adopter les couleurs de chaque entreprise partenaire. DaisyUI supporte le theming via des variables CSS — les couleurs primaires/secondaires du dashboard peuvent être injectées dynamiquement dans le HTML (`data-theme` ou variables inline) sans dupliquer de CSS. Ce point doit être architecturé dès la phase de modélisation pour ne pas bloquer le développement en cours de sprint.

---

### Base de données

**Production :** MariaDB sur Infomaniak (imposé par le brief — compatible MySQL, Laravel ne fait pas la différence au niveau du driver).

**Développement local :** SQLite — chaque développeur dispose de sa propre base locale sans serveur à installer. Le fichier `database/database.sqlite` est créé automatiquement par `php artisan migrate`. Cette approche évite la dépendance à une base de données partagée et simplifie l'onboarding.

La configuration dans `.env` :
- Local : `DB_CONNECTION=sqlite` (aucune autre variable DB nécessaire)
- Production : `DB_CONNECTION=mysql` + host, port, database, username, password

---

### GitHub — Versioning & stratégie de branches

**Pourquoi :** Le brief impose Git. GitHub est la plateforme Git la plus utilisée, et elle est nécessaire pour faire fonctionner GitHub Actions. Le brief demande explicitement les bonnes pratiques Git (branches, pas de données sensibles).

#### Modèle de branches

```
feature/xxx ──┐
feature/yyy ──┼──→ develop ──→ main (production)
fix/xxx ───────┤
chore/xxx ────┘
```

| Branche | Rôle | Déploiement |
|---------|------|-------------|
| `main` | Code en production, toujours stable | Oui — déclenche le déploiement automatique |
| `develop` | Intégration — seule branche autorisée à merger dans `main` | Non |
| `feature/nom` | Développement d'une fonctionnalité | Non |
| `fix/nom` | Correction de bug | Non |
| `chore/nom` | Tâches techniques sans impact fonctionnel (CI, config, dépendances) | Non |

#### Convention de nommage

- `feature/` — nouvelle fonctionnalité : `feature/quiz-eliminatoire`, `feature/dashboard-collecte`
- `fix/` — correction : `fix/cobrand-theme`, `fix/sanctum-cookie`
- `chore/` — maintenance technique : `chore/update-ci`, `chore/bump-dependencies`
- Noms en minuscules, mots séparés par des tirets, courts et lisibles

#### Workflow PR

1. Chaque développeur crée sa branche depuis `develop` : `git checkout -b feature/ma-feature develop`
2. Une fois la feature terminée, ouverture d'une **PR vers `develop`** — 1 review requise
3. Merge dans `develop` → le workflow Actions vérifie le build
4. Quand `develop` est stable et prête à partir en production, ouverture d'une **PR de `develop` vers `main`** — 1 review requise
5. Merge dans `main` → déploiement automatique déclenché

#### Branch protection rules à configurer sur GitHub

**Sur `main`** (`Settings → Branches → Add branch ruleset`) :
- Require a pull request before merging
- Required approvals : 1
- Dismiss stale pull request approvals when new commits are pushed
- Require status checks to pass (workflow `build-check` sur develop, ou le workflow deploy)
- Do not allow bypassing the above settings

**Sur `develop`** :
- Require a pull request before merging
- Required approvals : 1
- Pas de status check bloquant obligatoire (mais le workflow de build check s'y déclenche quand même)

#### Bonnes pratiques obligatoires
- Ne jamais committer `.env` (ajouter au `.gitignore` dès le début)
- Ne jamais pusher directement sur `main` ou `develop`
- Supprimer les branches de feature après merge (`git branch -d feature/xxx`)

---

### GitHub Actions — CI/CD

**Pourquoi :** Automatise le déploiement à chaque push sur `main` et valide les builds sur `develop`, répond à l'exigence du brief de fournir une procédure de mise en production.

Deux workflows distincts sont mis en place :

| Workflow | Déclencheur | Action |
|----------|-------------|--------|
| `build-check.yml` | Push sur `develop` | Vérifie que le build PHP et front ne cassent pas — sans déployer |
| `deploy.yml` | Push sur `main` | Déploie sur le serveur Infomaniak |

**Architecture retenue : GitHub Actions → bare repo serveur → hook post-receive**

Le bare repo et son hook `post-receive` sont à mettre en place sur le serveur Infomaniak. Une fois en place, GitHub Actions s'insère simplement dans la chaîne en remplaçant le push manuel par un push automatisé via SSH.

```
Push vers GitHub (main)
        ↓
GitHub Actions — deploy.yml (runner ubuntu)
        ↓  git push via SSH
Bare repo sur serveur Infomaniak
        ↓  hook post-receive
Déploiement automatique
```

---

#### Étape 1 — Mise en place du bare repo sur le serveur Infomaniak

Se connecter en SSH au serveur, puis :

```bash
# Structure par site — tout ce qui concerne hug-collecte est dans le même dossier
mkdir -p ~/sites/hug-collecte.ch/repos/hug-collecte.git
mkdir -p ~/sites/hug-collecte.ch/www/hug-collecte

cd ~/sites/hug-collecte.ch/repos/hug-collecte.git
git init --bare
```

Créer le hook `post-receive` :

```bash
nano ~/sites/hug-collecte.ch/repos/hug-collecte.git/hooks/post-receive
```

Contenu du hook :

```bash
#!/bin/bash
set -e

REPO_DIR="$HOME/sites/hug-collecte.ch/repos/hug-collecte.git"
WORK_DIR="$HOME/sites/hug-collecte.ch/www/hug-collecte"

echo "==> Déploiement en cours..."

git --work-tree="$WORK_DIR" --git-dir="$REPO_DIR" checkout main --force

cd "$WORK_DIR"

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh"

echo "==> Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

echo "==> Installation des dépendances Node..."
npm ci

echo "==> Build des assets..."
npm run build

echo "==> Migrations..."
php artisan migrate --force

echo "==> Optimisation Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Déploiement terminé ✓"
```

Rendre le hook exécutable :

```bash
chmod +x ~/sites/hug-collecte.ch/repos/hug-collecte.git/hooks/post-receive
```

---

#### Étape 2 — Configurer l'accès SSH pour GitHub Actions

Sur le serveur, générer une paire de clés dédiée au déploiement :

```bash
ssh-keygen -t ed25519 -C "github-actions-deploy" -f ~/.ssh/deploy_key -N ""
```

Ajouter la clé publique aux clés autorisées du serveur :

```bash
cat ~/.ssh/deploy_key.pub >> ~/.ssh/authorized_keys
```

Copier le contenu de la clé **privée** (`cat ~/.ssh/deploy_key`) — elle sera collée dans les secrets GitHub.

---

#### Étape 3 — Secrets GitHub à configurer

`Settings → Secrets and variables → Actions → New repository secret` :

| Secret | Valeur |
|--------|--------|
| `SSH_PRIVATE_KEY` | Contenu de `~/.ssh/github_actions` (clé privée générée sur le serveur) |
| `SSH_HOST` | Domaine SSH du serveur Infomaniak |
| `SSH_USER` | Nom d'utilisateur SSH Infomaniak |
| `REMOTE_BARE_REPO_PATH` | Chemin absolu du bare repo (ex: `/home/clients/.../sites/hug-collecte.ch/repos/hug-collecte.git`) |
| `SSH_KNOWN_HOSTS` | Empreinte du serveur — obtenue via `Get-Content ~/.ssh/known_hosts \| Select-String "SSH_HOST"` en local |

---

#### Étape 4 — Workflow GitHub Actions

Créer `.github/workflows/deploy.yml` dans le projet :

```yaml
name: Deploy to production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
        with:
          fetch-depth: 0

      - name: Push to server bare repo
        env:
          SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
          SSH_HOST: ${{ secrets.SSH_HOST }}
          SSH_USER: ${{ secrets.SSH_USER }}
          REMOTE_PATH: ${{ secrets.REMOTE_BARE_REPO_PATH }}
          SSH_KNOWN_HOSTS: ${{ secrets.SSH_KNOWN_HOSTS }}
        run: |
          mkdir -p ~/.ssh
          echo "$SSH_PRIVATE_KEY" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa
          echo "$SSH_KNOWN_HOSTS" >> ~/.ssh/known_hosts
          git remote add production "$SSH_USER@$SSH_HOST:$REMOTE_PATH"
          git push production main --force
```

**Avantage de cette approche :** GitHub Actions reste minimal (un seul push SSH). Toute la logique de déploiement est dans le hook sur le serveur — un seul endroit à maintenir, indépendant du CI.

---

#### Workflow build check sur `develop`

Créer `.github/workflows/build-check.yml` :

```yaml
name: Build check

on:
  push:
    branches: [develop]
  pull_request:
    branches: [develop]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'

      - name: Install PHP dependencies
        run: composer install --no-dev --optimize-autoloader --quiet

      - name: Setup Node
        uses: actions/setup-node@v4
        with:
          node-version: '20'

      - name: Install JS dependencies and build
        run: npm ci && npm run build
```

Ce workflow ne déploie rien — il s'assure uniquement que `composer install` et `npm run build` passent sans erreur. Si le build échoue, la PR vers `develop` (ou le push direct) est signalée en erreur dans GitHub, ce qui alerte l'équipe avant que ça atteigne `main`.

---

### Infomaniak — Hébergement

**Pourquoi :** Imposé par le brief. Infomaniak est un hébergeur suisse, ce qui est cohérent avec un projet des HUG (données médicales sensibles, souveraineté des données).

**Points d'attention :**
- Vérifier que le plan Infomaniak supporte les routes dynamiques Laravel (`.htaccess` avec `mod_rewrite` ou config `nginx`)
- Laravel nécessite que le `DocumentRoot` pointe vers le dossier `public/` — à configurer explicitement
- Les variables d'environnement (`.env`) ne doivent jamais être sur le serveur via Git ; les configurer manuellement dans l'interface Infomaniak ou via SSH

---

## Résumé des décisions clés

| Point | Décision | Raison |
|-------|----------|--------|
| Architecture | API REST Laravel + SPA Vue 3 découplée | Appliquer les acquis cours, pas de nouveau framework |
| Déploiement | GitHub Actions → push SSH → bare repo + hook | Infrastructure à créer sur Infomaniak, workflow Actions minimal |
| Branches | `feature/fix/chore` → `develop` → `main` | Convention standard, `develop` seule branche à merger dans `main` |
| CI sur `develop` | Workflow build-check (sans déploiement) | Détecte les erreurs de build avant qu'elles atteignent `main` |
| Co-branding | Theming DaisyUI via variables CSS dynamiques | À architecturer dès la modélisation |
| Architecture Vue | Multi-entry : 3 apps Vue indépendantes | Un espace = une app, pas de rechargement entre espaces différents |
| Navigation | Hash-based sans Vue Router | Pattern maîtrisé, aucune librairie supplémentaire |
| État partagé | Composable module-level (ref singleton) | Pattern déjà utilisé, pas besoin de Pinia |
