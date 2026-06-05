# Plan d'implémentation — Fin de projet

> Mis à jour le 5 juin 2026. Ce document définit qui fait quoi, dans quel ordre, pour finir le projet sans se marcher dessus.

---

## L'équipe

| Développeur | Rôle dans la suite |
|-------------|-------------------|
| **Loïc** | Frontend cobrand — Prevention.vue (Phase 7C) en parallèle de Inoé |
| **Elia** | Fix et finitions du site public uniquement — ne touche pas au dashboard ni au cobrand |
| **Inoé** | Fix dashboard (Phase 4C), cobrand complet (Phase 7), coordination backend |

**Règle d'or :** Avant d'ouvrir une PR, faire `git merge develop` sur sa branche et résoudre ses propres conflits. Un reviewer ne résout jamais les conflits d'une autre personne.

---

## Bugs ouverts

| Priorité | Bug | Responsable |
|----------|-----|-------------|
| 🟡 Normal | `CollecteForm.vue` + `CollecteDetail.vue` : aperçu co-branding en temps réel + warning contraste WCAG | Inoé (Phase 4C — en attente maquettes) |
| 🟡 Normal | Navigation : lien actif non mis en évidence dans le header | Elia |
| 🟡 Normal | Footer : lien "Accessibilité" mal positionné | Elia |

---

## Ce qui reste à faire

### Frontend site public **(Elia)**

- [ ] Fix nav : lien actif non mis en évidence — `SiteHeader.vue`, `useNavigation.js`
- [ ] Fix footer : lien "Accessibilité" à déplacer à droite — `SiteFooter.vue`
- [ ] Labels sur tous les champs de formulaire — `Home.vue`, `Information.vue`
- [ ] Focus trap sur la modale des critères — `Trophees.vue`

### Frontend dashboard **(Inoé)**

- [ ] Aperçu co-branding + warning contraste WCAG — `CollecteForm.vue`, `useColorContrast.js` **(Phase 4C — en attente maquettes)**
- [ ] Aperçu couleurs primaire + secondaire — `CollecteDetail.vue` **(Phase 4C — en attente maquettes)**

### Frontend cobrand

- [ ] `cobrand/App.vue` — routage hash, co-branding CSS, fenêtre de disponibilité **(Inoé)**
- [ ] `cobrand/views/Accueil.vue` **(Inoé)**
- [ ] `cobrand/views/Prevention.vue` — scrollytelling **(Loïc)**
- [ ] `cobrand/views/Quiz.vue` — P1 + P2 + tracking **(Inoé)**
- [ ] `cobrand/views/Redirect.vue` — page Onedoc + tracking **(Inoé)**
- [ ] `cobrand/composables/useQuizStore.js` **(Inoé)**
- [ ] `cobrand/constants/quizQuestions.js` — slugs stables P1 + P2 **(Inoé, à aligner dans `DashboardMetricsController` après Phase 7D)**

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
| Labels sur champs de formulaire | `Home.vue`, `Information.vue` |
| Focus trap modale | `Trophees.vue` |

---

### ✅ Phase 3 — Fix urgent Trophées (Inoé) — TERMINÉE

`ApiTropheeController` corrigé. `participant_count` reste provisoirement à `0` en attendant Phase 6.

---

### ✅ Phase 4 — Dashboard UI (Loïc) — TERMINÉE

---

### ✅ Phase 4B — Fix post-audit dashboard (Inoé) — TERMINÉE

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

### Phase 4C — Aperçu co-branding dashboard **(Inoé) ← EN ATTENTE DES MAQUETTES**

| Tâche | Fichier(s) |
|-------|-----------|
| Aperçu co-branding en temps réel + warning contraste WCAG | `CollecteForm.vue`, nouveau `composables/useColorContrast.js` |
| Aperçu couleurs primaire + secondaire en lecture | `CollecteDetail.vue` |

---

### ✅ Phase 5 — Backend dashboard + auth Sanctum (Loïc) — TERMINÉE

Endpoints :
- `POST /api/v1/session/connect` — login
- `POST /api/v1/session/disconnect` — logout
- `GET /api/v1/session/current-user`
- `GET/POST/PUT/DELETE /api/v1/manage-collections`
- `POST /api/v1/manage-collections/{id}/kit/send`
- `GET /api/v1/analytics-stats`

---

### ✅ Phase 5B — Backend cobrand + refactoring modèle de données (Inoé) — TERMINÉE

**Branches :** `feature/backend-tracking` + `fix/doc-home-page-public`

| Tâche | Détail |
|-------|--------|
| ✅ `GET /api/v1/cobrand/{token}` | `ApiCobrandController.php` |
| ✅ `ContactStat` | Comptage brut des demandes de contact |
| ✅ Table `contacts` | Email + téléphone sortis de `companies` — un contact référent par entreprise (`HasOne`) |
| ✅ Snapshot lieu sur `collections` | `address_id` FK remplacé par `contact_email`, `contact_phone`, `venue_street`, `venue_number`, `venue_postal_code`, `venue_city` |
| ✅ `ManageCollectionController` mis à jour | Validation, `store()`, `update()` adaptés au nouveau modèle |
| ✅ Seeder mis à jour | Section `CONTACTS` ajoutée, 23 collections avec snapshot |

