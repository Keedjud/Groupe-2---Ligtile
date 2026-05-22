# Stack technique

## Vue d'ensemble

| Couche | Technologie | Statut dans le brief |
|--------|-------------|----------------------|
| Back-end | Laravel 13 | Imposé |
| Authentification | Sanctum | Choix (inclus dans Laravel) |
| Front-end | Vue 3 | Imposé |
| Styling | Tailwind CSS + DaisyUI | Libre choix |
| Base de données | MySQL | Imposé |
| Versioning | GitHub | Imposé (Git) |
| CI/CD | GitHub Actions | Choix |
| Hébergement | Infomaniak (FTP/SSH) | Imposé |

---

## Détail et justification des choix

### Laravel 13 — Back-end & API REST

**Pourquoi :** Imposé par le brief pédagogique. Laravel est le framework PHP le plus mature pour construire des API REST, avec un ORM (Eloquent), un système de migrations, et des seeders — tous explicitement demandés dans le brief.

**Décision retenue :** Architecture API REST découplée retenue volontairement pour appliquer les acquis du cours sans introduire un nouveau framework (Inertia.js écarté). Points de vigilance à garder en tête : gestion du CORS, authentification côté client avec Sanctum, et gestion des états Vue (Pinia recommandé).

---

### Sanctum — Authentification dashboard

**Pourquoi :** Sanctum est le package d'authentification officiel de Laravel pour les SPA. Il gère l'authentification par **cookie de session** (recommandé) ou par token Bearer. C'est le choix naturel pour un dashboard Vue + Laravel, sans les complexités d'OAuth (Passport).

**Mode à utiliser — cookie, pas token Bearer :** Le dashboard étant sur le même domaine que l'API (`hug-collecte.ch/dashboard`), il n'y a aucun problème de cross-origin. Sanctum utilise des cookies de session httpOnly — non accessibles en JavaScript, résistants aux attaques XSS. Aucune configuration `SESSION_DOMAIN` ou `SANCTUM_STATEFUL_DOMAINS` spéciale requise. Le mode token Bearer (stocké en `localStorage`) reste plus simple à mettre en place mais constitue une mauvaise pratique de sécurité.

**Décision retenue :** Sanctum en mode cookie. La protection repose sur le middleware `auth` Laravel appliqué au groupe de routes `/dashboard`.

---

### Vue 3 — Front-end

