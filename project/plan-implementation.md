# Plan d'implémentation — Fin de projet

> Mis à jour le 2 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.

---

## L'équipe

| Développeur | Rôle dans la suite |
|-------------|-------------------|
| **Loïc** | Dashboard complet : UI, backend (auth Sanctum, CRUD collectes, métriques) et connexion au vrai back — déjà commencé en local |
| **Elia** | Fix et finitions du site public |
| **Inoé** | Cobrand complet (endpoint back + App.vue + Accueil + Quiz + Redirect + tracking), reviews, coordination |

**Règle d'or :** Avant d'ouvrir une PR, faire `git merge develop` sur sa branche et résoudre ses propres conflits. Un reviewer ne résout jamais les conflits d'une autre personne.

---

## Bugs identifiés en production

| Priorité | Bug | Responsable |
|----------|-----|-------------|
| 🔴 Urgent | Page Trophées cassée — `ApiTropheeController` utilise `nb_registered` supprimé de la DB | Inoé |
| 🟡 Normal | Navigation : lien actif non mis en évidence (aucune page n'est surlignée dans le header) | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" affiché à gauche avec les icônes sociales au lieu de la droite avec les liens légaux | Elia |

---

## État actuel (2 juin 2026)

| Développeur | En cours |
|-------------|---------|
| **Loïc** | Backend dashboard + connexion API — déjà commencé en local (`feature/dashboard` ou branche dédiée) |
| **Elia** | Fix et finitions site public (`fix/public-site`) |
| **Inoé** | Démarre la partie cobrand — commencer par l'endpoint back `GET /api/v1/cobrand/{token}` avant d'attaquer le frontend |

---

## Ce qui reste à faire

### Backend
- [x] Migration `collections` : suppression `nb_registered`, ajout `capacity`, `logo_url` nullable
- [x] Migrations `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`
- [x] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`
- [x] Réorganisation routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [x] **[URGENT]** Fix `ApiTropheeController` : supprimer référence à `nb_registered`, corriger N+1
- [ ] Auth Sanctum : `POST /api/v1/auth/login`, `POST /api/v1/auth/logout` **(Loïc)**
- [ ] CRUD collections : `GET`, `POST`, `PUT /api/v1/collections` **(Loïc)**
- [ ] Upload logo (storage + lien public) **(Loïc)**
- [ ] Métriques : `GET /api/v1/metrics` **(Loïc)**
- [ ] Endpoint cobrand public : `GET /api/v1/cobrand/{token}` **(Inoé)**
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event` **(Inoé)**
- [ ] Enregistrement en base des deux formulaires de contact (envoient uniquement un email pour l'instant) **(Inoé)**

### Frontend site public
- [ ] Fix nav : lien actif non mis en évidence (Elia)
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite (Elia)
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue` (Elia)
- [ ] Alts sur toutes les images (via `git cherry-pick 63f3b65`) (Elia)
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` (Elia)
- [x] Mentions vie privée sur les deux formulaires de contact

### Frontend cobrand
- [ ] `cobrand/App.vue` — routage hash + chargement données collecte (Inoé)
- [ ] `cobrand/views/Accueil.vue` (Inoé)
- [ ] `cobrand/views/Prevention.vue` — scrollytelling (Loïc)
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking (Inoé)
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking (Inoé)
- [ ] `cobrand/composables/useQuizStore.js` (Inoé)

### Frontend dashboard (Loïc)
- [x] UI complète dans `feature/dashboard` — Login, Collectes, CollecteDetail, CollecteForm, Metriques
- [ ] Ajouter `onedoc_url` et `capacity` dans `CollecteForm.vue` + mock (avant PR)
- [ ] Merger `feature/dashboard` dans `develop`
- [ ] Remplacer auth mock par vraie auth Sanctum
- [ ] Remplacer données mock par appels API réels
- [ ] Connecter `Metriques.vue` à `GET /api/v1/metrics`

### Dette technique
- [x] Namespace `API/` → `Api/` (compat Linux/prod)
- [x] Suppression `resources/js/app.js` (artefact inutilisé)
- [x] Remplacer `fetch()` natif dans `Home.vue` et `Information.vue` par `useFetchApi`
- [x] Refactoriser `ApiTropheeController` : N+1 queries corrigé (`participant_count` depuis `quiz_events` en Phase 6)

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations (Inoé) — TERMINÉE

- Namespace `API/` → `Api/` (compat Linux)
- Suppression `resources/js/app.js`
- Migration `collections` : `nb_registered` supprimé, `capacity` ajouté, `logo_url` nullable
- Migrations : `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`
- Modèles : `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`
- Routes API réorganisées en `routes/api/{public,dashboard,cobrand}.php`
- Seeder nettoyé
- Mentions vie privée sur les formulaires de contact
- Documentation tracking et RGPD mise à jour

---

### Phase 2 — Fix et finitions site public (Elia)

**Branche :** `fix/public-site`

**Règle :** un commit par fix logique, sync avec `develop` avant PR.

| Tâche | Fichier(s) |
|-------|-----------|
| Fix navigation active — le lien de la page courante doit être mis en évidence | `SiteHeader.vue`, `useNavigation.js` |
| Fix footer — déplacer le lien "Accessibilité" du côté des liens légaux (droite) | `SiteFooter.vue` |
| Alts images manquants — `git cherry-pick 63f3b65` pour récupérer le travail existant | vues du site public |
| Labels sur champs de formulaire (`for`/`id` ou `aria-label`) | `Home.vue`, `Information.vue` |
| Focus trap sur la modale des critères | `Trophees.vue` |

> Elia peut tester les emails en prod en mergant cette branche dans develop puis en ouvrant une PR develop → main.

---

### ✅ Phase 3 — Fix urgent Trophées + refactor (Inoé) — TERMINÉE

**Branche :** `fix/trophees-nb-registered` (mergée)

`ApiTropheeController` corrigé : référence à `nb_registered` supprimée, N+1 éliminé, `participant_count` provisoirement à `0` en attendant le tracking (Phase 6).

---

### Phase 4 — Dashboard UI (Loïc)

**Branche :** `feature/dashboard` — déjà commencée en local.

Ajouter `onedoc_url` et `capacity` dans `CollecteForm.vue`, merger dans `develop`, puis enchaîner directement sur la Phase 5.

---

### Phase 5 — Backend dashboard + auth Sanctum (Loïc, ~2–3 jours)

**Prérequis :** Phase 1 mergée.
**Branche :** `feature/backend-dashboard`

| Tâche | Fichier cible |
|-------|--------------|
| Auth login/logout Sanctum | `app/Http/Controllers/Api/v1/AuthController.php`, `routes/api/dashboard.php` |
| CRUD collectes | `app/Http/Controllers/Api/v1/CollectionController.php` |
| Upload logo (storage + lien public) | Intégré dans `CollectionController` |
| `GET /api/v1/metrics` (auth) | `app/Http/Controllers/Api/v1/MetricsController.php`, `routes/api/dashboard.php` |

> L'endpoint cobrand `GET /api/v1/cobrand/{token}` et l'enregistrement des formulaires de contact sont traités par Inoé dans la Phase 5B.

---

### Phase 5B — Backend cobrand + contacts (Inoé, en parallèle de Phase 5)

**Branche :** `feature/backend-cobrand`

| Tâche | Fichier cible |
|-------|--------------|
| Endpoint cobrand public : `GET /api/v1/cobrand/{token}` | `app/Http/Controllers/Api/v1/CobrandController.php`, `routes/api/cobrand.php` |
| Enregistrement en base des deux formulaires de contact | `ApiContactController.php`, `ApiPmeContactController.php` |

> Prérequis de la Phase 7 : l'endpoint cobrand doit être opérationnel avant de démarrer `cobrand/App.vue`.

---

### Phase 6 — Backend tracking (Inoé, ~1 jour, après Phase 5)

**Branche :** `feature/backend-tracking`

| Tâche | Fichier cible |
|-------|--------------|
| `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |

À ce stade, mettre à jour `ApiTropheeController` pour remplacer le `participant_count = 0` provisoire par le vrai calcul depuis `quiz_events`.

---

### Phase 7 — Cobrand (Inoé, démarre maintenant)

**Prérequis :** Phase 5B terminée (`GET /api/v1/cobrand/{token}` opérationnel).

**Phase 7A — `cobrand/App.vue`** — `feature/cobrand-app`
- Charge les données depuis l'API, applique le co-branding, gère le routage hash

**Phase 7B — `cobrand/views/Accueil.vue`** — `feature/cobrand-accueil`

**Phase 7C — `cobrand/views/Prevention.vue` (Loïc)** — `feature/cobrand-prevention`
- Loïc travaille dessus quand il revient des maquettes, en parallèle de 7D
- Émet `prevention_entered` / `prevention_exited` via `$emit` vers App.vue — ne pas appeler l'API directement

**Phase 7D — Quiz + Redirect (Inoé)** — `feature/cobrand-quiz`
- `useQuizStore.js`, `Quiz.vue`, `Redirect.vue`

---

### Phase 8 — Dashboard : connexion au back réel (Loïc, après Phases 4 + 5)

**Prérequis :** Phase 5 terminée.
**Branche :** `feature/dashboard-api`

- `useSessionAuth.js` → appels Sanctum réels
- `useCollectes.js` → `GET /api/v1/collections`
- `CollecteForm.vue` → `POST`/`PUT /api/v1/collections`
- `Metriques.vue` → `GET /api/v1/metrics`

> `nb_inscrits` dans le mock = `COUNT DISTINCT session_id WHERE event_type = 'onedoc_clicked'` — calculé côté back, plus de champ DB.

---

## Résumé des dépendances

```
Phase 1 ✅ (fondations)
  ├── Phase 2 (public site fixes, Elia)          ← en cours
  ├── Phase 3 ✅ (fix trophées, Inoé)
  ├── Phase 4 (dashboard UI, Loïc)               ← en cours en local
  │     └── Phase 5 (backend dashboard, Loïc)
  │           └── Phase 8 (dashboard API, Loïc)
  └── Phase 5B (backend cobrand, Inoé)           ← démarre maintenant
        ├── Phase 6 (tracking, Inoé)
        └── Phase 7A→D (cobrand frontend, Inoé)
```

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Qui y touche | Règle |
|---------|-------------|-------|
| `routes/api/cobrand.php` | Inoé (Phases 5B, 6) | Séquentiel — Inoé uniquement |
| `routes/api/dashboard.php` | Loïc (Phase 5) | Loïc uniquement |
| `resources/js/cobrand/App.vue` | Inoé (Phase 7A) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ApiTropheeController.php` | Inoé (Phase 3 ✅, puis 6) | Phase 3 déjà mergée |

---

## Checklist avant merge final dans `main`

- [ ] Phases 1–8 terminées et mergées dans `develop`
- [ ] Phase 2 (fixes public) terminée
- [ ] Variables d'environnement production configurées sur Infomaniak
- [ ] Test bout en bout : parcours employé cobrandé complet (Accueil → Prévention → Quiz → Onedoc)
- [ ] Test bout en bout : CTS crée une collecte dans le dashboard
- [ ] Review finale du dashboard métriques avec données de test
