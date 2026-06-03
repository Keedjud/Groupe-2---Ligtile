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
| ✅ Résolu | `CollecteForm.vue` : champs `onedoc_url`, `capacity` et `kit_url` ajoutés | `fix/dashboard-post-audit` |
| ✅ Résolu | `ManageCollectionController` : validation `onedoc_url`, `capacity`, `kit_url` + `store()` / `update()` corrigés | `fix/dashboard-post-audit` |
| ✅ Résolu | `useCollectes.js` : `onedoc_url`, `capacity`, `kit_url` dans les deux adapters | `fix/dashboard-post-audit` |
| ✅ Résolu | `App.vue` dashboard : redirection cassée au refresh — `await chargerUtilisateur()` + garde `verifAuthEnCours` | `fix/dashboard-post-audit` |
| 🟡 Normal | `DashboardMetricsController` : `collectes_recurrentes` filtre `> 2` au lieu de `>= 2` — **corrigé dans ce commit** | ✅ |
| 🟡 Normal | `Metriques.vue` : sélecteur période → remplacer par sélecteur multi-année | Inoé |
| 🟡 Normal | `Metriques.vue` + `DashboardMetricsController` : skip affiché avec `%` mais retourné en nombre brut | Inoé |
| 🟡 Normal | `ManageCollectionController` : alias `nb_registered` → renommer en `nb_inscrits` — **corrigé dans ce commit** | ✅ |
| 🟡 Normal | `CollecteForm.vue` : aperçu co-branding en temps réel + warning contraste WCAG | ⏳ En attente des maquettes |
| 🟡 Normal | `QuestionFlow.vue` : `setTimeout` fictifs + étapes 3+4 à fusionner | Inoé |
| 🟡 Normal | Page Trophées : `ApiTropheeController` utilise `participant_count = 0` provisoire | Inoé (Phase 6) |
| 🟡 Normal | Navigation : lien actif non mis en évidence dans le header | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" mal positionné | Elia |
| 🟡 Normal | Email confirmation PME : crash `$message->embed()` dans `contactPme-confirmation.blade.php` — `$message` non injecté par Laravel 11+ dans les Mailables API `Content::view` | Elia |

---

## Décisions actées post-audit (2 juin 2026)

| Sujet | Décision |
|-------|----------|
| `onedoc_url` | Champ manuel obligatoire : le CTS crée sa collecte sur Onedoc, copie l'URL et la colle dans le formulaire. Pas d'intégration API Onedoc. |
| `capacity` | Champ obligatoire (integer ≥ 1). Collectes sans capacity ne peuvent pas être créées. Utilisé pour le taux de remplissage. |
| Kit de communication | Lien KDrive obligatoire : le CTS prépare les fichiers co-brandés dans un dossier KDrive (1 dossier/entreprise, 1 sous-dossier/collecte) et colle le lien dans le champ `kit_url` du formulaire. Champ requis — l'email de kit ne peut pas être envoyé sans ce lien. |
| Filtre métriques | Sélection d'année(s) uniquement — multi-select. Remplace le sélecteur mois/trimestre/année. S'applique à tous les groupes A–E. |
| Slugs par question (métriques) | À aligner lors de la Phase 7D une fois `quizQuestions.js` défini |
| Nommage endpoints | Endpoints réels (`/session/connect`, `/manage-collections`, `/analytics-stats`) — plan mis à jour, code cohérent |

---

## Ce qui reste à faire

### Backend
- [x] Migration `collections` : suppression `nb_registered`, ajout `capacity` (NOT NULL), `onedoc_url` (NOT NULL), `kit_url` (NOT NULL), `logo_url` en `longText` NOT NULL — migration consolidée en un seul fichier, migrations d'altération intermédiaires supprimées
- [x] Migrations `quiz_events`, `page_events`, `contact_requests`, `pme_contacts`, `contact_stats`
- [x] Modèles `QuizEvent`, `PageEvent`, `ContactRequest`, `PmeContact`, `ContactStat`
- [x] Réorganisation routes API en 3 fichiers (`public.php`, `dashboard.php`, `cobrand.php`)
- [x] Auth Sanctum : `POST /api/v1/session/connect`, `POST /api/v1/session/disconnect`
- [x] CRUD collectes : `GET/POST/PUT/DELETE /api/v1/manage-collections`
- [x] Upload logo (data URL base64, stocké en `longText`)
- [x] Métriques : `GET /api/v1/analytics-stats`
- [x] Endpoint cobrand public : `GET /api/v1/cobrand/{token}`
- [x] Comptage anonyme demandes contact : `contact_stats`
- [x] **Fix `ManageCollectionController`** : `onedoc_url` (required), `capacity` (required, integer ≥ 1), `kit_url` (required) ajoutés à la validation, `store()` et `update()`
- [ ] **Fix `DashboardMetricsController`** : ajouter filtre `years[]` sur tous les groupes A–E **(Inoé)**
- [ ] **Fix `DashboardMetricsController`** : calculer le taux de skip en `%` dans `performanceParQuestion()` **(Inoé)**
- [ ] Tracking : `POST /api/v1/quiz/event`, `POST /api/v1/page/event` **(Inoé, Phase 6)**

