# Plan d'implémentation — Fin de projet

> Mis à jour le 2 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.

---

## L'équipe

| Développeur | Rôle dans la suite |
|-------------|-------------------|
| **Loïc** | Merger le dashboard UI → maquettes → revenir connecter le dashboard au vrai back + page Prévention cobrandée |
| **Elia** | Page Accueil du site cobrandé + accessibilité |
| **Inoé** | Back-end, cobrand App.vue + Quiz + Redirect, reviews, coordination |

**Règle d'or :** Avant d'ouvrir une PR, faire `git merge develop` sur sa branche et résoudre ses propres conflits. Un reviewer ne résout jamais les conflits d'une autre personne.

---

## Actions immédiates — premier jour

### Loïc — à faire ce matin
```bash
git checkout feature/dashboard
git merge develop   # récupère les fondations (migrations, routes, namespace)
# conflits peu probables — branches orthogonales
git push
```
Ouvrir la PR `feature/dashboard` → `develop`. Une fois mergée : passer sur les maquettes.

### Elia — à faire ce matin
Créer la branche d'accessibilité (indépendante, peut démarrer immédiatement) :
```bash
git checkout develop
git checkout -b fix/accessibilite-v2
git cherry-pick 63f3b65   # récupère "Add alts to images" de l'ancienne branche
git push -u origin fix/accessibilite-v2
```
Voir Phase 9 pour le détail des tâches.

### Inoé — à faire ce matin
Merger `chore/foundations` → `develop`, puis démarrer la Phase 3.

---

## Ce qui reste à faire