**Pourquoi :** Imposé par le brief. Vue 3 avec la Composition API est la version recommandée. Elle est parfaitement adaptée aux besoins du projet : composants réactifs pour le quizz, le scrollytelling, et le compteur en temps réel des inscrits. La gestion des états partagés entre composants sera assurée par **Pinia** (store officiel Vue 3 — remplaçant de Vuex, utile notamment pour la progression du quizz et l'état de session du dashboard).

**Architecture SPA complète** retenue pour les trois espaces (site public, dashboard, sites cobrandés), cohérente avec l'API REST Laravel.

#### Inventaire des pages et niveau de réactivité

| Espace | Page | Réactivité | Justification |
|--------|------|-----------|---------------|
| **Site public** | Landing / Accueil | Faible | Contenu statique, navigation — composant Vue léger suffisant |
| **Site public** | Page Trophées | Faible | Affichage d'une liste statique chargée depuis l'API au montage |
| **Site public** | Page Label | Faible | Affichage d'une liste statique chargée depuis l'API au montage |
| **Site public** | Page Information / Don du sang | Faible | Contenu éditorial statique, pas d'interaction |
| **Site public** | Formulaire de contact | Moyenne | Validation en temps réel, feedback d'envoi, gestion des erreurs |
| **Dashboard** | Page login | Moyenne | Formulaire avec validation, gestion d'erreur, redirection |
| **Dashboard** | Liste des collectes | Haute | CRUD, filtres, tri, rafraîchissement de la liste |
| **Dashboard** | Création / Édition collecte | Haute | Formulaire complexe : color picker, upload logo, aperçu live du co-branding |
| **Dashboard** | Détail d'une collecte | Haute | Statistiques, compteur d'inscrits Onedoc (polling API fictif) |
| **Site cobrandé** | Page d'accueil collecte | Haute | Compteur en temps réel des inscrits (polling API Onedoc), thème dynamique |
| **Site cobrandé** | Page Prévention (Scrollytelling) | Très haute | Animations déclenchées au scroll, transitions entre sections |
| **Site cobrandé** | Quizz (partie 1 — éliminatoire) | Très haute | Navigation entre questions, logique conditionnelle, redirection externe |
| **Site cobrandé** | Quizz (partie 2 — informatif) | Très haute | Pop-ups contextuelles, skip possible, état partagé avec partie 1 (Pinia) |
| **Site cobrandé** | Page de redirection Onedoc | Faible | Page de transition simple vers lien externe |

**Synthèse :** Le dashboard et les sites cobrandés concentrent l'essentiel de la complexité réactive. Les pages du site public sont quasi-statiques — elles bénéficient de Vue pour la cohérence de l'architecture SPA (routing, composants réutilisables) mais ne nécessitent pas de logique réactive poussée.

---

### Tailwind CSS + DaisyUI — Styling

**Pourquoi :** Libre choix dans le brief. Tailwind est un utilitaire CSS bas niveau, DaisyUI ajoute une couche de composants sémantiques (boutons, cartes, modals). L'association est bien rodée avec Vue 3 et permet un développement rapide.

**Point d'attention pour le co-branding :** Les sites cobrandés doivent adopter les couleurs de chaque entreprise partenaire. DaisyUI supporte le theming via des variables CSS — les couleurs primaires/secondaires du dashboard peuvent être injectées dynamiquement dans le HTML (`data-theme` ou variables inline) sans dupliquer de CSS. Ce point doit être architecturé dès la phase de modélisation pour ne pas bloquer le développement en cours de sprint.

**Décision retenue :** Bon choix. Se documenter sur le système de thèmes DaisyUI avant d'attaquer le développement des sites cobrandés.

---

### MySQL — Base de données

**Pourquoi :** Imposé par le brief. Bien intégré dans Laravel via Eloquent. Standard pour les hébergements Infomaniak.

Aucun challenge : choix sans alternative dans le cadre de ce projet.

---

### GitHub — Versioning

**Pourquoi :** Le brief impose Git. GitHub est la plateforme Git la plus utilisée, et elle est nécessaire pour faire fonctionner GitHub Actions. Le brief demande explicitement les bonnes pratiques Git (branches, pas de données sensibles).

**Bonnes pratiques à respecter obligatoirement :**
- Ne jamais committer le fichier `.env`
- Utiliser des branches de fonctionnalité (`feature/`, `fix/`)
- Protéger la branche `main` avec des PR reviews

---

### GitHub Actions — CI/CD

**Pourquoi :** Automatise le déploiement à chaque push sur `main`, répond à l'exigence du brief de fournir une procédure de mise en production.

**Architecture retenue : GitHub Actions → bare repo serveur → hook post-receive**

Le bare repo et son hook `post-receive` sont à mettre en place sur le serveur Infomaniak. Une fois en place, GitHub Actions s'insère simplement dans la chaîne en remplaçant le push manuel par un push automatisé via SSH.

```
Push vers GitHub (main)
        ↓
GitHub Actions (runner ubuntu)
        ↓  git push via SSH
Bare repo sur serveur Infomaniak
        ↓  hook post-receive
Déploiement automatique
```

---

#### Étape 1 — Mise en place du bare repo sur le serveur Infomaniak

Se connecter en SSH au serveur, puis :

```bash
# Créer le bare repo (dépôt nu, sans working tree)
mkdir -p ~/repos/hug-collecte.git
cd ~/repos/hug-collecte.git
git init --bare

# Créer le répertoire de travail (là où tourne l'application)
mkdir -p ~/www/hug-collecte
```

Créer le hook `post-receive` :

```bash
nano ~/repos/hug-collecte.git/hooks/post-receive
```

Contenu du hook :

```bash
#!/bin/bash
set -e

WORK_TREE=~/www/hug-collecte
BARE_REPO=~/repos/hug-collecte.git

echo "--- Déploiement en cours ---"

GIT_WORK_TREE=$WORK_TREE git checkout -f main

cd $WORK_TREE

echo "→ Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader --quiet

echo "→ Build des assets front-end..."
npm ci --quiet && npm run build

echo "→ Migrations base de données..."
php artisan migrate --force

echo "→ Mise en cache Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "--- Déploiement terminé ---"
```

Rendre le hook exécutable :

```bash
chmod +x ~/repos/hug-collecte.git/hooks/post-receive
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
| `SSH_PRIVATE_KEY` | Contenu de `~/.ssh/deploy_key` (clé privée) |
| `SSH_HOST` | IP ou domaine SSH du serveur Infomaniak |
| `SSH_USER` | Nom d'utilisateur SSH |
| `REMOTE_BARE_REPO_PATH` | Chemin absolu du bare repo (ex: `/home/user/repos/hug-collecte.git`) |

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
        run: |
          mkdir -p ~/.ssh
          echo "$SSH_PRIVATE_KEY" > ~/.ssh/id_rsa
          chmod 600 ~/.ssh/id_rsa
          ssh-keyscan -H "$SSH_HOST" >> ~/.ssh/known_hosts
          git remote add production "$SSH_USER@$SSH_HOST:$REMOTE_PATH"
          git push production main --force
```

**Avantage de cette approche :** GitHub Actions reste minimal (un seul push SSH). Toute la logique de déploiement est dans le hook sur le serveur — un seul endroit à maintenir, indépendant du CI.

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
| Co-branding | Theming DaisyUI via variables CSS dynamiques | À architecturer dès la modélisation |
| Périmètre Vue | SPA complète | Cohérent avec l'architecture API REST retenue |
