# Concept du projet

## Vue d'ensemble

Le projet est une plateforme multi-sites destinée à faciliter l'organisation de collectes de sang en entreprise, gérée par le CTS (Centre de Transfusion Sanguine).

---

## Les trois espaces du projet

### 1. Site public principal (`hug-collecte.ch`)

Site vitrine accessible à tous. Il comprend :

- **Splash screen** — affiché à l'arrivée sur le site, il indique clairement qu'il s'agit d'un **projet étudiant** et propose un lien de redirection vers le vrai site des HUG. L'utilisateur peut fermer le splash pour accéder au site.
- **Formulaire de contact** — permet à une entreprise de contacter le CTS pour organiser une collecte.
- **Page Trophées** — met en avant les trophées des années précédentes.
- **Page Label** — présente le label CTS et les entreprises labellisées.
- **Page Information / Don du sang en entreprise** — informe sur les aspects pratiques de l'accueil d'une collecte.

---

### 2. Dashboard CTS (`hug-collecte.ch/dashboard`)

Interface de gestion réservée au CTS, protégée par login. Elle comprend deux écrans distincts accessibles via la navigation interne.

---

#### 2a. Gestion des collectes (`#collectes`)

Page principale du dashboard. Le CTS y visualise toutes les collectes (en cours, à venir, passées).

**Flux d'utilisation :**
1. Après la prise de contact via le site public, les deux parties s'accordent par téléphone sur les dates, le lieu et les informations nécessaires.
2. L'entreprise fournit au CTS ses **couleurs principales** et son **logo** (pour le co-branding).
3. Le CTS se connecte au dashboard et saisit toutes les informations de la collecte via le formulaire de création.
4. Le dashboard **génère automatiquement le site cobrandé** et son lien (`hug-collecte.ch/{id_collecte}`).
5. Le lien est transmis à l'entreprise partenaire dans le kit de communication.

**Actions disponibles depuis cette page :**
- **Créer une nouvelle collecte** — navigue vers le formulaire (`#nouvelle-collecte`).
- **Accéder au détail d'une collecte** — navigue vers la page de détail (`#collecte-{id}`) en cliquant directement sur la collecte souhaitée.

---

#### 2b. Formulaire de collecte (`#nouvelle-collecte` / `#editer-{id}`)

Page à part entière pour la création et la modification d'une collecte. Le même composant est réutilisé pour les deux cas — vide pour une création, pré-rempli pour une modification.

**Contenu du formulaire :**
- Informations de l'entreprise partenaire (nom, email de contact)
- Date de début et date de fin de la collecte, lieu, horaires
- Lien Onedoc pour l'inscription des employés
- Capacité de la collecte (nombre de créneaux disponibles) — utilisée pour le taux de remplissage
- Couleurs de co-branding (color picker) et upload du logo

**Responsabilité des dates :**
La saisie correcte des dates est entièrement sous la responsabilité du CTS. Aucune contrainte d'intégrité n'est imposée côté base de données sur les dates (cohérence, chevauchement, etc.) — le CTS dispose déjà de ses propres processus internes pour valider ces informations lors de la prise de décision.

**Disponibilité du site cobrandé :**
- **Date de début de disponibilité** — automatique : correspond à la date d'ajout de la collecte en base de données.
- **Date de fin de disponibilité** — automatique : 3 jours après la date de fin de collecte saisie.

---

#### 2c. Détail d'une collecte (`#collecte-{id}`)

Page de détail accessible en cliquant sur une collecte depuis la liste. Permet de consulter toutes les informations et de modifier la collecte.

**Contenu :**
- Informations complètes de la collecte
- Lien du site cobrandé (avec bouton copier)
- Compteur d'inscrits (calculé depuis les clics Onedoc trackés)
- Aperçu du co-branding (couleurs + logo)
- Bouton **Modifier** — navigue vers le formulaire pré-rempli (`#editer-{id}`)

---

#### 2d. Dashboard des métriques (`#metriques`)

Écran de surveillance des KPIs, accessible depuis la navigation du dashboard. Permet au CTS de suivre ses performances globales.

**KPIs suivis :**

**Vue d'ensemble opérationnelle**

| KPI | Description |
|-----|-------------|
| Nombre total de collectes organisées | Volume brut sur une période sélectionnable |
| Nombre total d'inscrits cumulé | Total de clics Onedoc toutes collectes confondues |
| Nombre d'entreprises distinctes touchées | Combien d'entreprises différentes ont participé sur la période |
| Évolution temporelle | Dimension transversale : les KPIs principaux visualisables par mois / trimestre / année |