### Frontend site public
- [ ] Fix nav : lien actif non mis en évidence **(Elia)**
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite **(Elia)**
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue` **(Elia)**
- [ ] Alts sur toutes les images (via `git cherry-pick 63f3b65`) **(Elia)**
- [ ] Focus trap sur la modale des critères dans `Trophees.vue` **(Elia)**
- [ ] Fix email confirmation PME : remplacer `$message->embed(public_path('images/logo-hug.png'))` par une URL publique (`/images/logo-hug.png`) dans `resources/views/emails/contactPme-confirmation.blade.php` — `contactPme.blade.php` (notification CTS) est OK **(Elia)**
- [ ] `contactPme-confirmation.blade.php` : utiliser `{{ $entreprise }}` pour personnaliser le "Bonjour," (variable passée mais non affichée) **(Elia)**
- [x] Mentions vie privée sur les deux formulaires de contact

### Frontend dashboard — fixes post-audit **(Inoé, Phase 4B)**
- [x] `CollecteForm.vue` : champs `capacity` (obligatoire), `onedoc_url` (obligatoire), `kit_url` (obligatoire, lien KDrive) ajoutés
- [x] `useCollectes.js` : `onedoc_url`, `capacity`, `kit_url` dans `adapterDeApi` et `adapterVersApi`
- [x] `App.vue` : corriger la redirection au refresh — `await chargerUtilisateur()` + garde `verifAuthEnCours` (évite le flash Login)
- [ ] `Metriques.vue` : remplacer le sélecteur de période par un multi-select d'années ; passer `years[]` à l'API
- [ ] `Metriques.vue` : corriger l'affichage du skip (ne plus ajouter `%` si c'est un nombre brut, ou afficher le vrai taux calculé)
- [ ] `CollecteDetail.vue` : ajouter aperçu des couleurs de co-branding (primaire + secondaire) **(Inoé)**
- [ ] `QuestionFlow.vue` : supprimer les `setTimeout` artificiels, fusionner étapes 3+4 en une seule **(Inoé)**
- [x] `ManageCollectionController` : alias `withCount` renommé `nb_inscrits`
- [x] `CollectionKitMail` : passe `kit_url` (`lienKitComm`) au template email
- [x] `collection-kit.blade.php` : bouton "Télécharger le kit" conditionnel si `lienKitComm` renseigné ; suppression de l'attachement `public/kit/`
- [x] `kit_url` (NOT NULL) intégré directement dans la migration d'origine `2026_05_26_131534_collections.php` — pas de migration séparée
- [x] `DashboardMetricsController` : `demandes_contact` corrigé — `ContactRequest::count()` → `ContactStat::count()` (ContactRequest n'est jamais peuplé)
- [x] `Metriques.vue` : label "Entreprises récurrentes (≥ 2 collectes)" — corrigé (était ">2")
- [x] `ManageCollectionController::update()` : branche morte `array_key_exists('logo_url')` supprimée
- [x] `CollecteForm.vue` : `|| null` mort supprimé sur `kit_url`

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
| ✅ Ajouter `onedoc_url` (requis), `capacity` (requis, ≥ 1), `kit_url` (requis) dans formulaire + validation + adapters | `CollecteForm.vue`, `ManageCollectionController.php`, `useCollectes.js` |
| ✅ Email kit : bouton KDrive (`lienKitComm`) ; suppression attachements `public/kit/` | `CollectionKitMail.php`, `collection-kit.blade.php` |
| ✅ Migration consolidée : `kit_url`, `capacity`, `logo_url` (longText), `onedoc_url` tous NOT NULL dans `2026_05_26_131534_collections.php` ; migrations d'altération supprimées | `2026_05_26_131534_collections.php` |
| ✅ Seeder mis à jour : `capacity` et `kit_url` sur toutes les collectes, `logo_url` null remplacé par `/images/logo-hug.png` | `DatabaseSeeder.php` |
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
- `POST /api/v1/manage-collections/{id}/kit/send` — envoi kit co-brandé par email (lien KDrive en pièce centrale)
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
- **Après 7D :** renommer `participant_count` en `employees_count` (niveau entreprise) dans `ApiTropheeController` pour cohérence avec le calcul réel depuis `quiz_events`

---

### Phase 8 — Nettoyage code mort (`chore/cleanup`) **(Inoé ou n'importe)**

**Prérequis :** toutes les phases fonctionnelles terminées (peut être fait en parallèle dès maintenant pour les items non-risqués)

**8A — Fichiers Blade inutilisés**

| Tâche | Fichier(s) |
|-------|-----------|
| Supprimer la page Laravel par défaut (aucune route ne la sert, référence `app.js` inexistant) | `resources/views/welcome.blade.php` |
| Supprimer le layout Blade inutilisé (aucune des 3 views ne l'utilise, référence `app.js` inexistant) | `resources/views/components/default-layout.blade.php` |

**8B — Modèles et tables jamais peuplés**

`ContactRequest` et `PmeContact` sont créés mais jamais écrits : les deux contrôleurs de contact envoient uniquement par email et incrémentent `ContactStat`. Décision à prendre entre :
- **Option A (recommandée)** : supprimer les modèles + migrations + tables (les données transitent par email, `ContactStat` suffit pour le comptage)
- **Option B** : ajouter `ContactRequest::create($validated)` / `PmeContact::create($validated)` dans les contrôleurs pour avoir un historique persisté en base

Dans les deux cas, supprimer la colonne `contact_name` de la migration `contact_requests` — elle n'est ni validée ni utilisée.

| Tâche (Option A) | Fichier(s) |
|-----------------|-----------|
| Supprimer le modèle `ContactRequest` | `app/Models/ContactRequest.php` |
| Supprimer le modèle `PmeContact` | `app/Models/PmeContact.php` |
| Ajouter migration `drop_contact_requests_and_pme_contacts_tables` | nouveau fichier |
| Retirer les migrations d'origine du dépôt | `2026_06_01_224000_*`, `2026_06_01_224500_*` |

**8C — Nommage `ApiTropheeController`** *(à faire après Phase 7D)*

Au niveau année, `participant_count` retourne le nombre d'**entreprises uniques** — le nom est trompeur. À renommer en `companies_count` une fois que le calcul réel des participants (employees) sera en place à partir des `quiz_events`.

---

## Résumé des dépendances

```
Phase 1 ✅ (fondations)
  ├── Phase 2 (public site fixes, Elia)             ← en cours
  ├── Phase 3 ✅ (fix trophées, Inoé)
  ├── Phase 4 ✅ (dashboard UI, Loïc — mergée)
  │     └── Phase 4B (fix post-audit, Inoé)         ← partiellement mergée dans develop
  └── Phase 5 ✅ (backend dashboard, Loïc)
        └── Phase 5B ✅ (backend cobrand, Inoé)
              ├── Phase 6 (tracking, Inoé)           ← à venir
              └── Phase 7A→D (cobrand, Inoé)         ← à venir
                    └── aligner slugs dans DashboardMetricsController (après 7D)
                    └── renommer participant_count dans ApiTropheeController (après 7D)
