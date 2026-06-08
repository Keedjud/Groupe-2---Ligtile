# Plan d'implémentation — Fin de projet

> Mis à jour le 8 juin 2026 (Phase 7C ✅, Phase 4C ✅ — bugs tests collègue ajoutés). Ce document définit ce qui reste à faire pour finir le projet.

---

## L'équipe

| Développeur | Rôle |
|-------------|------|
| **Loïc** | Frontend cobrand |
| **Elia** | Frontend cobrand |
| **Inoé** | Backend, coordination, fixes dashboard |

---

## Bugs ouverts

### Site public — Home

| Priorité | Bug |
|----------|-----|
| 🔴 Critique | `POST /api/v1/contact` retourne 500 — impossible d'envoyer le formulaire de contact grande entreprise |
| 🟡 Normal | Lien du formulaire "inscription collecte" (segment employés < 1000) ne renvoie pas vers la bonne page |
| 🟡 Normal | Bouton "Comment se déroule une collecte ?" ne renvoie pas vers la bonne section de la page |
| 🟡 Normal | Section "Pourquoi accueillir une collecte" : icônes médicaments → remplacer par les bonnes icônes |
| 🟡 Normal | Traits de connexion entre les icônes manquants (câble / ligne verticale entre les étapes) |
| 🟢 Faible | Aligner horizontalement les 3 boutons "En savoir plus" de la section d'intro |

### Site public — Trophées

| Priorité | Bug |
|----------|-----|
| 🟡 Normal | Modèle 3D du trophée pas à jour — dernière version non intégrée dans `public/images/3D/` |
| 🟢 Faible | Trophée affiché trop petit — agrandir légèrement |

### Site public — Informations

| Priorité | Bug |
|----------|-----|
| 🟡 Normal | Image "goutte" dans la page Informations → remplacer par la poche de sang |

### Site cobrandé — Header

| Priorité | Bug |
|----------|-----|
| 🔴 Critique | Couleur de fond du header ne reprend pas la couleur primaire de l'entreprise |
| 🔴 Critique | Logo de l'entreprise partenaire absent |
| 🟡 Normal | Hover des liens header et footer en couleurs du site de base — doit reprendre `var(--cobrand-primary)` |

### Site cobrandé — `Accueil.vue`

| Priorité | Bug |
|----------|-----|
| 🔴 Critique | CTA "Faire le test d'éligibilité" pointe vers `#/inscription` au lieu de `#/quiz` |
| 🟡 Normal | "à Genève" hardcodé au lieu de `{{ venue?.city }}` |
| 🟡 Normal | Asset section "Pourquoi donner son sang ?" trop petit — agrandir selon maquette |
| 🟡 Normal | Lien baromètre HUG non fonctionnel (bouton sans href) |
| 🟡 Normal | Bordures de développement visibles (`border border-[var(--color-violet-100)]`) |

### Site cobrandé — `Quiz.vue`

| Priorité | Bug |
|----------|-----|
| 🟡 Normal | Phrase "Vous êtes toujours éligible" apparaît en permanence, même sans avoir passé le quiz |
| 🟡 Normal | Phrase "Bonne nouvelle ! Votre situation vous permet de vous inscrire." affichée même après clic "Passer" |
| 🟡 Normal | Question passée (skip P2) reste cliquable et non grisée — l'utilisateur peut encore y répondre |

### Site cobrandé — `Redirect.vue`

| Priorité | Bug |
|----------|-----|
| 🔴 Critique | Bouton "S'inscrire" rouge — couleur primaire cobrand non reprise |

### Site cobrandé — `Prevention.vue`

| Priorité | Bug |
|----------|-----|
| 🟡 Normal | Couleur des icônes "Pourquoi ne suis-je pas éligible ?" différente de celle de "J'ai peur de la piqûre" — harmoniser |
| 🟡 Normal | Section 1 — retour à la ligne après "vies" pour améliorer la lisibilité |

### Dashboard — `CollecteForm.vue`

| Priorité | Bug |
|----------|-----|
| 🟡 Normal | Color pickers ne se ferment pas en cliquant en dehors |
| 🟡 Normal | Logo non affiché dans `LogoUpload` après upload — pas de prévisualisation |
| 🟡 Normal | Faute de frappe : "diffère" → "diffèrent" dans la note de pré-remplissage |
| 🟡 Normal | Validation date : possible de créer une collecte avec une date de début dans le passé |

---

## Note — Mise à jour automatique des pages publiques

Les pages publiques (Trophées, Labels) ne font **pas de cache** : chaque chargement interroge directement la base de données via l'API. Toute modification effectuée depuis le dashboard (nouveau trophée, nouvelle collecte, nouveau label) est donc immédiatement visible sur le site public au prochain chargement de la page. Pas de travail supplémentaire nécessaire pour ce comportement.

---

## Ce qui reste à implémenter

> Fonctionnalités jamais codées, refactoring planifié et dette technique — distinct des bugs (voir section précédente).

