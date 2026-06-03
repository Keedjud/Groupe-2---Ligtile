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

const { estConnecte, chargerUtilisateur } = useSessionAuth()

// Routes du dashboard
const tableauRoutes = [
  { pattern: '#/connexion',               cle: 'connexion',            component: Login          },
  { pattern: '#/collectes',               cle: 'collectes',            component: Collectes      },
  { pattern: '#/collectes/nouvelle',      cle: 'nouvelle',             component: CollecteForm   },
  { pattern: '#/collectes/:id/edit',      cle: 'edit',                 component: CollecteForm   },
  { pattern: '#/collectes/:id',           cle: 'detail',               component: CollecteDetail },
  { pattern: '#/analytics/:idEntreprise', cle: 'analytics-entreprise', component: Metriques      },
  { pattern: '#/analytics',               cle: 'analytics',            component: Metriques      },
]

const { composantActif, routeActive, parametres, allerVers } = useRouteurDashboard(tableauRoutes)

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
  if (estConnecte.value && routeActive.value?.cle === 'connexion') {
    allerVers('#/collectes')
  }
})

// Composant affiché
const vueAffichee = computed(() => {
  if (!estConnecte.value) return Login
  return composantActif.value
})

// Props composant
const propsComposant = computed(() => {
  const base = { allerVers }

  if (routeActive.value?.cle === 'detail') {
    return { ...base, idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'edit') {
    return { ...base, mode: 'edit', idCollecte: parametres.value.id }
  }
  if (routeActive.value?.cle === 'nouvelle') {
    return { ...base, mode: 'create' }
  }
  if (routeActive.value?.cle === 'analytics-entreprise') {
    return { ...base, idEntreprise: parametres.value.idEntreprise }
  }

  return base
})
</script>

<template>
  <div v-if="verifAuthEnCours" class="flex min-h-screen items-center justify-center">
    <span class="font-sans text-regular text-violet-400">Chargement…</span>
  </div>
  <component v-else :is="vueAffichee" v-bind="propsComposant" />
</template>
