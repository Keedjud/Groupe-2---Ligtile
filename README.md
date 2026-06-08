# Ligtile — Plateforme de collectes de sang CTS

Projet étudiant — HEIG-VD, semestre 4.

Plateforme multi-sites Laravel + Vue 3 destinée à faciliter l'organisation de collectes de sang en entreprise pour le CTS (Centre de Transfusion Sanguine).

---

## Table des matières

1. [Stack technique](#stack-technique)
2. [Installation](#installation)
3. [Architecture](#architecture)
4. [Git — branches et workflow](#git--branches-et-workflow)
5. [Déploiement](#déploiement)
6. [Concept produit](#concept-produit)
7. [KPIs du dashboard](#kpis-du-dashboard)
8. [Documentation complémentaire](#documentation-complémentaire)

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Back-end | Laravel 13, PHP 8.4 |
| Authentification | Sanctum (mode cookie — pas token Bearer) |
| Front-end | Vue 3 (Composition API), Vite |
| Styling | Tailwind CSS 4 |
| Base de données | SQLite (local) / MariaDB (production) |
| Versioning / CI | GitHub + GitHub Actions |
| Hébergement | Infomaniak (SSH) |

**Décisions clés :**

| Point | Décision | Raison |
|-------|----------|--------|
| Architecture | API REST Laravel + SPA Vue 3 découplée | Appliquer les acquis cours, pas de nouveau framework |
| Auth | Sanctum cookie (httpOnly, résistant XSS, même domaine) | Token Bearer en localStorage = mauvaise pratique |
| Navigation | Hash-based sans Vue Router | Pattern maîtrisé, zéro librairie supplémentaire |
| État partagé | Composable module-level (ref singleton) | Pas besoin de Pinia pour la taille du projet |
| Co-branding | Variables CSS custom (`--cobrand-primary/secondary/*`) injectées sur `<html>` | Générées par `ColorPaletteService::fromTwo()` (PHP) + répliquées en JS, pas de CSS dupliqué par collecte |
| Déploiement | GitHub Actions → push SSH → bare repo + hook | Logique de déploiement centralisée côté serveur |
| DB locale | SQLite | Aucun serveur à installer, onboarding immédiat |
| Questions quiz | Hard-codées dans `cobrand/constants/quizQuestions.js` | CTS n'a pas besoin d'éditer les questions via le dashboard |

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

Laravel crée automatiquement `database/database.sqlite` s'il n'existe pas.

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

## Architecture

### Trois SPAs indépendantes

| Entrée | URL | Description |
|--------|-----|-------------|
| `resources/js/public/app.js` | `/` | Site public vitrine |
| `resources/js/dashboard/app.js` | `/dashboard` | Interface de gestion CTS |
| `resources/js/cobrand/app.js` | `/{id}` | Sites cobrandés par collecte |

Chaque app est montée sur sa propre Blade view servie par Laravel. Un rechargement complet est normal entre espaces — pas entre vues d'un même espace.

### Navigation par hash (sans Vue Router)

```js
const currentView = ref(window.location.hash || '#home')
window.addEventListener('hashchange', () => {
  currentView.value = window.location.hash
})
```

| Espace | Exemples d'URLs |
|--------|----------------|
| Site public | `hug-collecte.ch/#trophees`, `hug-collecte.ch/#label` |
| Dashboard | `hug-collecte.ch/dashboard#/collectes`, `hug-collecte.ch/dashboard#/collectes/42` |
| Site cobrandé | `hug-collecte.ch/abc123#prevention`, `hug-collecte.ch/abc123#quiz` |

### État partagé — composable module-level

```js
// cobrand/composables/useQuizStore.js
import { ref } from 'vue'

const answers = ref([])     // ← hors de la fonction = singleton partagé entre composants
const currentStep = ref(1)

export function useQuizStore() {
  return { answers, currentStep }
}
```

### Structure back-end

Les controllers API sont organisés par version sous `app/Http/Controllers/Api/v1/`. Toute nouvelle route API suit ce pattern :

```
app/Http/Controllers/
├── Controller.php           ← classe de base Laravel
└── Api/
    └── v1/
        ├── LabelCompanyController.php
        ├── ApiTropheeController.php
        └── ...              ← nouveaux controllers ici
```

Les routes sont déclarées dans `routes/api/` (un fichier par domaine) et incluses depuis `routes/api.php` :

```
routes/
├── api.php                  ← inclut les 3 fichiers ci-dessous
├── api/
│   ├── cobrand.php          ← routes des sites cobrandés
│   ├── dashboard.php        ← routes du dashboard
│   └── public.php           ← routes du site public
└── web.php                  ← 3 routes Blade (public, dashboard, cobrand)
```


---

### Structure des fichiers front-end

```
resources/js/
├── composables/          ← composables partagés entre les 3 apps
├── public/
│   ├── app.js
│   ├── components/
│   ├── composables/
│   └── views/
├── dashboard/
│   ├── app.js
│   ├── components/
│   ├── composables/
│   └── views/
└── cobrand/
    ├── app.js
    ├── components/
    ├── composables/
    ├── constants/
    │   └── quizQuestions.js  ← slugs stables des questions du quiz
    └── views/
```

> **Règle importante — slugs du quiz :** les identifiants de questions définis dans `quizQuestions.js` ne doivent jamais être modifiés en production sans migrer les données de tracking. Un changement de slug sans migration crée deux entrées distinctes pour la même question dans tous les agrégats.

---

## Git — branches et workflow

| Branche | Rôle | CI déclenché |
|---------|------|-------------|
| `main` | Production — toujours stable | Déploiement automatique |
| `develop` | Intégration | Build check |
| `feature/*` | Développement de fonctionnalités | — |
| `fix/*` | Corrections de bugs | — |
| `chore/*` | Tâches techniques (CI, config, dépendances) | — |

### Workflow standard

1. Créer sa branche depuis `develop` : `git checkout -b feature/ma-feature develop`
2. Développer et commiter
3. **Avant d'ouvrir la PR : synchroniser sa branche avec `develop`**
4. Ouvrir une PR vers `develop` — 1 review requise
5. Merger dans `develop` → build check automatique
6. Quand `develop` est stable → PR vers `main` → déploiement automatique

### Règle critique — synchroniser avant d'ouvrir une PR

**Avant toute demande de review, la branche doit être à jour avec `develop` :**

```bash
git checkout ma-feature
git merge develop      # ou git rebase develop selon la préférence
# résoudre ses propres conflits
git push
```

C'est au développeur de résoudre ses conflits, pas au reviewer. Un reviewer qui découvre des conflits au moment du merge perd du temps sur du code qu'il n'a pas écrit.

### Branches empilées — quand deux tâches touchent les mêmes fichiers

Si une PR est en attente de review et que la prochaine tâche touche les mêmes fichiers, **ne pas brancher depuis `develop`** — brancher depuis la PR en cours :

```bash
# PR1 ouverte (feature-a), en attente de review
git checkout feature-a
git checkout -b feature-b    # part de feature-a, pas de develop
```

Sur GitHub, ouvrir la PR de `feature-b` en ciblant `feature-a` comme branche de base (sélecteur "base" en haut de la PR).

```
develop
  └── feature-a   ← PR1, en review
        └── feature-b   ← PR2, construite sur PR1
```

**Quand PR1 est mergée dans `develop` :**

```bash
git checkout feature-b
git rebase develop                 # ou git merge develop
git push --force-with-lease        # --force-with-lease plutôt que --force
# changer la base de la PR sur GitHub : feature-a → develop
```

> `--force-with-lease` est plus sûr que `--force` : il refuse d'écraser si quelqu'un d'autre a pushé entre-temps.

Si des modifications sont demandées sur PR1 après l'ouverture de PR2, propager les changements dans PR2 :

```bash
git checkout feature-b
git rebase feature-a    # ou git merge feature-a
```

### Règles obligatoires

- Ne jamais commiter `.env`
- Ne jamais pusher directement sur `main` ou `develop`
- Supprimer les branches après merge
- Une PR = une chose précise — séparer backend et frontend si les deux sont conséquents

---

## Déploiement

Automatisé via GitHub Actions. Tout push sur `main` déclenche `.github/workflows/deploy.yml` qui pousse le code vers le bare repo Infomaniak via SSH. Le hook `post-receive` prend le relais :

```
composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

**Ne jamais commiter le `.env`** — les variables de production sont configurées directement sur le serveur via SSH.

---

## Concept produit

### Les trois espaces

| Espace | URL | Accès | Rôle |
|--------|-----|-------|------|
| Site public | `hug-collecte.ch` | Public | Vitrine : trophées, label CTS, informations don, deux formulaires de contact |
| Dashboard CTS | `hug-collecte.ch/dashboard` | Login requis | Création et gestion des collectes, métriques KPIs |
| Sites cobrandés | `hug-collecte.ch/{id_collecte}` | Public (lien distribué) | Site auto-généré aux couleurs de l'entreprise partenaire |

> **Deux formulaires de contact distincts sur le site public :**
> - `Home.vue` — formulaire rapide destiné aux **grandes entreprises** souhaitant organiser une collecte directement
> - `Information.vue` — formulaire simple (champ texte libre) destiné aux **petites entreprises** ayant des questions générales

### Flux de création d'une collecte

1. Une entreprise soumet le formulaire de contact depuis le site public
2. CTS et entreprise s'accordent par téléphone (dates, lieu, créneaux)
3. L'entreprise fournit ses couleurs et son logo pour le co-branding
4. Le CTS crée la collecte dans le dashboard → **un site cobrandé est généré automatiquement**
5. Le lien est transmis aux employés dans le kit de communication interne

### Parcours employé sur le site cobrandé

1. **Page d'accueil** — informations de la collecte + compteur d'inscrits en temps réel
2. **Page Prévention** — contenu de sensibilisation interactif en scrollytelling
3. **Quiz d'éligibilité** :
   - **Partie 1 — éliminatoire** : une mauvaise réponse redirige vers la section prévention correspondante
   - **Partie 2 — informative (skippable)** : une mauvaise réponse affiche une pop-up explicative. L'employé peut passer une question individuelle ou toute la partie
4. **Lien Onedoc** — accès au créneau. **Un clic = une inscription comptabilisée**

> Un footer présent sur toutes les pages précise qu'il s'agit d'un projet étudiant sans lien officiel avec les HUG ou le CTS.

---

## KPIs du dashboard

Accessibles depuis l'onglet `#/analytics` du dashboard CTS.

### Vue d'ensemble opérationnelle

| KPI | Description |
|-----|-------------|
| Nombre total de collectes organisées | Volume brut sur une période sélectionnable |
| Nombre total d'inscrits cumulé | Total de clics Onedoc toutes collectes confondues |
| Nombre d'entreprises distinctes touchées | Entreprises différentes ayant participé sur la période |
| Évolution temporelle | KPIs principaux visualisables par mois / trimestre / année |

### Engagement entreprises

| KPI | Description |
|-----|-------------|
| Taux de remplissage moyen des collectes | `nb inscrits / capacity` par collecte, moyenné sur la période |
| Collectes récurrentes | Entreprises ayant organisé ≥ 2 collectes — mesure la fidélisation |
| Top entreprises contributrices | Classement par nombre de collectes organisées |
| Nombre de demandes de collecte | Formulaires de contact envoyés depuis le site public |

### Performance du quiz — vue globale

| KPI | Description |
|-----|-------------|
| Taux de complétion du quiz | % ayant répondu à toutes les questions (P1 + P2) parmi ceux ayant commencé |
| Taux d'élimination P1 global | % éliminés en partie 1 parmi ceux ayant commencé le quiz |
| Taux de clic sur "Prendre rendez-vous" | % des éligibles (ayant passé P1) ayant cliqué sur Onedoc |

### Performance du quiz — par question

| KPI | Description |
|-----|-------------|
| Principale cause de non-éligibilité | Question (P1 ou P2) à l'origine du plus grand nombre de non-éligibilités |
| Taux de bonnes/mauvaises réponses par question | Ratio correct / incorrect pour chaque question |
| Taux de skip par question (P2) | % de chaque question P2 passée sans répondre |
| Question d'abandon P2 | À partir de quelle question les employés abandonnent la partie 2 |

### Engagement page prévention

| KPI | Description |
|-----|-------------|
| Temps passé sur la page scrollytelling | Durée moyenne — les employés lisent-ils vraiment le contenu ? |
| Taux de rebond sur la page cobrandée | % quittant la page prévention sans aucune interaction |

### Recommandation (non implémentable)

| KPI | Raison |
|-----|--------|
| ~~Taux de dons réels parmi les inscrits~~ | Nécessiterait une intégration Onedoc post-collecte |

---

## Documentation complémentaire

- **[`project/concept.md`](project/concept.md)** — concept produit complet, modèle de tracking détaillé (schémas des 3 tables, liste exhaustive des event types, formules SQL de chaque KPI)