**Engagement entreprises**

| KPI | Description |
|-----|-------------|
| Taux de remplissage moyen des collectes | `nb inscrits / capacity` par collecte, moyenné sur la période |
| Collectes récurrentes | Nombre d'entreprises ayant organisé ≥ 2 collectes — mesure la fidélisation |
| Top entreprises contributrices | Classement des entreprises par nombre de collectes organisées |
| Nombre de demandes de collecte | Formulaires de contact envoyés depuis le site public |

**Performance du quiz — vue globale**

| KPI | Description |
|-----|-------------|
| Taux de complétion du quiz | % ayant répondu à toutes les questions (P1 + P2) parmi ceux ayant commencé |
| Taux d'élimination P1 global | % éliminés en P1 parmi ceux ayant commencé le quiz |
| Taux de clic sur "Prendre rendez-vous" | % des personnes éligibles (ayant passé P1) ayant cliqué sur le lien Onedoc |

**Performance du quiz — granularité par question**

| KPI | Description |
|-----|-------------|
| Principale cause de non-éligibilité | Question (P1 ou P2) à l'origine du plus grand nombre de non-éligibilités |
| Taux de bonnes/mauvaises réponses par question | Pour chaque question : ratio correct / incorrect |
| Taux de skip par question (P2) | Pour chaque question P2 : % passée sans répondre (skip individuel) |
| Question d'abandon P2 | À partir de quelle question les employés abandonnent la P2 via le bouton "passer toute la partie" |

**Engagement page prévention**

| KPI | Description |
|-----|-------------|
| Temps passé sur la page scrollytelling | Durée moyenne — les employés lisent-ils vraiment le contenu ? |
| Taux de rebond sur la page cobrandée | % quittant la page prévention sans aucune interaction |

**Recommandation (non implémentable)**

| KPI | Description | Raison |
|-----|-------------|--------|
| ~~Taux de dons réels parmi les inscrits~~ | % ayant effectivement donné leur sang le jour J | Nécessite une intégration Onedoc réelle post-collecte |

---

### 3. Sites cobrandés (`hug-collecte.ch/{id_collecte}`)

Sites générés automatiquement pour chaque collecte, aux couleurs de l'entreprise partenaire.

**Parcours employé :**

1. **Page d'accueil** — informations de la collecte en cours, avec un compteur du nombre d'inscrits (calculé depuis les clics Onedoc trackés).
2. **Page Prévention (Scrollytelling)** — contenu de prévention interactif sous forme de scrollytelling.
3. **Quizz d'éligibilité** — divisé en deux parties :

   - **Partie 1 — Questions éliminatoires** (intemporelles, peu nombreuses) :
     - But : filtrer les personnes non-éligibles avant qu'elles ne bloquent des créneaux.
     - Une mauvaise réponse redirige l'employé vers la section de prévention correspondante sur le site cobrandé.
   
   - **Partie 2 — Questions informatives** (non-éliminatoires, skippables) :
     - But : rappeler des informations importantes ("Ah oui, c'est juste, il y a ça").
     - Les questions concernant les prescriptions médicamenteuses et les départs en voyage sont un peu à part.
     - Une "mauvaise" réponse affiche une **pop-up** expliquant pourquoi cela peut être problématique, avec un lien vers la section de prévention correspondante.
     - L'employé dispose de deux options pour ne pas répondre : **passer la question en cours** (affiche la suivante) ou **passer toute la partie 2** (accède directement à la page Onedoc).

4. **Lien Onedoc** — à l'issue du quiz (ou après le skip de P2), l'employé accède à la plateforme Onedoc pour choisir son créneau. **Un clic sur ce lien est comptabilisé comme une inscription.**

---

## Collecte de données et calcul des KPIs

### Comptage des inscrits

Il n'y a pas d'intégration avec l'API Onedoc. Un clic sur le lien Onedoc depuis la page de redirection du site cobrandé est comptabilisé comme une inscription. Ce comptage est assuré par le système de tracking décrit ci-dessous.

---

### Choix d'une solution de tracking custom

Le tracking est implémenté directement dans la base de données Laravel plutôt que via un outil tiers (Google Analytics, Matomo, etc.). Ce choix repose sur trois raisons :

1. **KPIs métier spécifiques** — les métriques du projet (taux d'élimination P1, question d'abandon P2, skip individuel vs global) ne correspondent à aucun concept standard d'un outil analytics générique. Un outil tiers aurait nécessité des custom events identiques, mais les données auraient atterri dans un système externe, rendant leur exploitation dans le dashboard CTS plus complexe (appels API, auth supplémentaire, conversion de format).

