# Plan d'implémentation — Fin de projet

> Mis à jour le 2 juin 2026 (décisions post-audit dashboard). Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.

---

## L'équipe

| Développeur | Rôle dans la suite |
|-------------|-------------------|
| **Loïc** | Maquettes — en pause sur le code. Reprend sur `cobrand/views/Prevention.vue` (Phase 7C) quand disponible. |
| **Elia** | Fix et finitions du site public uniquement — ne touche pas au dashboard pour éviter les conflits |
| **Inoé** | Tous les fixes dashboard (Phase 4B), cobrand complet (Phases 6 + 7), coordination |

**Règle d'or :** Avant d'ouvrir une PR, faire `git merge develop` sur sa branche et résoudre ses propres conflits. Un reviewer ne résout jamais les conflits d'une autre personne.

---

## Bugs identifiés en production

| Priorité | Bug | Responsable |
|----------|-----|-------------|
| 🔴 Urgent | `CollecteForm.vue` : champs `onedoc_url` et `capacity` manquants | Inoé |
| 🔴 Urgent | `ManageCollectionController` : `onedoc_url` hardcodé à `null`, `capacity` absent de la validation | Inoé |
| 🔴 Urgent | `useCollectes.js` : `adapterVersApi` ne transmet ni `onedoc_url` ni `capacity` | Inoé |
| 🔴 Urgent | `App.vue` dashboard : redirection cassée au refresh (async/sync mismatch) | Inoé |
| 🟡 Normal | `DashboardMetricsController` : `collectes_recurrentes` filtre `> 2` au lieu de `>= 2` — **corrigé dans ce commit** | ✅ |
| 🟡 Normal | `Metriques.vue` : sélecteur période → remplacer par sélecteur multi-année | Inoé |
| 🟡 Normal | `Metriques.vue` + `DashboardMetricsController` : skip affiché avec `%` mais retourné en nombre brut | Inoé |
| 🟡 Normal | `ManageCollectionController` : alias `nb_registered` → renommer en `nb_inscrits` — **corrigé dans ce commit** | ✅ |
| 🟡 Normal | `CollecteForm.vue` : aperçu co-branding en temps réel + warning contraste WCAG | ⏳ En attente des maquettes |
| 🟡 Normal | `QuestionFlow.vue` : `setTimeout` fictifs + étapes 3+4 à fusionner | Inoé |
| 🟡 Normal | Page Trophées : `ApiTropheeController` utilise `participant_count = 0` provisoire | Inoé (Phase 6) |
| 🟡 Normal | Navigation : lien actif non mis en évidence dans le header | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" mal positionné | Elia |
| 🟡 Normal | Email PME cassé — crash `htmlspecialchars` | Elia |

---

## Décisions actées post-audit (2 juin 2026)

| Sujet | Décision |
|-------|----------|
| `onedoc_url` | Champ manuel : le CTS crée sa collecte sur Onedoc, copie l'URL et la colle dans le formulaire. Champ obligatoire. |
| `capacity` | Champ optionnel (integer nullable). Collectes sans capacity exclues du taux de remplissage. |
| Filtre métriques | Sélection d'année(s) uniquement — multi-select. Remplace le sélecteur mois/trimestre/année. S'applique à tous les groupes A–E. |
| Kit de communication | ⏳ À définir lors d'une prochaine discussion (workflow, contenu, lien avec Onedoc URL) |
| Slugs par question (métriques) | À aligner lors de la Phase 7D une fois `quizQuestions.js` défini |
| Nommage endpoints | Endpoints réels (`/session/connect`, `/manage-collections`, `/analytics-stats`) — plan mis à jour, code cohérent |

---

## Ce qui reste à faire

