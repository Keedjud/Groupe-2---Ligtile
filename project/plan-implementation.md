# Plan d'implémentation — Fin de projet

> Mis à jour le 5 juin 2026 (dernière màj : fixes site public Phase 2). Ce document définit ce qui reste à faire pour finir le projet.

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
| 🟡 Normal | `CollecteForm.vue` + `CollecteDetail.vue` : aperçu co-branding en temps réel + warning contraste WCAG | `CollecteForm.vue`, `useColorContrast.js` (Phase 4C — en attente maquettes) |

---

## Ce qui reste à faire

### Frontend site public

- [x] Fix logo HUG : `href="/"` → `href="#/home"` — `SiteHeader.vue`
- [x] Fix nav : lien actif — `defineProps` manquant dans `PublicDefaultLayout.vue`
- [x] Fix footer : "Accessibilité" déplacé en dernier + faute de frappe corrigée — `SiteFooter.vue`
- [x] Labels `for`/`id` sur tous les champs — `Home.vue`, `Information.vue`
- [x] Focus trap + rôle ARIA sur la modale des critères — `Trophees.vue`

### Frontend dashboard

- [ ] **URGENT** Adapter `CollecteForm.vue` aux nouveaux champs backend — `venue_*`, `contact_email`, `contact_phone` (Phase 4D)
- [ ] **URGENT** Adapter `CollecteDetail.vue` à l'affichage des nouveaux champs (Phase 4D)
- [ ] Gestion des entreprises — nouvelle vue listant les entreprises et leurs contacts, avec édition (Phase 4E)
- [ ] Aperçu co-branding + warning contraste WCAG — `CollecteForm.vue`, `useColorContrast.js` (Phase 4C — en attente maquettes)
- [ ] Aperçu couleurs primaire + secondaire en lecture — `CollecteDetail.vue` (Phase 4C — en attente maquettes)

### Page labels publique

- [ ] Filtrer les entreprises par état label actif / échu — `Label.vue` + `ApiLabelCompanyController` (Phase 2B)

### Frontend cobrand

- [ ] `cobrand/App.vue` — routage hash, co-branding CSS, fenêtre de disponibilité
- [ ] `cobrand/views/Accueil.vue`
- [ ] `cobrand/views/Prevention.vue` — scrollytelling
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking
- [ ] `cobrand/composables/useQuizStore.js`
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2

---

## Phases d'implémentation

### ✅ Phase 1 — Fondations — TERMINÉE

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

### Phase 4D — Adaptation frontend dashboard au nouveau modèle de données ← URGENT

Suite du refactoring Phase 5B : le backend a changé mais le frontend n'a pas encore suivi.

**`CollecteForm.vue`** — renommer les champs envoyés à l'API :

| Ancien (cassé) | Nouveau |
|----------------|---------|
| `adresse.rue` | `venue_street` |
| `adresse.numero` | `venue_number` |
| `adresse.npa` | `venue_postal_code` |
| `adresse.ville` | `venue_city` |
| `entreprise.email` | `contact_email` |
| `entreprise.telephone` | `contact_phone` |

Aussi mettre à jour le pré-remplissage en mode édition (lecture depuis `collecte.venue_street` etc. au lieu de `collecte.adresse.rue`).

**`CollecteDetail.vue`** — mettre à jour l'affichage :

| Ancien (cassé) | Nouveau |
|----------------|---------|
| `collecte.adresse.rue` / `.numero` | `collecte.venue_street` / `.venue_number` |
| `collecte.adresse.npa` | `collecte.venue_postal_code` |
| `collecte.adresse.ville` | `collecte.venue_city` |
| `collecte.entreprise.email` | `collecte.contact_email` |
| `collecte.entreprise.telephone` | `collecte.contact_phone` |

---

### Phase 4E — Gestion des entreprises dans le dashboard

Nouvelle vue dédiée permettant de lister les entreprises et d'éditer leurs informations (nom, nb_employés, adresse du siège, contact référent).

**Backend à créer :**

| Route | Controller | Action |
|-------|-----------|--------|
| `GET /api/v1/companies` | `ManageCompanyController` | Liste paginée |
| `GET /api/v1/companies/{company}` | `ManageCompanyController` | Détail |
| `PUT /api/v1/companies/{company}` | `ManageCompanyController` | Mise à jour |

> Pas de `POST` ni `DELETE` — une entreprise est créée automatiquement lors de la création d'une collecte, et ne doit pas être supprimée indépendamment (cascade sur les collectes).

**Frontend à créer :**

- `dashboard/views/Companies.vue` — liste des entreprises avec recherche
- `dashboard/views/CompanyDetail.vue` — fiche entreprise (infos + contact + liste de ses collectes)
- Ajouter l'entrée dans `SidebarNav.vue`

