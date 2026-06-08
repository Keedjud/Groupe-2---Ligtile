# Plan d'implémentation — Fin de projet

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
> Mis à jour le 2 juin 2026 (après Phase 5B). Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.
=======
> Mis à jour le 3 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.
>>>>>>> develop
=======
> Mis à jour le 5 juin 2026 (dernière màj : page labels publique + Phase 2B terminée). Ce document définit ce qui reste à faire pour finir le projet.
>>>>>>> develop
=======
> Mis à jour le 5 juin 2026 (dernière màj : Phase 4F terminée — gestion trophées dashboard ; prochaine branche : cobrand Accueil.vue + Prevention.vue). Ce document définit ce qui reste à faire pour finir le projet.
>>>>>>> develop

---

## L'équipe

| Développeur | Rôle |
|-------------|------|
| **Loïc** | Frontend cobrand |
| **Elia** | Frontend cobrand |
| **Inoé** | Backend, coordination, fixes dashboard |

---

## Bugs ouverts

| Priorité | Bug | Fichier(s) |
|----------|-----|-----------|
| 🟡 Normal | Aperçu co-branding en temps réel + warning contraste WCAG | `CollecteForm.vue`, `useColorContrast.js` (Phase 4C — en attente maquettes) |

---

## Note — Mise à jour automatique des pages publiques

Les pages publiques (Trophées, Labels) ne font **pas de cache** : chaque chargement interroge directement la base de données via l'API. Toute modification effectuée depuis le dashboard (nouveau trophée, nouvelle collecte, nouveau label) est donc immédiatement visible sur le site public au prochain chargement de la page. Pas de travail supplémentaire nécessaire pour ce comportement.

---

## Ce qui reste à faire

<<<<<<< HEAD
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
=======
### Frontend dashboard
>>>>>>> develop

- [x] Adapter `CollecteForm.vue` aux nouveaux champs backend — `venue_*`, `contact_email`, `contact_phone` (Phase 4D)
- [x] Adapter `CollecteDetail.vue` à l'affichage des nouveaux champs (Phase 4D)
- [x] Gestion des entreprises — nouvelle vue listant les entreprises et leurs contacts, avec édition (Phase 4E)
- [x] Gestion des trophées — nouvel onglet + formulaire pour saisir les lauréats d'une nouvelle année (Phase 4F)
- [ ] Aperçu co-branding + warning contraste WCAG — `CollecteForm.vue`, `useColorContrast.js` (Phase 4C — en attente maquettes)
- [ ] Aperçu couleurs primaire + secondaire en lecture — `CollecteDetail.vue` (Phase 4C — en attente maquettes)

### Frontend cobrand

<<<<<<< HEAD
- [ ] `cobrand/App.vue` — routage hash, co-branding CSS, fenêtre de disponibilité
- [ ] `cobrand/views/Accueil.vue`
- [ ] `cobrand/views/Prevention.vue` — scrollytelling
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking
- [ ] `cobrand/composables/useQuizStore.js`
<<<<<<< HEAD
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2 *(à aligner dans `DashboardMetricsController` après Phase 7D)*
>>>>>>> develop
=======
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2
>>>>>>> develop
=======
- [x] `cobrand/App.vue` — routage hash, co-branding CSS (fenêtre de disponibilité côté frontend à finaliser avec le backend)
- [ ] `cobrand/views/Accueil.vue` — placeholder actuel, à implémenter (Phase 7B)
- [ ] `cobrand/views/Prevention.vue` — placeholder actuel, scrollytelling à implémenter (Phase 7C)
- [x] `cobrand/views/Quiz.vue` — P1 + P2 + tracking
- [x] `cobrand/views/Redirect.vue` — page Onedoc + tracking
- [x] `cobrand/composables/useQuizStore.js`
- [x] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2

### Backend cobrand

- [ ] `ApiCobrandController::show()` — ajouter vérification fenêtre de disponibilité (404 si avant `created_at` ou après `end_date + 7j`)
>>>>>>> develop

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations — TERMINÉE

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

### ✅ Phase 2 — Fix et finitions site public — TERMINÉE