### Backend
- [x] Migration `collections` : suppression `nb_registered`, ajout `capacity`, `logo_url` nullable
- [x] Migrations `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`, `contact_stats`
- [x] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`, `ContactStat`
- [x] Réorganisation routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [x] Auth Sanctum : `POST /api/v1/session/connect`, `POST /api/v1/session/disconnect`
- [x] CRUD collectes : `GET/POST/PUT/DELETE /api/v1/manage-collections`
- [x] Upload logo (data URL base64, stocké en `longText`)
- [x] Métriques : `GET /api/v1/analytics-stats`
- [x] Endpoint cobrand public : `GET /api/v1/cobrand/{token}`
- [x] Comptage anonyme demandes contact : `contact_stats`
- [x] **Fix `ManageCollectionController`** : `onedoc_url` (required), `capacity` (required, integer ≥ 1), `kit_url` (nullable) ajoutés à la validation, `store()` et `update()`
- [ ] **Fix `DashboardMetricsController`** : ajouter filtre `years[]` sur tous les groupes A–E **(Inoé)**
- [ ] **Fix `DashboardMetricsController`** : calculer le taux de skip en `%` dans `performanceParQuestion()` **(Inoé)**
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event` **(Inoé, Phase 6)**

### Frontend site public
- [x] Fix nav : lien actif non mis en évidence (Elia)
- [x] Fix footer : lien "Accessibilité" à déplacer à droite (Elia)
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue` (Elia)
- [x] Alts sur toutes les images (via `git cherry-pick 63f3b65`) (Elia)
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` (Elia)
- [ ] Fix email PME : corriger `resources/views/emails/contactPme.blade.php` — crash `htmlspecialchars` dû à un objet `Message` passé comme string (Elia)
- [x] Mentions vie privée sur les deux formulaires de contact

### Frontend dashboard — fixes post-audit **(Inoé, Phase 4B)**
- [x] `CollecteForm.vue` : champs `capacity` (obligatoire), `onedoc_url` (obligatoire), `kit_url` (optionnel, lien KDrive) ajoutés
- [x] `useCollectes.js` : `onedoc_url`, `capacity`, `kit_url` dans `adapterDeApi` et `adapterVersApi`
- [ ] `App.vue` : corriger la redirection au refresh — attendre la résolution de `chargerUtilisateur()` avant de vérifier `estConnecte`
- [ ] `Metriques.vue` : remplacer le sélecteur de période par un multi-select d'années ; passer `years[]` à l'API
- [ ] `Metriques.vue` : corriger l'affichage du skip (ne plus ajouter `%` si c'est un nombre brut, ou afficher le vrai taux calculé)
- [ ] `CollecteDetail.vue` : ajouter aperçu des couleurs de co-branding (primaire + secondaire) **(Inoé)**
- [ ] `QuestionFlow.vue` : supprimer les `setTimeout` artificiels, fusionner étapes 3+4 en une seule **(Inoé)**
- [x] `ManageCollectionController` : alias `withCount` renommé `nb_inscrits`
- [x] `CollectionKitMail` : passe `kit_url` (`lienKitComm`) au template email
- [x] `collection-kit.blade.php` : bouton "Télécharger le kit" conditionnel si `lienKitComm` renseigné ; suppression de l'attachement `public/kit/`
- [x] Migration `add_kit_url_to_collections` : champ `kit_url` nullable ajouté

### Frontend cobrand **(Inoé)**
- [ ] `cobrand/App.vue` — routage hash + chargement données collecte
- [ ] `cobrand/views/Accueil.vue`
- [ ] `cobrand/views/Prevention.vue` — scrollytelling **(Loïc quand disponible)**
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking
- [ ] `cobrand/composables/useQuizStore.js`
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2 *(Phase 7D — slugs à aligner ensuite dans `DashboardMetricsController`)*

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations (Inoé) — TERMINÉE

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

### ✅ Phase 4 — Dashboard UI (Loïc) — MERGÉE

UI complète. `onedoc_url` et `capacity` manquants dans le formulaire → corrigés en Phase 4B.

---

### Phase 4B — Fix post-audit dashboard **(Inoé) ← PRIORITÉ IMMÉDIATE**

**Branche :** `fix/dashboard-post-audit`

