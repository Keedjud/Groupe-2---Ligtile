# Concept du projet

## Vue d'ensemble

Le projet est une plateforme multi-sites destinée à faciliter l'organisation de collectes de sang en entreprise, gérée par le CTS (Centre de Transfusion Sanguine).

---

## Les trois espaces du projet

### 1. Site public principal (`hug-collecte.ch`)

Site vitrine accessible à tous. Il comprend :

- **Formulaire de contact** — permet à une entreprise de contacter le CTS pour organiser une collecte.
- **Page Trophées** — met en avant les trophées des années précédentes.
- **Page Label** — présente le label CTS et les entreprises labellisées.
- **Page Information / Don du sang en entreprise** — informe sur les aspects pratiques de l'accueil d'une collecte.

---

### 2. Dashboard CTS (`hug-collecte.ch/dashboard`)

Interface de gestion réservée au CTS, protégée par login.

**Flux d'utilisation :**
1. Après la prise de contact via le site public, les deux parties s'accordent par téléphone sur les dates, le lieu et les informations nécessaires.
2. L'entreprise fournit au CTS ses **couleurs principales** et son **logo** (pour le co-branding).
3. Le CTS se connecte au dashboard et saisit toutes les informations de la collecte.
4. Le dashboard **génère automatiquement le site cobrandé** et son lien (`hug-collecte.ch/{id_collecte}`).
5. Le lien est transmis à l'entreprise partenaire dans le kit de communication.

---

### 3. Sites cobrandés (`hug-collecte.ch/{id_collecte}`)

Sites générés automatiquement pour chaque collecte, aux couleurs de l'entreprise partenaire.

**Parcours employé :**

1. **Page d'accueil** — informations de la collecte en cours, avec un compteur en temps réel du nombre d'inscrits (via API fictif CTS ↔ Onedoc).
2. **Page Prévention (Scrollytelling)** — contenu de prévention interactif sous forme de scrollytelling.
3. **Quizz d'éligibilité** — divisé en deux parties :

   - **Partie 1 — Questions éliminatoires** (intemporelles, peu nombreuses) :
     - But : filtrer les personnes non-éligibles avant qu'elles ne bloquent des créneaux.
     - Une mauvaise réponse redirige l'employé vers la section de prévention correspondante sur le site public.
   
   - **Partie 2 — Questions informatives** (non-éliminatoires, skippables) :
     - But : rappeler des informations importantes ("Ah oui, c'est juste, il y a ça").
     - Une "mauvaise" réponse affiche une **pop-up** expliquant pourquoi cela peut être problématique, avec un lien vers la section de prévention correspondante.
     - L'employé peut **passer cette partie** pour accéder directement à l'inscription.

4. **Lien Onedoc** — à l'issue du quizz (ou après le skip), l'employé accède à la plateforme Onedoc pour s'inscrire réellement au créneau de la collecte.

---

## Intégration API fictive CTS ↔ Onedoc

Un API fictif est imaginé entre le CTS et Onedoc permettant de récupérer en temps réel le nombre d'inscrits à une collecte. Cette métrique est affichée sur la page d'accueil du site cobrandé pour un suivi en direct de la collecte.

---

## Résumé des comptes utilisateurs nécessaires

| Espace | Utilisateur | Accès |
|--------|-------------|-------|
| Dashboard | CTS uniquement | Login requis, URL non-répertoriée |
| Site public | Tout le monde | Public |
| Sites cobrandés | Employés de l'entreprise partenaire | Public (lien distribué par l'entreprise) |