| Tâche | Fichier(s) | Détail |
|-------|-----------|--------|
| ✅ Fix logo HUG | `SiteHeader.vue` | `href="/"` → `href="#/home"` |
| ✅ Fix navigation active | `PublicDefaultLayout.vue` | `defineProps` manquant — `current` était toujours `undefined` |
| ✅ Fix footer | `SiteFooter.vue` | "Accessibilité" déplacé en dernier + faute de frappe corrigée |
| ✅ Labels `for`/`id` sur les formulaires | `Home.vue`, `Information.vue` | 7 champs liés correctement |
| ✅ Focus trap modale critères | `Trophees.vue` | `role="dialog"`, `aria-modal`, `aria-labelledby`, Escape, Tab cyclique, focus auto |

---

### ✅ Phase 2B — Page labels publique : filtre actif / échu — TERMINÉE

| Tâche | Fichier(s) | Détail |
|-------|-----------|--------|
| ✅ Filtre `?status=active\|expired` | `ApiLabelCompanyController.php` | Charge le bon label selon le contexte ; une entreprise avec un label échu ET un label actif apparaît dans les deux onglets |
| ✅ Suppression filtre année | `ApiLabelCompanyController.php`, `Label.vue` | `years()` + route `/label-years` retirés |
| ✅ Toggle "Labellisées" / "Labels échus" | `Label.vue` | Remplace le select année ; titre et description réactifs |
| ✅ Phrase de période dans la carte | `LabelCard.vue` | "Est labelisé de X à Y." / "A été labelisé de X à Y." selon `end_date` |

---

### ✅ Phase 3 — Fix urgent Trophées — TERMINÉE

`ApiTropheeController` corrigé. `participant_count` reste provisoirement à `0` en attendant Phase 6.

---

### ✅ Phase 4 — Dashboard UI — TERMINÉE

---

### ✅ Phase 4B — Fix post-audit dashboard — TERMINÉE

**Branche :** `fix/dashboard-post-audit` (mergée)

| Tâche | Fichier(s) |
|-------|-----------|
| ✅ `onedoc_url`, `capacity`, `kit_url` dans formulaire + validation | `CollecteForm.vue`, `ManageCollectionController.php` |
| ✅ Email kit : bouton KDrive, suppression attachements `public/kit/` | `CollectionKitMail.php`, `collection-kit.blade.php` |
| ✅ Corriger la redirection async au refresh | `App.vue` |
| ✅ Remplacer sélecteur période par cases à cocher multi-années | `Metriques.vue` |
| ✅ Filtre `years[]` sur tous les groupes A–E | `DashboardMetricsController.php` |
| ✅ Corriger affichage skip (taux % réel) | `DashboardMetricsController.php`, `Metriques.vue` |
| ✅ Simplifier flow `QuestionFlow.vue` | `QuestionFlow.vue` |

---

### Phase 4C — Aperçu co-branding dashboard ← EN ATTENTE DES MAQUETTES

| Tâche | Fichier(s) |
|-------|-----------|
| Aperçu co-branding en temps réel + warning contraste WCAG | `CollecteForm.vue`, nouveau `composables/useColorContrast.js` |
| Aperçu couleurs primaire + secondaire en lecture | `CollecteDetail.vue` |

---

### ✅ Phase 4D — Adaptation frontend dashboard au nouveau modèle de données — TERMINÉE

Correction centralisée dans `useCollectes.js` (adaptateurs `adapterDeApi` / `adapterVersApi`) :
- `adapterDeApi` lit désormais `c.venue_street`, `c.contact_email`, etc. depuis la réponse API
- `adapterVersApi` envoie `venue_street`, `contact_email`, etc. à la racine du payload
- `CollecteForm.vue` (mode édition), `CollecteDetail.vue` et `QuestionFlow.vue` mis à jour pour lire `collecte.contact_email` / `collecte.contact_phone` au lieu de `collecte.entreprise.email/telephone`
- `ManageCollectionController.update()` corrigé pour retourner `nb_inscrits` (loadCount manquant)

---

### ✅ Phase 4E — Gestion des entreprises dans le dashboard — TERMINÉE

- `ManageCompanyController` : `index` (GET + ?search=), `show`, `update`
- Routes `GET/PUT /api/v1/companies` et `/companies/{company}` dans `dashboard.php`
- `useCompanies.js` : composable avec `chargerEntreprises`, `chargerEntreprise`, `mettreAJourEntreprise`
- `Companies.vue` : tableau avec recherche debounce, clic → fiche
- `CompanyDetail.vue` : fiche lecture + édition inline (nom, nb_employés, adresse siège, contact)
- `SidebarNav.vue` + `App.vue` : entrée "Entreprises" + routes `#/entreprises` et `#/entreprises/:id`