---

### Phase 2B — Page labels publique : filtre actif / échu

**Contexte :** le pivot `company_label` a une colonne `end_date`. Une entreprise est considérée **labellisée** si au moins un de ses labels a `end_date >= aujourd'hui`, et **échue** si tous ses labels ont `end_date < aujourd'hui`.

**Backend — `ApiLabelCompanyController`** :

Ajouter un paramètre `?status=active|expired` (défaut : `active`) :
- `active` → `whereHas('labels', fn($q) => $q->where('end_date', '>=', now()))`
- `expired` → `whereHas('labels')` + `whereDoesntHave('labels', fn($q) => $q->where('end_date', '>=', now()))`

Supprimer le filtre par `year` (plus pertinent avec cette logique).

**Frontend — `Label.vue`** :

- Remplacer le filtre année par un toggle "Labellisées" / "Labels échus"
- Le toggle passe `?status=active` ou `?status=expired` à l'API

---

### ✅ Phase 5 — Backend dashboard + auth Sanctum — TERMINÉE

Endpoints :
- `POST /api/v1/session/connect` — login
- `POST /api/v1/session/disconnect` — logout
- `GET /api/v1/session/current-user`
- `GET/POST/PUT/DELETE /api/v1/manage-collections`
- `POST /api/v1/manage-collections/{id}/kit/send`
- `GET /api/v1/analytics-stats`

---

### ✅ Phase 5B — Backend cobrand + refactoring modèle de données — TERMINÉE

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

---

### Phase 7 — Cobrand

**Prérequis :** Phase 5B ✅, Phase 6 ✅

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

> À compléter dans `ApiCobrandController` : ajouter le compte d'inscrits (`onedoc_clicked`) pour l'affichage sur `Accueil.vue`.

---

**7A — `cobrand/App.vue`**

Routage hash, injection des couleurs cobrand en CSS vars, gestion de la fenêtre de disponibilité (`created_at` → `end_date + 7j`).

---

**7B — `cobrand/views/Accueil.vue`**

- Fetch collection via `useQuizStore`
- Affichage : nom entreprise, logo, lieu, dates, compteur inscrits / capacité
- CTA → Prevention

---

**7C — `cobrand/views/Prevention.vue`**

Scrollytelling. Émet `prevention_entered` / `prevention_exited` (avec `engaged` + `time_on_page`) via le store — ne pas appeler l'API directement depuis la vue.

---

**7D — Quiz + Redirect**

Ordre de développement conseillé :
1. `cobrand/constants/quizQuestions.js` — définir P1 + P2 avec slugs stables (`age`, `poids`, `sante-generale`, `medicaments`, `voyages` pour P1)
2. `useQuizStore.js` — fetch API, navigation entre vues, UUID `session_id`, helpers `sendQuizEvent()` / `sendPageEvent()`
3. `Redirect.vue` — message intermédiaire, track `onedoc_clicked` au clic, ouvrir `onedoc_url`
4. `Quiz.vue` — P1 éliminatoire + P2 informative/skippable

**Règle critique slugs :** ne jamais modifier un slug en prod sans migrer les données historiques (`UPDATE quiz_events SET question_slug = 'nouveau' WHERE question_slug = 'ancien'`).

**Après 7D :**
- Aligner les slugs hardcodés dans `DashboardMetricsController::performanceParQuestion()` avec ceux définis dans `quizQuestions.js`
- Renommer `participant_count` → `companies_count` dans `ApiTropheeController` (représente des entreprises, pas des participants)

---

### ✅ Phase 8A + 8B — Nettoyage code mort — TERMINÉE

Suppression fichiers Blade inutilisés, modèles `ContactRequest` et `PmeContact`, migrations orphelines.

### Phase 8C — Renommage `ApiTropheeController` *(après Phase 7D)*

`participant_count` → `companies_count` au niveau année.

---

## Résumé des dépendances

```
Phase 1 ✅
  ├── Phase 2         (fixes public)             ← en cours
  │     └── Phase 2B  (labels actif/échu)        ← en cours
  ├── Phase 3 ✅      (trophées)
  ├── Phase 4 ✅      (dashboard UI)
  │     ├── Phase 4B ✅   (fix post-audit)
  │     ├── Phase 4C      (co-branding)          ← en attente maquettes
  │     ├── Phase 4D      (adaptation new model) ← URGENT
  │     └── Phase 4E      (gestion entreprises)
  └── Phase 5 ✅      (backend dashboard)
        └── Phase 5B ✅   (backend cobrand + refactoring)
              └── Phase 6 ✅   (tracking)
                    └── Phase 7A→D   (cobrand)            ← en cours
                          └── Phase 8C  (renommage)       ← après 7D
Phase 8A+8B ✅  (cleanup)
```