Phase 8 (cleanup, chore/cleanup)                    ← indépendant, quand disponible
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

- [ ] Phase 4B terminée et mergée (reste : App.vue async, Metriques years[], skip %, CollecteDetail couleurs, QuestionFlow setTimeout)
- [ ] Phase 2 (fixes public) terminée (reste : nav active, footer, alts, labels form, focus trap, email PME confirmation)
- [ ] Phase 6 (tracking backend) terminée
- [ ] Phase 7A–D (cobrand complet) terminée
- [ ] Slugs `DashboardMetricsController` alignés avec `quizQuestions.js`
- [ ] Phase 8A : fichiers Blade morts supprimés (`welcome.blade.php`, `default-layout.blade.php`)
- [ ] Phase 8B : décision prise sur `ContactRequest` / `PmeContact` (supprimer ou persister) et implémentée
- [ ] Variables d'environnement production configurées sur Infomaniak (dont `KDRIVE_URL` si nécessaire)
- [ ] Test bout en bout : parcours employé cobrandé complet (Accueil → Prévention → Quiz → Onedoc)
- [ ] Test bout en bout : CTS crée une collecte (avec `onedoc_url`, `capacity`, `kit_url`)
- [ ] Review finale du dashboard métriques avec données de test
- [ ] `demandes_contact` dans les métriques affiche bien un nombre > 0 après soumission d'un formulaire de contact