### Backend — `ApiCobrandController`

- [ ] Fenêtre de disponibilité : retourner 404 si avant `created_at` ou après `end_date + 7 jours`

### Backend — `ApiTropheeController` *(Phase 8C)*

- [ ] Renommer `participant_count` → `companies_count` au niveau année

### Backend — Migrations & Cleanup

- [ ] Nouvelle migration : index composite `(collection_id, event_type, session_id)` sur `quiz_events`
- [ ] `app/Models/Address.php` — supprimer la relation morte `collections()`

### Site cobrandé — `App.vue`

- [ ] Afficher un écran "Collecte indisponible" si `sessionError` (token invalide ou hors fenêtre)

### Site cobrandé — `Prevention.vue`

- [ ] Ajouter un lien vers travelcheck.ch sur le mot "voyage" dans la carte correspondante

### Dashboard — Gestion des collectes

- [ ] Ajouter une confirmation (modale ou toast) avant suppression définitive d'une collecte

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

### ✅ Phase 4C — Aperçu co-branding dashboard — TERMINÉE

**Branche :** `feature/phase-4c-cobrand-preview` (en attente de merge dans `develop`)

| Tâche | Fichier(s) |
|-------|-----------|
| ✅ Bouton "Prévisualiser le site cobrandé" dans `CollecteForm.vue` — visible dès que le logo est uploadé, couleur adaptive selon contraste WCAG | `CollecteForm.vue` |
| ✅ Modale grande `CobrandPreviewModal.vue` — faux header cobrand + hero "État de la collecte" + section "Pourquoi donner" avec couleurs et logo injectés en temps réel | `dashboard/components/CobrandPreviewModal.vue` |

> **Note :** l'aperçu calcul côté JS la couleur de texte accessible (blanc ou noir) via luminance WCAG — pas de dépendance à `useColorContrast.js`. L'aperçu des couleurs en lecture dans `CollecteDetail.vue` reste hors scope.

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

#### ✅ Phase 7A — `cobrand/App.vue` — TERMINÉE

Routage hash, injection des couleurs cobrand en CSS vars, gestion de `initSession`.

---

#### ✅ Phase 7B — `cobrand/views/Accueil.vue` — TERMINÉE

**Branche :** `feature/front-homepage-cobrand`

Implémentation complète de la page d'accueil cobrand :
- État de la collecte : nom entreprise, nb inscrits, places restantes, taux de remplissage avec image goutte adaptative
- Section "Pourquoi donner son sang ?" (5 raisons)
- Baromètre des réserves par groupe sanguin
- Section éligibilité avec CTA vers le quiz

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

#### ✅ Phase 7C — `cobrand/views/Prevention.vue` — TERMINÉE

Scrollytelling complet : 6 sections narratives (impact, peur, pendant, devient, éligibilité, après), câble SVG animé desktop, carousel de topics avec navigation clavier et indicateur de page, tracking `prevention_entered` / `prevention_exited` (avec `engaged` + `time_on_page`) via `trackPage` / `trackPageBeacon`.

---

**Après 7B + 7C :**
- ✅ Alignement slugs `DashboardMetricsController::performanceParQuestion()` ↔ `quizQuestions.js` — vérifié, tous les 18 slugs correspondent
- [ ] Renommer `participant_count` → `companies_count` dans `ApiTropheeController` (représente des entreprises, pas des participants) — Phase 8C

---

### ✅ Phase 8A + 8B — Nettoyage code mort — TERMINÉE

Suppression fichiers Blade inutilisés, modèles `ContactRequest` et `PmeContact`, migrations orphelines.

### Phase 8C — Renommage `ApiTropheeController` *(après Phase 7D)*

`participant_count` → `companies_count` au niveau année.

---

## Résumé des dépendances

```
Phase 1 ✅
  ├── Phase 2 ✅      (fixes public)
  │     └── Phase 2B ✅  (labels actif/échu)
  ├── Phase 3 ✅      (trophées)
  ├── Phase 4 ✅      (dashboard UI)
  │     ├── Phase 4B ✅   (fix post-audit)
  │     ├── Phase 4C ✅   (co-branding preview)   ← branche feature/phase-4c-cobrand-preview
  │     ├── Phase 4D ✅   (adaptation new model)
  │     ├── Phase 4E ✅   (gestion entreprises)
  │     └── Phase 4F ✅   (gestion trophées)
  └── Phase 5 ✅      (backend dashboard)
        └── Phase 5B ✅   (backend cobrand + refactoring)
              └── Phase 6 ✅   (tracking)
                    └── Phase 7A ✅  (App.vue cobrand)
                          └── Phase 7D ✅  (quiz + redirect)
                                └── Phase 7B ✅  (Accueil.vue)
                                      └── Phase 7C ✅  (Prevention.vue)
                                            └── Phase 8C  (renommage participant_count)  ← à faire
Phase 8A+8B ✅  (cleanup)
```
