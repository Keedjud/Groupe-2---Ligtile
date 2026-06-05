# Plan d'implémentation — Fin de projet

<<<<<<< HEAD
> Mis à jour le 2 juin 2026 (après Phase 5B). Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.
=======
> Mis à jour le 3 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.
>>>>>>> develop

---

## L'équipe

| Développeur | Rôle dans la suite |
|-------------|-------------------|
<<<<<<< HEAD
| **Loïc** | Dashboard complet : UI, backend (auth Sanctum, CRUD collectes, métriques) et connexion au vrai back — déjà commencé en local |
| **Elia** | Fix et finitions du site public |
| **Inoé** | Cobrand complet (endpoint back + App.vue + Accueil + Quiz + Redirect + tracking), reviews, coordination |
=======
| **Loïc** | Maquettes — en pause sur le code. Reprend sur `cobrand/views/Prevention.vue` (Phase 7C) quand disponible. |
| **Elia** | Fix et finitions du site public uniquement — ne touche pas au dashboard pour éviter les conflits |
| **Inoé** | Tous les fixes dashboard (Phase 4B), cobrand complet (Phases 6 + 7), coordination |
>>>>>>> develop

**Règle d'or :** Avant d'ouvrir une PR, faire `git merge develop` sur sa branche et résoudre ses propres conflits. Un reviewer ne résout jamais les conflits d'une autre personne.

---

<<<<<<< HEAD
## Bugs identifiés en production

