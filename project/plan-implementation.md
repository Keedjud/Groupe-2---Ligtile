# Plan d'implémentation — Fin de projet

> Rédigé le 2 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.

---

## Contexte et contraintes

**L'équipe :**

| Développeur | Disponibilité | Rôle dans la suite |
|-------------|---------------|--------------------|
| Loïc | Disponible maintenant, part sur les maquettes dès que le dashboard est mergé, revient ensuite | Dashboard UI (déjà fait), connexion au vrai back après retour |
| Elia | Disponible tout le long | Site cobrandé — pages Accueil et Prévention, cœur de l'expérience utilisateur |
| Inoé | Reviews + développement | Fondations, back-end, quiz cobrandé, coordination |

**Règle d'or anti-conflit :** Avant toute PR, `git merge develop` sur sa branche, résoudre ses propres conflits, puis demander review. Un reviewer ne résout jamais les conflits d'une autre personne.

---

## Ce qui reste à faire (état au 2 juin 2026)

### Backend (tout à créer)
- [ ] Migration : colonne `capacity` sur `collections`, suppression `nb_registered`
- [ ] Migration `quiz_events`
- [ ] Migration `page_events`
- [ ] Migration `contact_requests` (formulaire grandes entreprises — alimente le KPI)
- [ ] Migration `pme_contacts` (formulaire PME)
- [ ] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`
- [ ] Réorganisation des routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [ ] Auth Sanctum : `POST /api/v1/auth/login`, `POST /api/v1/auth/logout`
- [ ] CRUD collections : `GET`, `POST`, `PUT /api/v1/collections`
- [ ] Endpoint cobrand public : `GET /api/v1/cobrand/{token}`
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event`
- [ ] Métriques : `GET /api/v1/metrics`
- [ ] Enregistrement en base des deux formulaires de contact (pour l'instant ils n'envoient qu'un email)

### Frontend cobrand (tout à créer)
- [ ] `cobrand/App.vue` — routage hash + chargement données collecte (Inoé)
- [ ] `cobrand/views/Accueil.vue` (Elia)
- [ ] `cobrand/views/Prevention.vue` — scrollytelling (Loïc)
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking (Inoé)
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking (Inoé)
- [ ] `cobrand/composables/useQuizStore.js` (Inoé)

### Frontend dashboard (UI mockée dans feature/dashboard — à connecter au vrai back)
- [ ] Merger `feature/dashboard` dans develop
- [ ] Remplacer `useSessionAuth` mock par vraie auth Sanctum
- [ ] Remplacer `useCollectes` mock par appels API réels
- [ ] Ajouter le champ `capacity` dans `CollecteForm.vue` (absent du mock actuel)
- [ ] Connecter `Metriques.vue` à `GET /api/v1/metrics`

### Accessibilité
- [ ] Recréer `fix/accessibilite-v2` depuis develop HEAD (l'ancienne branche est trop en retard)
- [ ] Récupérer les alts images : `git cherry-pick 63f3b65`
- [ ] Labels sur tous les champs de formulaire (`Home.vue`, `Information.vue`)
- [ ] Focus trap sur la modale des critères (`Trophees.vue`)

### Bugs / dette technique
- [ ] Renommer `app/Http/Controllers/API/` → `app/Http/Controllers/Api/` (namespace Linux-safe) ← **dans les fondations**
- [ ] Supprimer `resources/js/app.js` (import cassé, fichier inutile) ← **dans les fondations**
- [ ] Refactoriser `ApiTropheeController` : supprimer le N+1 et calculer `participant_count` depuis `quiz_events.onedoc_clicked` (à faire après que les tables de tracking soient en place)
- [ ] Remplacer `fetch()` natif dans `Home.vue` et `Information.vue` par `useFetchApi`

---

## Phases d'implémentation

### Phase 1 — Fondations (Inoé, seul·e, ce soir + demain matin)

**Pourquoi d'abord :** Ces modifications touchent les fichiers que tout le monde va utiliser (migrations, routes, namespace). Si quelqu'un commence à coder le cobrand ou le dashboard back en parallèle sans ces fondations, il y a des risques de conflits sur les routes et d'incohérences de schéma.

**Issue GitHub :** "Fondations — schéma DB, modèles, routes API par domaine, fix namespace"
**Branche :** `chore/foundations`

| Tâche | Fichiers touchés |
|-------|-----------------|
| Renommer `API/` → `Api/` dans les controllers + MAJ namespaces | `app/Http/Controllers/` |
| Supprimer `resources/js/app.js` | `resources/js/app.js` |
| Modifier migration `collections` : supprimer `nb_registered`, ajouter `capacity`, inline `logo_url` nullable | `database/migrations/2026_05_26_131534_collections.php` |
| Supprimer la migration séparée `make_logo_url_nullable` (inlinée) | `database/migrations/2026_05_29_183508_*.php` |
| Mettre à jour `Collection::$fillable` | `app/Models/Collection.php` |
| Mettre à jour le seeder : retirer `nb_registered` des `Collection::create()` | `database/seeders/DatabaseSeeder.php` |
| Nouvelles migrations : `quiz_events`, `page_events`, `contact_requests`, `pme_contacts` | `database/migrations/` |
| Nouveaux modèles : `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact` | `app/Models/` |
| Créer `routes/api/public.php`, `routes/api/dashboard.php`, `routes/api/cobrand.php` | `routes/api/` |
| Mettre à jour `routes/api.php` pour inclure les 3 fichiers | `routes/api.php` |

> Voir le détail étape par étape dans [`plan-fondations-ce-soir.md`](plan-fondations-ce-soir.md).

---

### Phase 2 — Dashboard UI (Loïc, en parallèle de la Phase 1)

**Pourquoi maintenant :** Le dashboard UI est déjà fait dans `feature/dashboard`. Il faut le merger avant que Loïc parte sur les maquettes, pendant qu'il est encore disponible pour répondre aux questions de review.

**Branche existante :** `feature/dashboard` (origin/feature/dashboard)

**Avant d'ouvrir la PR (Loïc) :**
```bash
git checkout feature/dashboard
git merge develop   # récupère les fondations de la Phase 1
# résoudre ses propres conflits (peu probables — branches orthogonales)
git push
```

**Ce que la Phase 1 change dans son code :**
- `nb_registered` n'existe plus → son mock utilise `nb_inscrits` (nom différent, pas de conflit direct)
- `capacity` ajouté en DB → son `CollecteForm.vue` n'a pas encore ce champ : **à ajouter en Phase 7**
- Les routes API sont réorganisées → n'affecte pas son code front-end

**Après merge :** Loïc passe sur les maquettes.

---

### Phase 3 — Backend dashboard + auth Sanctum (Inoé, ~2–3 jours)

**Prérequis :** Phase 1 terminée et mergée.

**Branche :** `feature/backend-dashboard`

| Tâche | Fichier cible |
|-------|--------------|
| Auth login/logout Sanctum | `app/Http/Controllers/Api/v1/AuthController.php`, `routes/api/dashboard.php` |
| CRUD collectes : GET /collections, POST, PUT, GET /{id} | `app/Http/Controllers/Api/v1/CollectionController.php` |
| Upload logo (storage + lien public) | Intégré dans CollectionController |
| Endpoint cobrand public : GET /api/v1/cobrand/{token} | `app/Http/Controllers/Api/v1/CobrandController.php`, `routes/api/cobrand.php` |
| GET /api/v1/metrics (auth) | `app/Http/Controllers/Api/v1/MetricsController.php`, `routes/api/dashboard.php` |
| Enregistrement en base des demandes de contact (les deux controllers) | `ApiContactController.php`, `ApiPmeContactController.php` |

---

### Phase 4 — Backend tracking (Inoé, ~1 jour, après Phase 3)

**Branche :** `feature/backend-tracking`

| Tâche | Fichier cible |
|-------|--------------|
| `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |

---

### Phase 5 — Cobrand : squelette App.vue (Inoé)

**Prérequis :** Phase 3 terminée (`GET /api/v1/cobrand/{token}` doit exister).

**Branche :** `feature/cobrand-app`

Fichiers :
- `resources/js/cobrand/App.vue`
- `resources/js/cobrand/app.js` (si besoin)

Ce que ça fait :
- Charge les données de la collecte depuis `GET /api/v1/cobrand/{token}`
- Applique les couleurs de co-branding via variables CSS dynamiques
- Gère le routage hash entre les 4 vues (Accueil, Prevention, Quiz, Redirect)
- Injecte les données de la collecte dans chaque vue via props

> Cette branche est intentionnellement petite et fusionnée rapidement — elle débloque Elia et Loïc pour travailler en parallèle sur leurs vues respectives.

---

### Phase 6 — Cobrand : Accueil (Elia) + Prévention (Loïc) — en parallèle après Phase 5

**Prérequis :** Phase 5 mergée.

**Règle de travail :**
- Une branche par vue
- Ne toucher QUE les fichiers listés — aucun fichier partagé
- Sync avec develop obligatoire avant chaque PR

---

**Tâche 6A — `cobrand/views/Accueil.vue` (Elia)**

**Branche :** `feature/cobrand-accueil`

Fichiers autorisés :
- `resources/js/cobrand/views/Accueil.vue` uniquement

Ce que ça fait :
- Affiche les informations de la collecte (dates, lieu, entreprise partenaire) injectées via props par App.vue
- Affiche le compteur d'inscrits (calculé côté back, transmis dans la réponse de `/api/v1/cobrand/{token}`)
- Bouton "Commencer" → navigate vers Prévention

---

**Tâche 6B — `cobrand/views/Prevention.vue` (Loïc)**

**Branche :** `feature/cobrand-prevention`

Fichiers autorisés :
- `resources/js/cobrand/views/Prevention.vue` uniquement

Ce que ça fait :
- Contenu de prévention en scrollytelling
- Émet les événements `prevention_entered` / `prevention_exited` via un emit vers App.vue (App.vue appelle l'API tracking — ne pas faire d'appel HTTP directement depuis Prevention.vue, coordonner avec Inoé)
- Bouton "Continuer" → navigate vers Quiz

---

### Phase 7 — Cobrand : Quiz + Redirect (Inoé)

**Prérequis :** Phase 4 (tracking API) + Phase 5 (App.vue).

Ces deux vues sont les plus complexes (logique d'élimination P1, skip P2, tracking détaillé).

**Tâche 7A — `useQuizStore.js` + `cobrand/views/Quiz.vue`**

**Branche :** `feature/cobrand-quiz`

Fichiers :
- `resources/js/cobrand/composables/useQuizStore.js`
- `resources/js/cobrand/views/Quiz.vue`

**Tâche 7B — `cobrand/views/Redirect.vue`**

**Branche :** `feature/cobrand-redirect` (peut partir de `feature/cobrand-quiz`)

---

### Phase 8 — Dashboard : connexion au back réel (Loïc, après Phase 3)

**Prérequis :** Phase 3 terminée (back dashboard + auth prêts).

**Branche :** `feature/dashboard-api`

Fichiers à modifier (uniquement dans `resources/js/dashboard/`) :
- `composables/useSessionAuth.js` → remplacer mock par appels Sanctum réels (`POST /api/v1/auth/login`, `GET /api/v1/user`)
- `composables/useCollectes.js` → remplacer `donneesMock` par `GET /api/v1/collections`
- `views/CollecteForm.vue` → brancher `POST`/`PUT /api/v1/collections` **+ ajouter le champ `capacity`** (absent du mock actuel)
- `views/Metriques.vue` → brancher `GET /api/v1/metrics`

> Le comptage d'inscrits (`nb_inscrits` dans le mock) vient du calcul dynamique côté back : `COUNT DISTINCT session_id WHERE event_type = 'onedoc_clicked'` dans `quiz_events`. Ce n'est plus un champ DB.

---

### Phase 9 — Accessibilité (Elia, en parallèle des autres phases)

**Branche :** `fix/accessibilite-v2` (nouvelle, depuis develop HEAD — **ne pas réutiliser l'ancienne branche `fix/accessibilite`**)

```bash
git checkout develop
git checkout -b fix/accessibilite-v2
git cherry-pick 63f3b65   # récupère le commit "Add alts to images"
```

Puis compléter :
- Labels sur tous les champs de formulaire (`Home.vue`, `Information.vue`)
- Focus trap sur la modale des critères (`Trophees.vue`)

---

## Résumé des dépendances

```
Phase 1 (fondations, Inoé)  ←── À faire ce soir
  ├── Phase 2 (dashboard UI, Loïc) ← peut démarrer en parallèle
  └── Phase 3 (backend dashboard, Inoé)
        ├── Phase 4 (tracking backend, Inoé)
        │     └── Phase 7 (cobrand quiz/redirect, Inoé)
        ├── Phase 5 (cobrand App.vue squelette, Inoé) ← après Phase 3
        │     ├── Phase 6A (cobrand accueil, Elia) ← après Phase 5
        │     └── Phase 6B (cobrand prévention, Loïc) ← après Phase 5
        └── Phase 8 (dashboard API, Loïc) ← après Phase 3
```

Phase 9 (accessibilité, Elia) : indépendante, peut être faite à tout moment.

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Qui y touche | Ordre |
|---------|-------------|-------|
| `routes/api.php` | Inoé (Phase 1) | En premier — plus personne après |
| `routes/api/cobrand.php` | Inoé (Phase 3, 4) | Séquentiel par Inoé |
| `routes/api/dashboard.php` | Inoé (Phase 3) | Séquentiel par Inoé |
| `resources/js/cobrand/App.vue` | Inoé uniquement (Phase 5) | — |
| `resources/js/cobrand/composables/useQuizStore.js` | Inoé uniquement (Phase 7) | — |
| `app/Models/Collection.php` | Inoé (Phase 1, 3) | Séquentiel par Inoé |

---

## Checklist avant merge dans `main`

- [ ] Toutes les phases 1–8 terminées et mergées dans develop
- [ ] Phase 9 (accessibilité) terminée
- [ ] Variables d'environnement production configurées sur le serveur (Infomaniak)
- [ ] Test de bout en bout : parcours employé cobrandé complet (Accueil → Prévention → Quiz → Onedoc)
- [ ] Test de bout en bout : CTS crée une collecte dans le dashboard
- [ ] Review finale du dashboard métriques avec des données réelles de test