> **Note :** `ApiCobrandController` retourne désormais les colonnes snapshot directement (`venue.*`, `contact_email`, `contact_phone`) — plus de relation `address`.

---

### ✅ Phase 6 — Backend tracking (Inoé) — TERMINÉE

| Tâche | Fichier |
|-------|--------|
| ✅ `POST /api/v1/quiz/event` | `QuizEventController.php` |
| ✅ `POST /api/v1/page/event` | `PageEventController.php` |
| ✅ Calcul réel `participant_count` dans `ApiTropheeController` | `ApiTropheeController.php` |

---

### Phase 7 — Cobrand **(Loïc + Inoé)**

**Prérequis :** Phase 5B ✅, Phase 6 ✅

**Données disponibles depuis `GET /api/v1/cobrand/{token}` :**
```json
{
  "company_name", "start_date", "end_date", "capacity",
  "primary_color", "secondary_color", "logo_url", "onedoc_url",
  "contact_email", "contact_phone",
  "venue": { "street", "number", "postal_code", "city" }
}
```
> À compléter : ajouter le compte d'inscrits (`onedoc_clicked`) dans la réponse pour l'affichage sur `Accueil.vue`.

---

**7A — `cobrand/App.vue`** **(Inoé)**

Routage hash, injection des couleurs cobrand en CSS vars, fenêtre de disponibilité (masquer le site cobrand avant `start_date` et 3 jours après `end_date`).

---

**7B — `cobrand/views/Accueil.vue`** **(Inoé)**

- Fetch collection via `useQuizStore`
- Affichage : nom entreprise, logo, lieu, dates, compteur inscrits / capacité
- CTA → Prevention

---

**7C — `cobrand/views/Prevention.vue`** **(Loïc)**

Scrollytelling. Émet `prevention_entered` / `prevention_exited` (avec `engaged` + `time_on_page`) via le store — ne pas appeler l'API directement depuis la vue.

---

**7D — Quiz + Redirect** **(Inoé)**

Ordre de développement conseillé :
1. `cobrand/constants/quizQuestions.js` — définir P1 + P2 avec slugs stables (`age`, `poids`, `sante-generale`, `medicaments`, `voyages` pour P1)
2. `useQuizStore.js` — fetch API, navigation entre vues, UUID `session_id`, helpers `sendQuizEvent()` / `sendPageEvent()`
3. `Redirect.vue` — message intermédiaire, track `onedoc_clicked` au clic, ouvrir `onedoc_url`
4. `Quiz.vue` — P1 éliminatoire + P2 informative/skippable

**Règle critique slugs :** ne jamais modifier un slug en prod sans migrer les données historiques (`UPDATE quiz_events SET question_slug = 'nouveau' WHERE question_slug = 'ancien'`).

**Après 7D :**
- Aligner les slugs hardcodés dans `DashboardMetricsController::performanceParQuestion()` avec ceux définis dans `quizQuestions.js`
- Renommer `participant_count` → `companies_count` dans `ApiTropheeController` (le champ représente des entreprises, pas des participants)

---

### ✅ Phase 8A + 8B — Nettoyage code mort — TERMINÉE

Suppression fichiers Blade inutilisés, modèles `ContactRequest` et `PmeContact`, migrations orphelines.

### Phase 8C — Renommage `ApiTropheeController` *(après Phase 7D)*

`participant_count` → `companies_count` au niveau année (représente des entreprises distinctes, pas des participants individuels).

---

## Résumé des dépendances

```
Phase 1 ✅
  ├── Phase 2        (public, Elia)          ← en cours
  ├── Phase 3 ✅     (trophées, Inoé)
  ├── Phase 4 ✅     (dashboard UI, Loïc)
  │     ├── Phase 4B ✅  (fix post-audit, Inoé)
  │     └── Phase 4C    (co-branding, Inoé)  ← en attente maquettes
  └── Phase 5 ✅     (backend dashboard, Loïc)
        └── Phase 5B ✅  (backend cobrand + refactoring, Inoé)
              └── Phase 6 ✅  (tracking, Inoé)
                    └── Phase 7A→D  (cobrand, Loïc + Inoé)  ← en cours
                          └── Phase 8C  (renommage, Inoé)   ← après 7D
Phase 8A+8B ✅  (cleanup)
```

---

## Fichiers partagés — zones à risque de conflit

| Fichier | Responsable | Règle |
|---------|-------------|-------|
| `routes/api/cobrand.php` | Inoé | Inoé uniquement |
| `routes/api/dashboard.php` | Inoé | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ManageCollectionController.php` | Inoé | Inoé uniquement |
| `app/Http/Controllers/Api/v1/DashboardMetricsController.php` | Inoé | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ApiTropheeController.php` | Inoé | Inoé uniquement |
| `app/Http/Controllers/Api/v1/ApiCobrandController.php` | Inoé | Inoé uniquement |
| `resources/js/cobrand/` (toutes les vues) | Loïc + Inoé | Branches séparées par vue |
