# Stack technique — Décisions clés

> La documentation complète de la stack (architecture, patterns, workflow Git, déploiement) se trouve dans [`README.md`](../README.md).
> Ce fichier conserve uniquement le tableau des décisions pour référence rapide.

| Point | Décision | Raison |
|-------|----------|--------|
| Architecture | API REST Laravel + SPA Vue 3 découplée | Appliquer les acquis cours, pas de nouveau framework |
| Auth | Sanctum en mode cookie (pas token Bearer) | httpOnly, résistant XSS, même domaine — localStorage = mauvaise pratique |
| Déploiement | GitHub Actions → push SSH → bare repo + hook | Logique de déploiement centralisée côté serveur, workflow Actions minimal |
| Branches | `feature/fix/chore` → `develop` → `main` | Convention standard, `develop` seule branche à merger dans `main` |
| CI sur `develop` | Workflow build-check (sans déploiement) | Détecte les erreurs de build avant qu'elles atteignent `main` |
| Co-branding | Theming DaisyUI via variables CSS dynamiques | Injecté dans le HTML, pas de CSS dupliqué par collecte |
| Architecture Vue | Multi-entry : 3 apps Vue indépendantes | Un espace = une app, rechargement complet uniquement entre espaces |
| Navigation | Hash-based sans Vue Router | Pattern maîtrisé, aucune librairie supplémentaire |
| État partagé | Composable module-level (ref singleton) | Pas besoin de Pinia pour la taille du projet |
| DB locale | SQLite | Aucun serveur à installer, onboarding immédiat |
| Questions quiz | Hard-codées dans `cobrand/constants/quizQuestions.js` | CTS n'a pas besoin d'éditer les questions via le dashboard |