### Backend
- [x] Migration `collections` : suppression `nb_registered`, ajout `capacity`, `logo_url` nullable
- [x] Migrations `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`
- [x] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`
- [x] Réorganisation routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [ ] Auth Sanctum : `POST /api/v1/auth/login`, `POST /api/v1/auth/logout`
- [ ] CRUD collections : `GET`, `POST`, `PUT /api/v1/collections`
- [ ] Endpoint cobrand public : `GET /api/v1/cobrand/{token}`
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event`
- [ ] Métriques : `GET /api/v1/metrics`
- [ ] Enregistrement en base des deux formulaires de contact (envoient uniquement un email pour l'instant)

### Frontend cobrand
- [ ] `cobrand/App.vue` — routage hash + chargement données collecte (Inoé)
- [ ] `cobrand/views/Accueil.vue` (Elia)
- [ ] `cobrand/views/Prevention.vue` — scrollytelling (Loïc)
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking (Inoé)
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking (Inoé)
- [ ] `cobrand/composables/useQuizStore.js` (Inoé)

### Frontend dashboard
- [x] UI complète dans `feature/dashboard` (Loïc) — Login, Collectes, CollecteDetail, CollecteForm, Metriques
- [ ] Ajouter `onedoc_url` et `capacity` dans `CollecteForm.vue` + mock (Loïc, avant PR)
- [ ] Merger `feature/dashboard` dans `develop`
- [ ] Remplacer auth mock par vraie auth Sanctum
- [ ] Remplacer données mock par appels API réels
- [ ] Connecter `Metriques.vue` à `GET /api/v1/metrics`

### Accessibilité
- [x] Mentions vie privée ajoutées sur les deux formulaires de contact
- [ ] Alts sur toutes les images (via `git cherry-pick 63f3b65` dans `fix/accessibilite-v2`)
- [ ] Labels sur tous les champs de formulaire (`Home.vue`, `Information.vue`)
- [ ] Focus trap sur la modale des critères (`Trophees.vue`)

### Dette technique
- [x] Namespace `API/` → `Api/` (compat Linux/prod)
- [x] Suppression `resources/js/app.js` (artefact inutilisé)
- [ ] Refactoriser `ApiTropheeController` : N+1 queries + calculer `participant_count` depuis `quiz_events` (à faire après Phase 4)
- [ ] Remplacer `fetch()` natif dans `Home.vue` et `Information.vue` par `useFetchApi`

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations (Inoé) — TERMINÉE

Branche `chore/foundations` — à merger dans `develop` ce matin.

Ce qui a été fait :
- Namespace `API/` → `Api/` (compat Linux)
- Suppression `resources/js/app.js`
- Migration `collections` : `nb_registered` supprimé, `capacity` ajouté, `logo_url` nullable
- Migrations : `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`
- Modèles : `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`
- Routes API réorganisées en `routes/api/{public,dashboard,cobrand}.php`
- Seeder nettoyé
- Mentions vie privée sur les formulaires de contact
- Documentation tracking (concept.md) mise à jour

---

### Phase 2 — Dashboard UI (Loïc)

**À faire ce matin.** Deux champs manquent dans `CollecteForm.vue` avant que la PR puisse être ouverte :

| Champ | Type | Description |
|-------|------|-------------|
| `onedoc_url` | `string`, requis | URL de la plateforme Onedoc pour l'inscription des employés — le CTS la saisit à la création de la collecte |
| `capacity` | `number`, optionnel | Nombre de créneaux disponibles — utilisé pour le taux de remplissage dans les métriques |

Ces deux champs sont dans le schéma DB (`collections`) et doivent être présents dans le formulaire et dans les données mock de `useCollectes.js`.

**Ensuite :**
```bash
git checkout feature/dashboard
git merge develop   # récupère les fondations (migrations, routes, namespace)
git push
```
Ouvrir la PR. Une fois mergée : passer sur les maquettes.

**Ce que la Phase 1 change dans son code :**
- `nb_registered` n'existe plus en DB → son mock utilise `nb_inscrits` (nom différent, pas de conflit)
- Routes API réorganisées → sans impact sur son code frontend

**Après merge :** Loïc passe sur les maquettes.

---

### Phase 3 — Backend dashboard + auth Sanctum (Inoé, ~2–3 jours)

**Prérequis :** Phase 1 mergée.
**Branche :** `feature/backend-dashboard`

| Tâche | Fichier cible |
|-------|--------------|
| Auth login/logout Sanctum | `app/Http/Controllers/Api/v1/AuthController.php`, `routes/api/dashboard.php` |
| CRUD collectes | `app/Http/Controllers/Api/v1/CollectionController.php` |
| Upload logo (storage + lien public) | Intégré dans `CollectionController` |
| Endpoint cobrand public : `GET /api/v1/cobrand/{token}` | `app/Http/Controllers/Api/v1/CobrandController.php`, `routes/api/cobrand.php` |
| `GET /api/v1/metrics` (auth) | `app/Http/Controllers/Api/v1/MetricsController.php`, `routes/api/dashboard.php` |
| Enregistrement en base des deux formulaires de contact | `ApiContactController.php`, `ApiPmeContactController.php` |

---

### Phase 4 — Backend tracking (Inoé, ~1 jour, après Phase 3)

**Branche :** `feature/backend-tracking`

| Tâche | Fichier cible |
|-------|--------------|
| `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |

---

### Phase 5 — Cobrand : squelette App.vue (Inoé, après Phase 3)

**Prérequis :** `GET /api/v1/cobrand/{token}` opérationnel.
**Branche :** `feature/cobrand-app`

Fichiers : `resources/js/cobrand/App.vue`, `resources/js/cobrand/app.js`

Ce que ça fait :
- Charge les données de la collecte depuis l'API
- Applique les couleurs de co-branding via variables CSS dynamiques
- Gère le routage hash entre les 4 vues

> Branche intentionnellement courte — mergée rapidement pour débloquer Elia et Loïc.

---

### Phase 6 — Cobrand : Accueil (Elia) + Prévention (Loïc) — en parallèle après Phase 5

**Prérequis :** Phase 5 mergée dans `develop`.

**Règle de travail : une branche par vue, un seul fichier par branche, sync develop obligatoire avant PR.**

---

**Tâche 6A — `cobrand/views/Accueil.vue` (Elia)**
**Branche :** `feature/cobrand-accueil`

- Fichier autorisé : `resources/js/cobrand/views/Accueil.vue` uniquement
- Affiche les infos de la collecte (dates, lieu, co-branding) transmises par App.vue via props
- Affiche le compteur d'inscrits
- Bouton "Commencer" → navigation vers Prévention

---

**Tâche 6B — `cobrand/views/Prevention.vue` (Loïc)**
**Branche :** `feature/cobrand-prevention`

- Fichier autorisé : `resources/js/cobrand/views/Prevention.vue` uniquement
- Contenu scrollytelling de prévention
- Émet `prevention_entered` / `prevention_exited` via un `$emit` vers App.vue — **ne pas appeler l'API de tracking directement depuis cette vue, coordonner avec Inoé**
- Bouton "Continuer" → navigation vers Quiz

---

### Phase 7 — Cobrand : Quiz + Redirect (Inoé, après Phases 4 et 5)

**Branche :** `feature/cobrand-quiz`

Fichiers :
- `resources/js/cobrand/composables/useQuizStore.js`
- `resources/js/cobrand/views/Quiz.vue`
- `resources/js/cobrand/views/Redirect.vue` (peut partir de `feature/cobrand-quiz`)

---

### Phase 8 — Dashboard : connexion au back réel (Loïc, après Phase 3)

**Prérequis :** Phase 3 terminée.
**Branche :** `feature/dashboard-api`

Fichiers à modifier (uniquement dans `resources/js/dashboard/`) :
- `composables/useSessionAuth.js` → remplacer mock par appels Sanctum réels
- `composables/useCollectes.js` → remplacer `donneesMock` par `GET /api/v1/collections`
- `views/CollecteForm.vue` → brancher `POST`/`PUT /api/v1/collections`
- `views/Metriques.vue` → brancher `GET /api/v1/metrics`

> `nb_inscrits` dans le mock correspond au `COUNT DISTINCT session_id WHERE event_type = 'onedoc_clicked'` calculé côté back — ce n'est plus un champ DB.

---

### Phase 9 — Accessibilité (Elia, en parallèle de tout)

**Branche :** `fix/accessibilite-v2` (ne pas réutiliser l'ancienne `fix/accessibilite`)

```bash
git checkout develop
git checkout -b fix/accessibilite-v2
git cherry-pick 63f3b65   # récupère "Add alts to images"
```

Tâches :
- Vérifier et compléter les `alt` sur toutes les images du site public
- Ajouter des `label` (ou `aria-label`) sur tous les champs des formulaires (`Home.vue`, `Information.vue`)
- Focus trap sur la modale des critères dans `Trophees.vue`

---

## Résumé des dépendances

```
Phase 1 ✅ (fondations)
  ├── Phase 2 (dashboard UI, Loïc)  ← ce matin
  └── Phase 3 (backend dashboard, Inoé)
        ├── Phase 4 (tracking, Inoé)
        │     └── Phase 7 (cobrand quiz/redirect, Inoé)
        ├── Phase 5 (cobrand App.vue, Inoé)
        │     ├── Phase 6A (cobrand accueil, Elia)
        │     └── Phase 6B (cobrand prévention, Loïc)
        └── Phase 8 (dashboard API, Loïc)

Phase 9 (accessibilité, Elia) : indépendante, démarre ce matin
```

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Qui y touche | Règle |
|---------|-------------|-------|
| `routes/api/cobrand.php` | Inoé (Phases 3, 4) | Séquentiel — Inoé uniquement |
| `routes/api/dashboard.php` | Inoé (Phase 3) | Séquentiel — Inoé uniquement |
| `resources/js/cobrand/App.vue` | Inoé (Phase 5) | Inoé uniquement |
| `resources/js/cobrand/composables/useQuizStore.js` | Inoé (Phase 7) | Inoé uniquement |

---

## Checklist avant merge dans `main`

- [ ] Phases 1–8 terminées et mergées dans `develop`
- [ ] Phase 9 (accessibilité) terminée
- [ ] Variables d'environnement production configurées sur Infomaniak
- [ ] Test bout en bout : parcours employé cobrandé complet (Accueil → Prévention → Quiz → Onedoc)
- [ ] Test bout en bout : CTS crée une collecte dans le dashboard
- [ ] Review finale du dashboard métriques avec données de test