| Priorité | Bug | Responsable |
|----------|-----|-------------|
| 🔴 Urgent | Page Trophées cassée — `ApiTropheeController` utilise `nb_registered` supprimé de la DB | Inoé |
| 🟡 Normal | Navigation : lien actif non mis en évidence (aucune page n'est surlignée dans le header) | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" affiché à gauche avec les icônes sociales au lieu de la droite avec les liens légaux | Elia |
| 🟡 Normal | Email PME cassé — `contactPme.blade.php` passe un objet `Illuminate\Mail\Message` là où une string est attendue (`htmlspecialchars` crash) | Elia |

---

## État actuel (2 juin 2026)

| Développeur | En cours |
|-------------|---------|
| **Loïc** | Backend dashboard + connexion API — déjà commencé en local (`feature/dashboard` ou branche dédiée) |
| **Elia** | Fix et finitions site public (`fix/public-site`) |
| **Inoé** | Phase 5B terminée — démarre Phase 7 (cobrand frontend, branche `feature/cobrand-app`) |
=======
## Bugs ouverts

| Priorité | Bug | Responsable |
|----------|-----|-------------|
| 🟡 Normal | `CollecteForm.vue` + `CollecteDetail.vue` : aperçu co-branding en temps réel + warning contraste WCAG | Inoé (Phase 4C — en attente maquettes) |
| 🟡 Normal | Navigation : lien actif non mis en évidence dans le header | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" mal positionné | Elia |
| 🟡 Normal | Email confirmation PME : crash `$message->embed()` dans `contactPme-confirmation.blade.php` | Elia |
>>>>>>> develop

---

## Ce qui reste à faire

### Backend
<<<<<<< HEAD
- [x] Migration `collections` : suppression `nb_registered`, ajout `capacity`, `logo_url` nullable
- [x] Migrations `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`, `contact_stats`
- [x] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`, `ContactStat`
- [x] Réorganisation routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [x] **[URGENT]** Fix `ApiTropheeController` : supprimer référence à `nb_registered`, corriger N+1
- [ ] Auth Sanctum : `POST /api/v1/auth/login`, `POST /api/v1/auth/logout` **(Loïc)**
- [ ] CRUD collections : `GET`, `POST`, `PUT /api/v1/collections` **(Loïc)**
- [ ] Upload logo (storage + lien public) **(Loïc)**
- [ ] Métriques : `GET /api/v1/metrics` **(Loïc)**
- [x] Endpoint cobrand public : `GET /api/v1/cobrand/{token}` **(Inoé)**
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event` **(Inoé)**
- [x] Comptage anonyme des demandes de contact : `contact_stats` — juste un horodatage par soumission, aucune donnée personnelle **(Inoé)**

### Frontend site public
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` (Elia)
- [ ] Fix email PME : corriger `resources/views/emails/contactPme.blade.php` — crash `htmlspecialchars` dû à un objet `Message` passé comme string (Elia)

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
=======

<<<<<<< HEAD
### Frontend site public
<<<<<<< HEAD
- [x] Fix nav : lien actif non mis en évidence (Elia)
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite (Elia)
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue` (Elia)
- [ ] Alts sur toutes les images (via `git cherry-pick 63f3b65`) (Elia)
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` (Elia)
- [ ] Fix email PME : corriger `resources/views/emails/contactPme.blade.php` — crash `htmlspecialchars` dû à un objet `Message` passé comme string (Elia)
=======
- [ ] Fix nav : lien actif non mis en évidence **(Elia)**
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite **(Elia)**
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue` **(Elia)**
- [ ] Alts sur toutes les images (via `git cherry-pick 63f3b65`) **(Elia)**
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` **(Elia)**
- [ ] Fix email confirmation PME : remplacer `$message->embed(public_path('images/logo-hug.png'))` par une URL publique (`/images/logo-hug.png`) dans `resources/views/emails/contactPme-confirmation.blade.php` — `contactPme.blade.php` (notification CTS) est OK **(Elia)**
- [ ] `contactPme-confirmation.blade.php` : utiliser `{{ $entreprise }}` pour personnaliser le "Bonjour," (variable passée mais non affichée) **(Elia)**
>>>>>>> develop
- [x] Mentions vie privée sur les deux formulaires de contact
=======
### Frontend site public **(Elia)**
>>>>>>> 9f15ecf2f24718621bc8f3784febe762e17efd59

- [ ] Fix nav : lien actif non mis en évidence — `SiteHeader.vue`, `useNavigation.js`
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite — `SiteFooter.vue`
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue`
- [ ] Focus trap sur la modale des critères — `Trophees.vue`
- [ ] Fix email confirmation PME : remplacer `$message->embed(...)` par une URL publique dans `contactPme-confirmation.blade.php`
- [ ] `contactPme-confirmation.blade.php` : utiliser `{{ $entreprise }}` pour personnaliser le "Bonjour,"

### Frontend dashboard **(Inoé)**

- [ ] Aperçu co-branding + warning contraste WCAG — `CollecteForm.vue`, `useColorContrast.js` **(Phase 4C — en attente maquettes)**
- [ ] Aperçu couleurs primaire + secondaire — `CollecteDetail.vue` **(Phase 4C — en attente maquettes)**

### Frontend cobrand **(Inoé)**

- [ ] `cobrand/App.vue` — routage hash, co-branding CSS, fenêtre de disponibilité
- [ ] `cobrand/views/Accueil.vue`
- [ ] `cobrand/views/Prevention.vue` — scrollytelling **(Loïc quand disponible)**
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking
- [ ] `cobrand/composables/useQuizStore.js`
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2 *(à aligner dans `DashboardMetricsController` après Phase 7D)*
>>>>>>> develop

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations (Inoé) — TERMINÉE

<<<<<<< HEAD
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

> L'endpoint cobrand `GET /api/v1/cobrand/{token}` a été traité par Inoé dans la Phase 5B (terminée).
=======
Namespace, migrations, modèles, routes réorganisées, seeder, vie privée.

---

### Phase 2 — Fix et finitions site public **(Elia)**

**Branche :** `fix/public-site`

| Tâche | Fichier(s) |
|-------|-----------|
| Fix navigation active | `SiteHeader.vue`, `useNavigation.js` |
| Fix footer | `SiteFooter.vue` |
| Alts images — `git cherry-pick 63f3b65` | vues du site public |
| Labels sur champs de formulaire | `Home.vue`, `Information.vue` |
| Focus trap modale | `Trophees.vue` |
| Fix email PME | `resources/views/emails/contactPme.blade.php` |

---

### ✅ Phase 3 — Fix urgent Trophées (Inoé) — TERMINÉE

`ApiTropheeController` corrigé. `participant_count` reste provisoirement à `0` en attendant Phase 6.

---

### ✅ Phase 4 — Dashboard UI (Loïc) — EN COURS

UI complète. `onedoc_url` et `capacity` manquants dans le formulaire → corrigés en Phase 4B.

---

### ✅ Phase 4B — Fix post-audit dashboard (Inoé) — TERMINÉE

**Branche :** `fix/dashboard-post-audit` (mergée le 3 juin 2026)

| Tâche | Fichier(s) cible(s) |
|-------|---------------------|
| ✅ Ajouter `onedoc_url` (requis), `capacity` (requis, ≥ 1), `kit_url` (requis) dans formulaire + validation + adapters | `CollecteForm.vue`, `ManageCollectionController.php`, `useCollectes.js` |
| ✅ Email kit : bouton KDrive (`lienKitComm`) ; suppression attachements `public/kit/` | `CollectionKitMail.php`, `collection-kit.blade.php` |
| ✅ Migration consolidée : `kit_url`, `capacity`, `logo_url` (longText), `onedoc_url` tous NOT NULL | `2026_05_26_131534_collections.php` |
| ✅ Seeder mis à jour : `capacity` et `kit_url` sur toutes les collectes | `DatabaseSeeder.php` |
| ✅ Corriger la redirection async au refresh | `App.vue` |
| ✅ Remplacer sélecteur période par cases à cocher multi-années | `Metriques.vue` |
| ✅ Ajouter filtre `years[]` sur tous les groupes A–E | `DashboardMetricsController.php` |
| ✅ Corriger affichage skip (taux % réel) | `DashboardMetricsController.php`, `Metriques.vue` |
| ✅ Simplifier flow `QuestionFlow.vue` : supprimer `setTimeout` fictifs, fusionner étapes 3+4 | `QuestionFlow.vue` |

---

### Phase 4C — Aperçu co-branding dashboard **(Inoé) ← EN ATTENTE DES MAQUETTES**

**Branche :** à créer depuis `develop` quand les maquettes sont disponibles

| Tâche | Fichier(s) cible(s) |
|-------|---------------------|
| Aperçu co-branding en temps réel + warning contraste WCAG | `CollecteForm.vue`, nouveau `composables/useColorContrast.js` |
| Aperçu couleurs primaire + secondaire en lecture | `CollecteDetail.vue` |

---

### ✅ Phase 5 — Backend dashboard + auth Sanctum (Loïc) — TERMINÉE

Endpoints réels (nommage différent du plan initial) :
- `POST /api/v1/session/connect` — login
- `POST /api/v1/session/disconnect` — logout
- `GET /api/v1/session/current-user` — utilisateur courant
- `GET/POST/PUT/DELETE /api/v1/manage-collections` — CRUD
- `POST /api/v1/manage-collections/{id}/kit/send` — envoi kit co-brandé par email (lien KDrive en pièce centrale)
- `GET /api/v1/analytics-stats` — métriques
>>>>>>> develop

---

### ✅ Phase 5B — Backend cobrand + contact_stats (Inoé) — TERMINÉE

<<<<<<< HEAD
`ApiCobrandController` créé : `GET /api/v1/cobrand/{token}` retourne les données de collecte (couleurs, dates, logo, lien Onedoc, entreprise, adresse) ou 404 si le token est inconnu.

`ContactStat` créé : comptage anonyme des demandes de contact — un horodatage par soumission, aucune donnée personnelle stockée.

---

### Phase 6 — Backend tracking (Inoé, ~1 jour, après Phase 5)
=======
`GET /api/v1/cobrand/{token}` opérationnel. `ContactStat` créé.

---

### ✅ Phase 6 — Backend tracking (Inoé) — TERMINÉE
>>>>>>> develop

**Branche :** `feature/backend-tracking`

| Tâche | Fichier cible |
|-------|--------------|
<<<<<<< HEAD
| `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |

À ce stade, mettre à jour `ApiTropheeController` pour remplacer le `participant_count = 0` provisoire par le vrai calcul depuis `quiz_events`.

---

### Phase 7 — Cobrand (Inoé)

**Prérequis :** Phase 5B terminée (`GET /api/v1/cobrand/{token}` opérationnel).

**Phase 7A — `cobrand/App.vue`** — `feature/cobrand-app`
- Charge les données depuis l'API, applique le co-branding, gère le routage hash
- Vérifier la fenêtre de disponibilité : le site est accessible entre la date de création de la collecte et 3 jours après `end_date` — afficher un message d'indisponibilité sinon

**Phase 7B — `cobrand/views/Accueil.vue`** — `feature/cobrand-accueil`

**Phase 7C — `cobrand/views/Prevention.vue` (Loïc)** — `feature/cobrand-prevention`
- Loïc travaille dessus quand il revient des maquettes, en parallèle de 7D
- Émet `prevention_entered` / `prevention_exited` via `$emit` vers App.vue — ne pas appeler l'API directement

**Phase 7D — Quiz + Redirect (Inoé)** — `feature/cobrand-quiz`
- `cobrand/constants/quizQuestions.js` — slugs stables des questions (P1 + P2). **Règle critique : ne jamais modifier un slug en production sans migrer les données `quiz_events` correspondantes.**
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
=======
| ✅ `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| ✅ `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |
| ✅ Remplacer `participant_count = 0` dans `ApiTropheeController` par calcul réel | `ApiTropheeController.php` |

---

### Phase 7 — Cobrand **(Inoé)**

**Prérequis :** Phase 5B ✅

**7A — `cobrand/App.vue`** (`feature/cobrand-app`) — routage hash, co-branding, fenêtre de disponibilité

**7B — `cobrand/views/Accueil.vue`** (`feature/cobrand-accueil`)

**7C — `cobrand/views/Prevention.vue`** (`feature/cobrand-prevention`) — **(Loïc quand disponible, en parallèle de 7D)**
- Émet `prevention_entered` / `prevention_exited` via `$emit` → ne pas appeler l'API directement

**7D — Quiz + Redirect** (`feature/cobrand-quiz`) — **(Inoé)**
- `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2
- `useQuizStore.js`, `Quiz.vue`, `Redirect.vue`
- **Règle critique : ne jamais modifier un slug en prod sans `UPDATE quiz_events SET question_slug = 'nouveau' WHERE question_slug = 'ancien'`**
- **Après 7D :** aligner les slugs hardcodés dans `DashboardMetricsController::performanceParQuestion()` avec ceux définis ici
- **Après 7D :** renommer `participant_count` en `employees_count` (niveau entreprise) dans `ApiTropheeController` pour cohérence avec le calcul réel depuis `quiz_events`

---

### ✅ Phase 8A + 8B — Nettoyage code mort (`chore/cleanup`) — TERMINÉE

**8A — Fichiers Blade inutilisés**

| Tâche | Fichier(s) |
|-------|-----------|
| ✅ Supprimer la page Laravel par défaut | `resources/views/welcome.blade.php` |
| ✅ Supprimer le layout Blade inutilisé + ViewComponent associé | `resources/views/components/default-layout.blade.php`, `app/View/Components/DefaultLayout.php` |

**8B — Modèles et tables jamais peuplés**

Option A retenue : suppression des modèles et migrations. Le `migrate:fresh --seed` du hook de prod suffit — pas besoin de migration de drop intermédiaire.

| Tâche | Fichier(s) |
|-------|-----------|
| ✅ Supprimer le modèle `ContactRequest` | `app/Models/ContactRequest.php` |
| ✅ Supprimer le modèle `PmeContact` | `app/Models/PmeContact.php` |
| ✅ Retirer les migrations d'origine du dépôt | `2026_06_01_224000_*`, `2026_06_01_224500_*` |

**8C — Nommage `ApiTropheeController`** *(à faire après Phase 7D)*

Au niveau année, `participant_count` retourne le nombre d'**entreprises uniques** — le nom est trompeur. À renommer en `companies_count` une fois que le calcul réel des participants (employees) sera en place à partir des `quiz_events`.
>>>>>>> develop

---

## Résumé des dépendances

```
Phase 1 ✅ (fondations)
<<<<<<< HEAD
  ├── Phase 2 (public site fixes, Elia)          ← en cours
  ├── Phase 3 ✅ (fix trophées, Inoé)
  ├── Phase 4 (dashboard UI, Loïc)               ← en cours en local
  │     └── Phase 5 (backend dashboard, Loïc)
  │           └── Phase 8 (dashboard API, Loïc)
  └── Phase 5B ✅ (backend cobrand, Inoé)
        ├── Phase 6 (tracking, Inoé)              ← après Phase 7D
        └── Phase 7A→D (cobrand frontend, Inoé)  ← démarre maintenant
=======
  ├── Phase 2 (public site fixes, Elia)             ← en cours
  ├── Phase 3 ✅ (fix trophées, Inoé)
  ├── Phase 4 ✅ (dashboard UI, Loïc — mergée)
  │     ├── Phase 4B ✅ (fix post-audit, Inoé — mergée dans develop)
  │     └── Phase 4C (aperçu co-branding, Inoé — en attente maquettes)
  └── Phase 5 ✅ (backend dashboard, Loïc)
        └── Phase 5B ✅ (backend cobrand, Inoé)
              ├── Phase 6 ✅ (tracking, Inoé)
              └── Phase 7A→D (cobrand, Loic)         ← en cours
                    └── aligner slugs dans DashboardMetricsController (après 7D)
                    └── renommer participant_count dans ApiTropheeController (après 7D)
Phase 8A+8B ✅ (cleanup, chore/cleanup — 3 juin 2026)
Phase 8C (renommage ApiTropheeController)            ← après Phase 7D
>>>>>>> develop
```

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Qui y touche | Règle |
|---------|-------------|-------|
<<<<<<< HEAD
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
=======
| `routes/api/cobrand.php` | Inoé (Phase 5B ✅, 6) | Inoé uniquement |
| `routes/api/dashboard.php` | Inoé (Phase 4B) | Inoé uniquement |
| `resources/js/cobrand/App.vue` | Inoé (Phase 7A) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ManageCollectionController.php` | Inoé (Phase 4B) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/DashboardMetricsController.php` | Inoé (Phase 4B + post-7D) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ApiTropheeController.php` | Inoé (Phase 3 ✅, puis 6) | Inoé uniquement |
>>>>>>> develop
