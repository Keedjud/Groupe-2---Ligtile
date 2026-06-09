<!-- CollecteDetail -->
<script setup>
import { computed, ref, onMounted } from 'vue'
import DashboardLayout from '../layouts/DashboardLayout.vue'
import QuestionFlow    from '../components/QuestionFlow.vue'
import { useCollectes }  from '../composables/useCollectes.js'
import { useOverlay }   from '../composables/useOverlay.js'

const props = defineProps({
  idCollecte: { type: [String, Number], required: true },
  allerVers:  { type: Function, required: true },
})

const { listeCollectes, trouverParId, chargerCollectes, supprimerCollecte, chargement } = useCollectes()
const { show: showOverlay } = useOverlay()

const collecte = computed(() => trouverParId(props.idCollecte))

onMounted(() => {
  if (!listeCollectes.value.length) chargerCollectes()
})

// Confirmation suppression
const confirmeSuppression = ref(false)

const questionsTerminees = ref(false)

function surFlowTermine(val) {
  questionsTerminees.value = val
}

async function gererSuppression() {
  if (!confirmeSuppression.value) {
    confirmeSuppression.value = true
    return
  }
  await supprimerCollecte(props.idCollecte)
  showOverlay('La collecte a bien été supprimée.')
  props.allerVers('#/collectes')
}

// Format suisse
function formaterDate(dateStr) {
  if (!dateStr) return ''
  return new Date(dateStr).toLocaleDateString('fr-CH', {
    day: '2-digit', month: '2-digit', year: 'numeric',
  })
}

const dureesJours = computed(() => {
  if (!collecte.value) return 0
  const d = new Date(collecte.value.date_fin) - new Date(collecte.value.date_debut)
  return Math.round(d / (1000 * 60 * 60 * 24))
})
</script>

<template>
  <DashboardLayout vueCourante="collectes" :allerVers="allerVers">

    <!-- Collecte introuvable -->
    <div v-if="!collecte" class="flex items-center justify-center py-16">
      <p class="font-sans text-h4 text-violet-700">Collecte introuvable.</p>
    </div>

    <!-- Détail -->
    <template v-else>
      <div class="mx-auto max-w-3xl rounded-[20px] bg-form-bg p-6 shadow-[0_4px_16px_rgba(104,23,100,0.10)]">

        <!-- En-tête : titre + logo -->
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <h1 class="font-sans text-h3 font-bold text-violet-800">Gestion de collecte</h1>
          <div class="h-[54px] w-[97px] overflow-hidden">
            <img
              v-if="collecte.logo_url"
              :src="collecte.logo_url"
              :alt="collecte.entreprise.nom"
              class="h-full w-full object-contain"
            />
            <div v-else class="flex h-full w-full items-center justify-center rounded bg-violet-100 font-sans text-small font-bold text-violet-700">
              {{ collecte.entreprise.nom.slice(0,3).toUpperCase() }}
            </div>
          </div>
        </div>

        <!-- Dates + durée + inscrits -->
        <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
          <!-- Dates -->
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <span class="font-sans text-regular">Date de début</span>
              <span class="font-sans text-regular font-bold">{{ formaterDate(collecte.date_debut) }}</span>
            </div>
            <div class="flex items-center gap-2.5">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-vert-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
              </svg>
              <span class="font-sans text-regular">Date de fin</span>
              <span class="font-sans text-regular font-bold">{{ formaterDate(collecte.date_fin) }}</span>
            </div>
          </div>
          <!-- Durée + inscrits -->
          <div class="text-left font-sans text-h5 sm:text-right">
            <p>Durée <strong>{{ dureesJours }}</strong> jours</p>
            <p>Nombre d'inscrit·es : <strong>{{ collecte.nb_inscrits }}</strong></p>
          </div>
        </div>

        <!-- Infos entreprise (lecture seule) -->
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:gap-10">
          <!-- Colonne adresse -->
          <div class="flex flex-1 flex-col gap-3">
            <div class="rounded-lg bg-white px-3 py-2.5 font-sans text-small font-medium text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)]">{{ collecte.adresse.rue }} {{ collecte.adresse.numero }}</div>
            <div class="flex flex-col gap-3 sm:flex-row">
              <div class="w-full rounded-lg bg-white px-3 py-2.5 font-sans text-small font-medium text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)] sm:w-28">{{ collecte.adresse.npa }}</div>
              <div class="flex-1 rounded-lg bg-white px-3 py-2.5 font-sans text-small font-medium text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)]">{{ collecte.adresse.ville }}</div>
            </div>
          </div>
          <!-- Colonne contacts -->
          <div class="flex flex-1 flex-col gap-3">
            <div class="rounded-lg bg-white px-3 py-2.5 font-sans text-small font-medium text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)]">{{ collecte.contact_email }}</div>
            <div class="rounded-lg bg-white px-3 py-2.5 font-sans text-small font-medium text-violet-950 shadow-[0_0_4px_rgba(0,0,0,0.25)]">{{ collecte.contact_phone }}</div>
          </div>
        </div>

        <!-- Flux de questions -->
        <div class="mb-8">
          <QuestionFlow
            :collecte="collecte"
            :allerVers="allerVers"
            @flow-termine="surFlowTermine"
          />
        </div>

        <!-- Barre d'actions bas de carte -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
          <!-- Supprimer -->
          <button
            @click="gererSuppression"
            class="rounded-[40px] px-5 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90"
            :class="confirmeSuppression ? 'bg-rouge-700' : 'bg-rouge-500'"
            :disabled="chargement"
          >
            {{ confirmeSuppression ? 'Confirmer la suppression' : 'Supprimer' }}
          </button>

          <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:justify-end">
            <!-- Analytics de l'entreprise -->
            <button
              v-if="collecte.entreprise.id"
              @click="allerVers('#/analytics?company_id=' + collecte.entreprise.id)"
              class="rounded-[40px] bg-white px-5 py-2.5 font-sans text-regular text-texte-secondary underline shadow-[0_0_4px_rgba(0,0,0,0.25)] transition-colors hover:bg-violet-50"
            >Voir les analytics</button>
            <!-- Modifier -->
            <button
              @click="allerVers('#/collectes/' + collecte.id + '/edit')"
              class="rounded-[40px] bg-violet-900 px-5 py-2.5 font-sans text-regular text-white transition-opacity hover:opacity-90"
            >Modifier</button>
            <!-- Fermer -->
            <button
              @click="allerVers('#/collectes')"
              class="rounded-[40px] bg-violet-900 px-5 py-2.5 font-sans text-regular text-white hover:opacity-90 transition-opacity"
            >Fermer</button>
          </div>
        </div>

      </div>
    </template>

  </DashboardLayout>
</template>