| Tâche | Fichier(s) cible(s) |
|-------|---------------------|
| Ajouter `onedoc_url` (requis, string) dans formulaire + validation + adapters | `CollecteForm.vue`, `ManageCollectionController.php`, `useCollectes.js` |
| Ajouter `capacity` (optionnel, integer) dans formulaire + validation + adapters | `CollecteForm.vue`, `ManageCollectionController.php`, `useCollectes.js` |
| Corriger la redirection async au refresh | `App.vue` |
| Remplacer sélecteur période par multi-select années | `Metriques.vue` |
| Ajouter filtre `years[]` sur tous les groupes A–E | `DashboardMetricsController.php` |
| Corriger affichage skip (taux % réel) | `DashboardMetricsController.php`, `Metriques.vue` |
| Aperçu co-branding + warning contraste WCAG dans le formulaire | `CollecteForm.vue`, nouveau `useColorContrast.js` — **⏳ contenu aperçu en attente des maquettes** |
| Ajouter aperçu couleurs primaire + secondaire | `CollecteDetail.vue` |
| Simplifier flow `QuestionFlow.vue` : supprimer `setTimeout` fictifs, fusionner étapes 3+4 | `QuestionFlow.vue` |

---

### ✅ Phase 5 — Backend dashboard + auth Sanctum (Loïc) — TERMINÉE

Endpoints réels (nommage différent du plan initial) :
- `POST /api/v1/session/connect` — login
- `POST /api/v1/session/disconnect` — logout
- `GET /api/v1/session/current-user` — utilisateur courant
- `GET/POST/PUT/DELETE /api/v1/manage-collections` — CRUD
- `POST /api/v1/manage-collections/{id}/kit/send` — envoi kit *(à confirmer lors de la discussion kit comm)*
- `GET /api/v1/analytics-stats` — métriques

---

### ✅ Phase 5B — Backend cobrand + contact_stats (Inoé) — TERMINÉE

`GET /api/v1/cobrand/{token}` opérationnel. `ContactStat` créé.

---

### Phase 6 — Backend tracking **(Inoé, ~1 jour)**

**Prérequis :** Phase 5B ✅  
**Branche :** `feature/backend-tracking`

| Tâche | Fichier cible |
|-------|--------------|
| `POST /api/v1/quiz/event` (public, sans auth) | `QuizEventController.php`, `routes/api/cobrand.php` |
| `POST /api/v1/page/event` (public, sans auth) | `PageEventController.php`, `routes/api/cobrand.php` |
| Remplacer `participant_count = 0` dans `ApiTropheeController` par calcul réel | `ApiTropheeController.php` |

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

---

## Résumé des dépendances

```
Phase 1 ✅ (fondations)
  ├── Phase 2 (public site fixes, Elia)             ← en cours
  ├── Phase 3 ✅ (fix trophées, Inoé)
  ├── Phase 4 ✅ (dashboard UI, Loïc — mergée)
  │     └── Phase 4B (fix post-audit, Inoé)         ← PRIORITÉ IMMÉDIATE
  └── Phase 5 ✅ (backend dashboard, Loïc)
        └── Phase 5B ✅ (backend cobrand, Inoé)
              ├── Phase 6 (tracking, Inoé)           ← en cours
              └── Phase 7A→D (cobrand, Inoé)         ← en cours
                    └── aligner slugs dans DashboardMetricsController (après 7D)
```

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Qui y touche | Règle |
|---------|-------------|-------|
| `routes/api/cobrand.php` | Inoé (Phase 5B ✅, 6) | Inoé uniquement |
| `routes/api/dashboard.php` | Inoé (Phase 4B) | Inoé uniquement |
| `resources/js/cobrand/App.vue` | Inoé (Phase 7A) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ManageCollectionController.php` | Inoé (Phase 4B) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/DashboardMetricsController.php` | Inoé (Phase 4B + post-7D) | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ApiTropheeController.php` | Inoé (Phase 3 ✅, puis 6) | Inoé uniquement |

---

## Checklist avant merge final dans `main`

- [ ] Phase 4B terminée et mergée
- [ ] Phase 2 (fixes public) terminée
- [ ] Phase 6 (tracking backend) terminée
- [ ] Phase 7A–D (cobrand complet) terminée
- [ ] Slugs `DashboardMetricsController` alignés avec `quizQuestions.js`
- [ ] Discussion kit de communication conclue et implémentation terminée
- [ ] Variables d'environnement production configurées sur Infomaniak
- [ ] Test bout en bout : parcours employé cobrandé complet (Accueil → Prévention → Quiz → Onedoc)
- [ ] Test bout en bout : CTS crée une collecte (avec `onedoc_url` et `capacity`)
- [ ] Review finale du dashboard métriques avec données de test