2. **Données dans la même base** — puisque le dashboard CTS est construit sur Laravel avec accès direct à la base, les KPIs se calculent en une requête Eloquent. Aucune dépendance externe, aucune latence supplémentaire.

3. **Données sur notre serveur** — Google Analytics transfère les données vers des serveurs américains, ce qui pose des questions légales pour un projet lié à une institution médicale suisse (même fictive). Matomo auto-hébergé résoudrait ce point mais ajouterait une infrastructure supplémentaire (serveur dédié, base séparée) disproportionnée pour la taille du projet.

---

### Identifiant de session anonyme

Pour relier les événements d'un même parcours sans identifier les employés, un UUID est généré côté client au premier chargement du site cobrandé et stocké en `sessionStorage`. Cet identifiant est :
- **Anonyme** — aucun lien avec une identité
- **Éphémère** — réinitialisé à chaque fermeture d'onglet ou de navigateur
- **Transmis** avec chaque événement envoyé à l'API

---

### Tables de tracking

#### Table `quiz_events`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT PK | — |
| `collection_id` | FK → `collections` | Collecte concernée |
| `session_id` | UUID | Identifiant anonyme de visite |
| `event_type` | ENUM | Type d'événement (voir ci-dessous) |
| `part` | TINYINT nullable | `1` ou `2` — renseigné uniquement pour les événements liés à une question |
| `question_slug` | VARCHAR nullable | Identifiant stable de la question — renseigné uniquement pour les événements liés à une question |
| `answer_result` | ENUM nullable | `correct` ou `incorrect` — renseigné uniquement pour `question_answered` |
| `created_at` | TIMESTAMP | — |

**Événements et conditions de déclenchement :**

| `event_type` | Déclencheur | `part` | `question_slug` | `answer_result` |
|---|---|---|---|---|
| `quiz_started` | Affichage de la première question P1 | — | — | — |
| `question_answered` | Réponse à une question (P1 ou P2) | obligatoire | obligatoire | obligatoire |
| `question_skipped` | Clic "passer cette question" (P2 uniquement) | `2` | obligatoire | — |
| `form_skipped_from` | Clic "passer toute la partie 2" | `2` | obligatoire | — |
| `p1_eliminated` | Réponse incorrecte en P1 — marqueur d'état de session | — | — | — |
| `p1_completed` | Toutes les questions P1 passées sans élimination | — | — | — |
| `p2_completed` | Toutes les questions P2 répondues sans skip global | — | — | — |
| `quiz_completed` | Arrivée sur la page de redirection Onedoc | — | — | — |
| `onedoc_clicked` | Clic effectif sur le lien Onedoc | — | — | — |

> **Note :** pour une mauvaise réponse en P1, deux événements sont émis dans cet ordre : `question_answered` (part=1, answer_result='incorrect') puis `p1_eliminated`. Le premier alimente les stats par question, le second marque l'état de la session.

**Règle critique — stabilité des slugs :**
Les slugs de questions sont définis une seule fois dans `cobrand/constants/quizQuestions.js`. Ils ne doivent jamais être modifiés en production sans mettre à jour les données historiques :
```sql
UPDATE quiz_events SET question_slug = 'nouveau_slug' WHERE question_slug = 'ancien_slug';
```
Un changement de slug sans migration produit deux entrées distinctes pour la même question dans tous les agrégats.

---

#### Table `page_events`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT PK | — |
| `collection_id` | FK → `collections` | Collecte concernée |
| `session_id` | UUID | Identifiant anonyme de visite |
| `event_type` | ENUM | `prevention_entered` ou `prevention_exited` |
| `engaged` | BOOLEAN nullable | `true` si l'utilisateur a scrollé ou interagi — renseigné à `prevention_exited` |
| `time_on_page` | INT nullable | Durée en secondes — renseigné à `prevention_exited` |
| `created_at` | TIMESTAMP | — |

`prevention_entered` est émis au montage du composant Prevention. `prevention_exited` est émis au `beforeUnmount` avec la durée calculée depuis le montage et le flag `engaged` (positionné à `true` par un listener de scroll ou de clic).

---

#### Table `contact_requests`

| Colonne | Type | Description |
|---------|------|-------------|
| `id` | BIGINT PK | — |
| `company_name` | VARCHAR | Nom de l'entreprise |
| `contact_name` | VARCHAR | Nom du contact |
| `email` | VARCHAR | Email |
| `message` | TEXT | Message |
| `created_at` | TIMESTAMP | — |