---

### ✅ Phase 4F — Gestion des trophées dans le dashboard — TERMINÉE

Nouvel onglet permettant au CTS de saisir les lauréats d'une nouvelle année en sélectionnant des entreprises déjà en base.

**Comportement :**
- La page **Trophées** du site public se met à jour automatiquement (pas de cache — voir note en haut du document)
- Un seul podium par année — si une année existe déjà, l'interface propose de la modifier plutôt que d'en créer une nouvelle

**Backend à créer :**

| Route | Controller | Action |
|-------|-----------|--------|
| `GET /api/v1/manage-trophees` | `ManageTropheeController` | Liste des années existantes + lauréats |
| `POST /api/v1/manage-trophees` | `ManageTropheeController` | Créer les trophées d'une nouvelle année (3 entreprises + rangs) |
| `PUT /api/v1/manage-trophees/{year}` | `ManageTropheeController` | Modifier les lauréats d'une année existante |
| `DELETE /api/v1/manage-trophees/{year}` | `ManageTropheeController` | Supprimer le podium d'une année |

Le `store()` crée 3 `Trophee` (`Trophée Or/Argent/Bronze {année}`) et les attache aux entreprises via `company_trophee` avec leur rang.

**Frontend à créer :**

- `dashboard/views/Trophees.vue` — liste des podiums par année (tableau) + bouton "Nouveau podium"
- Formulaire intégré (ou modale) : sélecteur d'année + 3 champs "entreprise" avec autocomplete sur les entreprises en base
- Ajouter l'entrée dans `SidebarNav.vue`

> **Remarque :** les trophées du seeder utilisent des noms figés (`'Trophée Or 2021'`, etc.) — le controller devra générer ces noms automatiquement depuis l'année saisie pour rester cohérent.

---

### ✅ Phase 5 — Backend dashboard + auth Sanctum — TERMINÉE

Endpoints :
- `POST /api/v1/session/connect` — login
- `POST /api/v1/session/disconnect` — logout
<<<<<<< HEAD
- `GET /api/v1/session/current-user` — utilisateur courant
- `GET/POST/PUT/DELETE /api/v1/manage-collections` — CRUD
- `POST /api/v1/manage-collections/{id}/kit/send` — envoi kit co-brandé par email (lien KDrive en pièce centrale)
- `GET /api/v1/analytics-stats` — métriques
>>>>>>> develop
=======
- `GET /api/v1/session/current-user`
- `GET/POST/PUT/DELETE /api/v1/manage-collections`
- `POST /api/v1/manage-collections/{id}/kit/send`
- `GET /api/v1/analytics-stats`
>>>>>>> develop

---

### ✅ Phase 5B — Backend cobrand + refactoring modèle de données — TERMINÉE

<<<<<<< HEAD
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
=======
| Tâche | Détail |
|-------|--------|
| ✅ `GET /api/v1/cobrand/{token}` | `ApiCobrandController.php` |
| ✅ `ContactStat` | Comptage brut des demandes de contact |
| ✅ Table `contacts` | Email + téléphone sortis de `companies` — un contact référent par entreprise (`HasOne`) |
| ✅ Snapshot lieu sur `collections` | `address_id` FK remplacé par `contact_email`, `contact_phone`, `venue_street`, `venue_number`, `venue_postal_code`, `venue_city` |
| ✅ `ManageCollectionController` mis à jour | Validation, `store()`, `update()` adaptés au nouveau modèle |
| ✅ Seeder mis à jour | Section `CONTACTS` ajoutée, 23 collections avec snapshot |

> **Note :** `ApiCobrandController` retourne les colonnes snapshot directement (`venue.*`, `contact_email`, `contact_phone`) — plus de relation `address`.

---

### ✅ Phase 6 — Backend tracking — TERMINÉE

| Tâche | Fichier |
|-------|--------|
| ✅ `POST /api/v1/quiz/event` | `QuizEventController.php` |
| ✅ `POST /api/v1/page/event` | `PageEventController.php` |
| ✅ Calcul réel `participant_count` dans `ApiTropheeController` | `ApiTropheeController.php` |
>>>>>>> develop

---

### Phase 7 — Cobrand

**Prérequis :** Phase 5B ✅, Phase 6 ✅

#### ✅ Phase 7A — `cobrand/App.vue` — TERMINÉE

Routage hash, injection des couleurs cobrand en CSS vars, gestion de `initSession`.

