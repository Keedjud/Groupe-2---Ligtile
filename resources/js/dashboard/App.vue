<!-- App.vue du dashboard : routeur hash avec params + garde d'authentification -->
<script setup>
import { computed, onMounted } from 'vue'
import { useRouteurDashboard } from './composables/useRouteur.js'
import { useSessionAuth }      from './composables/useSessionAuth.js'

import Login         from './views/Login.vue'
import Collectes     from './views/Collectes.vue'
import CollecteForm  from './views/CollecteForm.vue'
import CollecteDetail from './views/CollecteDetail.vue'
import Metriques     from './views/Metriques.vue'

const { estConnecte, chargerUtilisateur } = useSessionAuth()

// Routes du dashboard
// pattern = hash avec :params optionnels
const tableauRoutes = [
  { pattern: '#/connexion',             cle: 'connexion',       component: Login         },
  { pattern: '#/collectes',             cle: 'collectes',       component: Collectes     },
  { pattern: '#/collectes/nouvelle',    cle: 'nouvelle',        component: CollecteForm  },
  { pattern: '#/collectes/:id/edit',    cle: 'edit',            component: CollecteForm  },
  { pattern: '#/collectes/:id',         cle: 'detail',          component: CollecteDetail },
  { pattern: '#/analytics',             cle: 'analytics',       component: Metriques     },
]

const { composantActif, routeActive, parametres, allerVers } = useRouteurDashboard(tableauRoutes)

// Redirection si non connecté
onMounted(() => {
  chargerUtilisateur()
  // Si non connecté et pas déjà sur la page connexion → rediriger
  if (!estConnecte.value && routeActive.value?.cle !== 'connexion') {
    allerVers('#/connexion')
  }
  // Si déjà connecté et sur connexion → rediriger vers collectes
  if (estConnecte.value && routeActive.value?.cle === 'connexion') {
    allerVers('#/collectes')
  }
})

// Composant à afficher avec protection
const vueAffichee = computed(() => {
  if (!estConnecte.value) return Login
  return composantActif.value
})

// Propriétés injectées dans le composant actif
const propsComposant = computed(() => {
  const base = { allerVers }

  // Injecte l'id de collecte selon la route active
  if (routeActive.value?.cle === 'detail') {
    return { ...base, idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'edit') {
    return { ...base, mode: 'edit', idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'nouvelle') {
    return { ...base, mode: 'create' }
  }

  return base
})
</script>

<template>
  <component :is="vueAffichee" v-bind="propsComposant" />
</template>