---

#### Modification de la table `collections`

- **Colonne ajoutée :** `capacity` (INT nullable) — nombre de créneaux disponibles, saisi par le CTS dans le formulaire. Les collectes sans capacité renseignée sont exclues du calcul du taux de remplissage.
- **Colonne supprimée :** `nb_registered` — le nombre d'inscrits est désormais calculé dynamiquement depuis `quiz_events` (`COUNT DISTINCT session_id WHERE event_type = 'onedoc_clicked'`).

---

### Calcul des KPIs

Les calculs ci-dessous sont exprimés en Eloquent Laravel. Ils supposent que les modèles `QuizEvent`, `PageEvent`, `ContactRequest` et `Collection` ont leurs relations définies.

#### Compatibilité SQLite (dev) / MySQL (prod)

Pour tout groupement par période (évolution temporelle), l'expression de date diffère selon le driver. Le pattern déjà établi dans `LabelCompanyController` fait référence :

```php
// Réutiliser ce pattern dans MetricsController pour tout groupement temporel
$periodExpr = DB::connection()->getDriverName() === 'sqlite'
    ? "strftime('%Y-%m', created_at)"
    : "DATE_FORMAT(created_at, '%Y-%m')";
```

---

#### Vue d'ensemble opérationnelle

**Nombre total de collectes**
```php
Collection::whereBetween('start_date', [$debut, $fin])->count();
```

**Nombre total d'inscrits cumulé**
```php
QuizEvent::where('event_type', 'onedoc_clicked')
    ->whereHas('collection', fn($q) => $q->whereBetween('start_date', [$debut, $fin]))
    ->distinct('session_id')
    ->count('session_id');
```

**Nombre d'entreprises distinctes**
```php
Collection::whereBetween('start_date', [$debut, $fin])
    ->distinct('company_id')
    ->count('company_id');
```

**Évolution temporelle** — groupement par mois, à adapter selon la granularité souhaitée :
```php
$periodExpr = DB::connection()->getDriverName() === 'sqlite'
    ? "strftime('%Y-%m', created_at)"
    : "DATE_FORMAT(created_at, '%Y-%m')";

QuizEvent::where('event_type', 'onedoc_clicked')
    ->selectRaw("{$periodExpr} as period, COUNT(DISTINCT session_id) as nb_inscrits")
    ->groupByRaw($periodExpr)
    ->orderBy('period')
    ->get();
```
> La forme de visualisation (graphes, courbes) sera définie par la maquette. Des librairies comme Chart.js ou d3.js pourront être ajoutées selon les besoins — à documenter après validation des maquettes.

---

#### Engagement entreprises

**Taux de remplissage moyen**
```php
// Calculé en PHP : ratio par collecte, puis moyenne de ces ratios
$collections = Collection::whereNotNull('capacity')
    ->withCount(['quizEvents as nb_inscrits' => fn($q) =>
        $q->where('event_type', 'onedoc_clicked')->distinct('session_id')
    ])
    ->get();

$tauxMoyen = $collections->avg(fn($c) => $c->nb_inscrits / $c->capacity);
```
> Seules les collectes avec `capacity` renseigné sont prises en compte.

**Collectes récurrentes**
```php
Company::has('collections', '>=', 2)->count();
```

**Top entreprises contributrices**
```php
Company::withCount('collections')
    ->orderByDesc('collections_count')
    ->get(['id', 'name', 'collections_count']);
```

**Nombre de demandes de collecte**
```php
ContactRequest::whereBetween('created_at', [$debut, $fin])->count();
```

---

#### Performance du quiz — vue globale

Les trois taux partagent le même dénominateur de base — calculer `$nbStarted` une seule fois :

```php
$nbStarted    = QuizEvent::where('event_type', 'quiz_started')->distinct('session_id')->count('session_id');
$nbCompleted  = QuizEvent::where('event_type', 'p2_completed')->distinct('session_id')->count('session_id');
$nbEliminated = QuizEvent::where('event_type', 'p1_eliminated')->distinct('session_id')->count('session_id');
$nbEligible   = QuizEvent::where('event_type', 'p1_completed')->distinct('session_id')->count('session_id');
$nbClicked    = QuizEvent::where('event_type', 'onedoc_clicked')->distinct('session_id')->count('session_id');

// Taux de complétion    : $nbCompleted  / $nbStarted
// Taux d'élimination P1 : $nbEliminated / $nbStarted
// Taux de clic Onedoc  : $nbClicked    / $nbEligible
```

