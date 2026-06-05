<<<<<<< HEAD
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
=======
<!-- App dashboard -->
<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouteurDashboard } from './composables/useRouteur.js'
import { useSessionAuth }      from './composables/useSessionAuth.js'

import Login          from './views/Login.vue'
import Collectes      from './views/Collectes.vue'
import CollecteForm   from './views/CollecteForm.vue'
import CollecteDetail from './views/CollecteDetail.vue'
import Metriques      from './views/Metriques.vue'
>>>>>>> develop

const { estConnecte, chargerUtilisateur } = useSessionAuth()

// Routes du dashboard
<<<<<<< HEAD
// pattern = hash avec :params optionnels
const tableauRoutes = [
  { pattern: '#/connexion',             cle: 'connexion',       component: Login         },
  { pattern: '#/collectes',             cle: 'collectes',       component: Collectes     },
  { pattern: '#/collectes/nouvelle',    cle: 'nouvelle',        component: CollecteForm  },
  { pattern: '#/collectes/:id/edit',    cle: 'edit',            component: CollecteForm  },
  { pattern: '#/collectes/:id',         cle: 'detail',          component: CollecteDetail },
  { pattern: '#/analytics',             cle: 'analytics',       component: Metriques     },
=======
const tableauRoutes = [
  { pattern: '#/connexion',               cle: 'connexion',            component: Login          },
  { pattern: '#/collectes',               cle: 'collectes',            component: Collectes      },
  { pattern: '#/collectes/nouvelle',      cle: 'nouvelle',             component: CollecteForm   },
  { pattern: '#/collectes/:id/edit',      cle: 'edit',                 component: CollecteForm   },
  { pattern: '#/collectes/:id',           cle: 'detail',               component: CollecteDetail },
  { pattern: '#/analytics/:idEntreprise', cle: 'analytics-entreprise', component: Metriques      },
  { pattern: '#/analytics',               cle: 'analytics',            component: Metriques      },
>>>>>>> develop
]

const { composantActif, routeActive, parametres, allerVers } = useRouteurDashboard(tableauRoutes)

<<<<<<< HEAD
// Redirection si non connecté
onMounted(() => {
  chargerUtilisateur()
  // Si non connecté et pas déjà sur la page connexion → rediriger
  if (!estConnecte.value && routeActive.value?.cle !== 'connexion') {
    allerVers('#/connexion')
  }
  // Si déjà connecté et sur connexion → rediriger vers collectes
=======
// Bloque l'affichage le temps de vérifier la session au chargement initial.
// Sans ce garde, vueAffichee retournerait Login (estConnecte encore faux)
// et l'utilisateur authentifié verrait un flash de la page de connexion.
const verifAuthEnCours = ref(true)

onMounted(async () => {
  await chargerUtilisateur()
  verifAuthEnCours.value = false

  if (!estConnecte.value && routeActive.value?.cle !== 'connexion') {
    allerVers('#/connexion')
  }
>>>>>>> develop
  if (estConnecte.value && routeActive.value?.cle === 'connexion') {
    allerVers('#/collectes')
  }
})

<<<<<<< HEAD
// Composant à afficher avec protection
=======
// Composant affiché
>>>>>>> develop
const vueAffichee = computed(() => {
  if (!estConnecte.value) return Login
  return composantActif.value
})

<<<<<<< HEAD
// Propriétés injectées dans le composant actif
const propsComposant = computed(() => {
  const base = { allerVers }

  // Injecte l'id de collecte selon la route active
=======
// Props composant
const propsComposant = computed(() => {
  const base = { allerVers }

>>>>>>> develop
  if (routeActive.value?.cle === 'detail') {
    return { ...base, idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'edit') {
    return { ...base, mode: 'edit', idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'nouvelle') {
    return { ...base, mode: 'create' }
  }
<<<<<<< HEAD
=======
  if (routeActive.value?.cle === 'analytics-entreprise') {
    return { ...base, idEntreprise: parametres.value.idEntreprise }
  }
>>>>>>> develop

  return base
})
</script>

<template>
<<<<<<< HEAD
  <component :is="vueAffichee" v-bind="propsComposant" />
=======
  <div v-if="verifAuthEnCours" class="flex min-h-screen items-center justify-center">
    <span class="font-sans text-regular text-violet-400">Chargement…</span>
  </div>
  <component v-else :is="vueAffichee" v-bind="propsComposant" />
>>>>>>> develop
</template>