---

#### ✅ Phase 7D — Quiz + Redirect — TERMINÉE

`quizQuestions.js` (8 questions P1 obligatoires + 10 questions P2 optionnelles avec slugs stables), `useQuizStore.js` (logique complète P1/P2 + tracking), `Quiz.vue`, `Redirect.vue` (tracking `quiz_completed` + `onedoc_clicked`).

---

#### Fenêtre de disponibilité du site cobrand

Les dates de collecte (`start_date` / `end_date`) sont les dates réelles de l'événement. La disponibilité du site cobrand suit une logique différente :

- **Début :** `created_at` de la collecte (dès que l'admin crée la collecte dans le dashboard)
- **Fin :** `end_date + 7 jours`

`created_at` est déjà présent sur `collections` via `timestamps()` — pas de nouvelle colonne.

À implémenter :
- Dans `ApiCobrandController` : retourner une 404 si on est hors fenêtre (évite d'exposer les données)
- Dans `cobrand/App.vue` : afficher un message approprié si hors fenêtre (avant ouverture ou après fermeture)

#### Données disponibles depuis `GET /api/v1/cobrand/{token}`

```json
{
  "company_name", "start_date", "end_date", "capacity",
  "primary_color", "secondary_color", "logo_url", "onedoc_url",
  "contact_email", "contact_phone",
  "venue": { "street", "number", "postal_code", "city" }
}
```

> `nb_inscrits`, `places_restantes` et `taux_remplissage` sont déjà calculés et retournés par `ApiCobrandController` — prêts à l'emploi dans `Accueil.vue`.

---

**7B — `cobrand/views/Accueil.vue`** ← PROCHAINE TÂCHE

- Données disponibles via `useCobrandSession` : `companyName`, `logoUrl`, `startDate`, `endDate`, `capacity`, `nbInscrits`, `placesRestantes`, `tauxRemplissage`, `venue`
- Affichage : nom entreprise, logo, lieu, dates, compteur inscrits / capacité
- CTA → Prevention

---

**7C — `cobrand/views/Prevention.vue`**

<<<<<<< HEAD
Au niveau année, `participant_count` retourne le nombre d'**entreprises uniques** — le nom est trompeur. À renommer en `companies_count` une fois que le calcul réel des participants (employees) sera en place à partir des `quiz_events`.
>>>>>>> develop
=======
Scrollytelling. Émet `prevention_entered` / `prevention_exited` (avec `engaged` + `time_on_page`) via le store — ne pas appeler l'API directement depuis la vue.

---

**Après 7B + 7C :**
- Aligner les slugs hardcodés dans `DashboardMetricsController::performanceParQuestion()` avec ceux définis dans `quizQuestions.js`
- Renommer `participant_count` → `companies_count` dans `ApiTropheeController` (représente des entreprises, pas des participants)

---

### ✅ Phase 8A + 8B — Nettoyage code mort — TERMINÉE

Suppression fichiers Blade inutilisés, modèles `ContactRequest` et `PmeContact`, migrations orphelines.

### Phase 8C — Renommage `ApiTropheeController` *(après Phase 7D)*

`participant_count` → `companies_count` au niveau année.
>>>>>>> develop

---

## Résumé des dépendances

```
<<<<<<< HEAD
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
=======
Phase 1 ✅
  ├── Phase 2 ✅      (fixes public)
  │     └── Phase 2B ✅  (labels actif/échu)
  ├── Phase 3 ✅      (trophées)
  ├── Phase 4 ✅      (dashboard UI)
  │     ├── Phase 4B ✅   (fix post-audit)
  │     ├── Phase 4C      (co-branding)          ← en attente maquettes
  │     ├── Phase 4D ✅   (adaptation new model)
  │     ├── Phase 4E ✅   (gestion entreprises)
  │     └── Phase 4F ✅   (gestion trophées)
  └── Phase 5 ✅      (backend dashboard)
        └── Phase 5B ✅   (backend cobrand + refactoring)
              └── Phase 6 ✅   (tracking)
                    └── Phase 7A ✅  (App.vue cobrand)
                          └── Phase 7D ✅  (quiz + redirect)
                                └── Phase 7B    (Accueil.vue)    ← PROCHAINE TÂCHE
                                      └── Phase 7C  (Prevention.vue)
                                            └── Phase 8C  (renommage)
Phase 8A+8B ✅  (cleanup)
```
>>>>>>> develop