---

#### Performance du quiz — par question

**Principale cause de non-éligibilité**
Couvre P1 et P2 sans distinction.
```php
QuizEvent::where('event_type', 'question_answered')
    ->where('answer_result', 'incorrect')
    ->selectRaw('question_slug, COUNT(*) as nb_issues')
    ->groupBy('question_slug')
    ->orderByDesc('nb_issues')
    ->first();
```

**Taux de bonnes/mauvaises réponses par question**
```php
QuizEvent::where('event_type', 'question_answered')
    ->selectRaw('question_slug, part,
        SUM(CASE WHEN answer_result = "correct" THEN 1 ELSE 0 END) as nb_correct,
        SUM(CASE WHEN answer_result = "incorrect" THEN 1 ELSE 0 END) as nb_incorrect')
    ->groupBy('question_slug', 'part')
    ->get();
```

**Taux de skip par question (P2)**
```php
// Les form_skipped_from sont exclus du dénominateur : abandon total ≠ skip individuel
QuizEvent::where('part', 2)
    ->whereIn('event_type', ['question_answered', 'question_skipped'])
    ->selectRaw('question_slug,
        SUM(CASE WHEN event_type = "question_skipped" THEN 1 ELSE 0 END) as nb_skips,
        COUNT(*) as nb_vues')
    ->groupBy('question_slug')
    ->get();
// taux_skip = nb_skips / nb_vues (calculé côté PHP ou dans la API Resource)
```

**Question d'abandon P2**
```php
// Seuls les abandons via le bouton "passer toute la partie 2" sont capturés.
// Les fermetures de navigateur en cours de P2 ne sont pas trackées de façon fiable.
QuizEvent::where('event_type', 'form_skipped_from')
    ->selectRaw('question_slug, COUNT(*) as nb_abandons')
    ->groupBy('question_slug')
    ->orderByDesc('nb_abandons')
    ->get();
```

---

#### Engagement page prévention

**Temps passé sur la page scrollytelling**
```php
PageEvent::where('event_type', 'prevention_exited')->avg('time_on_page');
```

**Taux de rebond**
```php
$entered = PageEvent::where('event_type', 'prevention_entered')->count();
$bounced = PageEvent::where('event_type', 'prevention_exited')
    ->where('engaged', false)
    ->count();
// taux = $bounced / $entered
```

---

## Vie privée et conformité nLPD/RGPD

### Tracking comportemental — pas de cookie, pas de consentement requis

Le système de tracking n'utilise pas de cookies. L'UUID de session est stocké dans `sessionStorage` (pas `localStorage`, pas un cookie) : il est éphémère par construction, détruit à la fermeture de l'onglet, et jamais envoyé automatiquement au serveur. Les lois sur les cookies (ePrivacy Directive, nLPD suisse) visent spécifiquement les cookies et trackers persistants — `sessionStorage` n'entre pas dans ce périmètre.

Les données de tracking collectées (`quiz_events`, `page_events`) sont anonymes : aucune IP, aucun nom, aucun email n'est enregistré, et l'UUID ne peut pas être relié à une identité. Ces données ne constituent probablement pas des "données personnelles" au sens de la nLPD. **Aucune bannière cookie n'est nécessaire.**

### Formulaires de contact — données personnelles

Les formulaires de contact (`contact_requests`, `pme_contacts`) collectent des données personnelles (nom d'entreprise, email, téléphone). Ces données sont transmises au CTS pour le suivi des demandes de collecte. Chaque formulaire doit comporter une mention courte précisant :

> *Vos données sont transmises au CTS et utilisées uniquement dans le cadre de l'organisation de votre collecte de sang.*

Aucune politique de confidentialité complète n'est requise pour un projet étudiant, mais cette mention doit être visible avant la soumission du formulaire.

---

## Footer

Un footer est présent sur **toutes les pages** des trois espaces (site public, dashboard, sites cobrandés). Il mentionne explicitement que la plateforme est un **projet étudiant** réalisé dans le cadre d'un cours, sans lien officiel avec les HUG ou le CTS.

---

## Résumé des comptes utilisateurs nécessaires

| Espace | Utilisateur | Accès |
|--------|-------------|-------|
| Dashboard | CTS uniquement | Login requis |
| Site public | Tout le monde | Public |
| Sites cobrandés | Employés de l'entreprise partenaire | Public (lien distribué par l'entreprise) |
