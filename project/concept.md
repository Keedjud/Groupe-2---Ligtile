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
- Compteur d'inscrits en temps réel (polling API Onedoc fictif)
- Aperçu du co-branding (couleurs + logo)
- Bouton **Modifier** — navigue vers le formulaire pré-rempli (`#editer-{id}`)

---

#### 2b. Dashboard des métriques (`#metriques`)

Écran de surveillance des KPIs, accessible depuis la navigation du dashboard. Permet au CTS de suivre ses performances globales.

**KPIs suivis (sujet à modification) :**

| Métrique | Description |
|----------|-------------|
| Nombre total de collectes organisées | Sur une période sélectionnable |
| Nombre total d'inscrits | Cumul tous événements confondus |
| Taux de remplissage moyen | Inscrits / capacité cible par collecte |
| Taux de conversion quiz | % d'employés ayant terminé le quiz parmi ceux l'ayant commencé |
| Taux d'élimination partie 1 | % d'employés non-éligibles détectés en partie 1 |
| Principale cause d'élimination | Question éliminatoire la plus fréquemment échouée |
| Taux de skip partie 2 | % d'employés ayant sauté la partie informative |
| Nombre de demandes de contact | Formulaire de contact du site public |
| Collectes par entreprise | Nombre de collectes par entreprise partenaire |

---

### 3. Sites cobrandés (`hug-collecte.ch/{id_collecte}`)

Sites générés automatiquement pour chaque collecte, aux couleurs de l'entreprise partenaire.

**Parcours employé :**

1. **Page d'accueil** — informations de la collecte en cours, avec un compteur en temps réel du nombre d'inscrits (via API fictif CTS ↔ Onedoc).
2. **Page Prévention (Scrollytelling)** — contenu de prévention interactif sous forme de scrollytelling.
3. **Quizz d'éligibilité** — divisé en deux parties :

   - **Partie 1 — Questions éliminatoires** (intemporelles, peu nombreuses) :
     - But : filtrer les personnes non-éligibles avant qu'elles ne bloquent des créneaux.
     - Une mauvaise réponse redirige l'employé vers la section de prévention correspondante sur le site cobrandé.
   
   - **Partie 2 — Questions informatives** (non-éliminatoires, skippables) :
     - But : rappeler des informations importantes ("Ah oui, c'est juste, il y a ça").
     - Question concernant les préscriptions médicamenteuses et les départs de voyages sont un peu à part 
     - Une "mauvaise" réponse affiche une **pop-up** expliquant pourquoi cela peut être problématique, avec un lien vers la section de prévention correspondante.
     - L'employé peut **passer cette partie** pour accéder directement à l'inscription.

4. **Lien Onedoc** — à l'issue du quizz (ou après le skip), l'employé accède à la plateforme Onedoc pour s'inscrire réellement au créneau de la collecte.

---

## Intégration API fictive CTS ↔ Onedoc

Un API fictif est imaginé entre le CTS et Onedoc permettant de récupérer en temps réel le nombre d'inscrits à une collecte. Cette métrique est affichée sur la page d'accueil du site cobrandé pour un suivi en direct de la collecte.

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
